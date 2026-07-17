<?php

declare(strict_types=1);

namespace RunApi\GrokImagine\Tests\Unit;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use RunApi\Core\ClientOptions;
use RunApi\Core\Errors\ValidationException;
use RunApi\Core\Tests\Fixtures\QueueHttpClient;
use RunApi\GrokImagine\GrokImagineClient;
use RunApi\GrokImagine\Models\CompletedImageTaskResponse;
use RunApi\GrokImagine\Resources\EditImage;
use RunApi\GrokImagine\Resources\ExtendVideo;
use RunApi\GrokImagine\Resources\ImageToVideo;
use RunApi\GrokImagine\Resources\TextToImage;
use RunApi\GrokImagine\Resources\TextToVideo;
use RunApi\GrokImagine\Resources\UpscaleImage;

final class GrokImagineClientTest extends TestCase
{
    public function testExposesTypedResources(): void
    {
        $client = new GrokImagineClient(new ClientOptions(apiKey: 'k', httpClient: new QueueHttpClient([]), maxRetries: 0));

        self::assertInstanceOf(TextToImage::class, $client->textToImage);
        self::assertInstanceOf(TextToVideo::class, $client->textToVideo);
        self::assertInstanceOf(ImageToVideo::class, $client->imageToVideo);
        self::assertInstanceOf(EditImage::class, $client->editImage);
        self::assertInstanceOf(ExtendVideo::class, $client->extendVideo);
        self::assertInstanceOf(UpscaleImage::class, $client->upscaleImage);
    }

    public function testCreatePostsCompactedBodyToCorrectPath(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
        ]);
        $client = new GrokImagineClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $task = $client->textToImage->create([
            'model' => 'grok-imagine-text-to-image',
            'prompt' => 'A product render',
            'callback_url' => '',
            'seed' => null,
        ]);

        $body = json_decode((string) $transport->requests[0]->getBody(), true, flags: JSON_THROW_ON_ERROR);

        self::assertSame('task_1', $task->id);
        self::assertSame('/api/v1/grok_imagine/text_to_image', $transport->requests[0]->getUri()->getPath());
        self::assertSame('grok-imagine-text-to-image', $body['model']);
        self::assertArrayNotHasKey('callback_url', $body);
        self::assertArrayNotHasKey('seed', $body);
    }

    public function testRunReturnsTypedCompletedResponseAndPreservesUnknownFields(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
            new Response(200, [], '{"id":"task_1","status":"completed","images":[{"url":"https://file.runapi.ai/result"}],"extra_field":"kept"}'),
        ]);
        $client = new GrokImagineClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $result = $client->textToImage->run([
            'model' => 'grok-imagine-text-to-image',
            'prompt' => 'A product render',
        ]);

        self::assertInstanceOf(CompletedImageTaskResponse::class, $result);
        self::assertSame('https://file.runapi.ai/result', $result->images[0]->url);
        self::assertSame('kept', $result->toArray()['extra_field']);
        self::assertSame('/api/v1/grok_imagine/text_to_image/task_1', $transport->requests[1]->getUri()->getPath());
    }

    public function testCompletedResponseRequiresResultFiles(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
            new Response(200, [], '{"id":"task_1","status":"completed"}'),
        ]);
        $client = new GrokImagineClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('images is required');

        $client->textToImage->run([
            'model' => 'grok-imagine-text-to-image',
            'prompt' => 'A product render',
        ]);
    }

    public function testRejectsInvalidContractEnum(): void
    {
        $client = new GrokImagineClient(new ClientOptions(apiKey: 'k', httpClient: new QueueHttpClient([]), maxRetries: 0));

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('aspect_ratio must be one of the allowed values');

        $client->textToImage->create([
        'model' => 'grok-imagine-text-to-image',
        'prompt' => 'A product render',
        'aspect_ratio' => 'not-valid',
        ]);
    }

    public function testSecondaryResourceUsesItsOwnPath(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_2"}'),
        ]);
        $client = new GrokImagineClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $client->textToVideo->create([
            'model' => 'grok-imagine-text-to-video',
            'prompt' => 'A product render',
        ]);

        self::assertSame('/api/v1/grok_imagine/text_to_video', $transport->requests[0]->getUri()->getPath());
    }

    public function testPreviewVideoRequestsUseSharedModelSlug(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_preview_t2v"}'),
            new Response(200, [], '{"id":"task_preview_i2v"}'),
        ]);
        $client = new GrokImagineClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $client->textToVideo->create([
            'model' => 'grok-imagine-video-1.5-preview',
            'prompt' => 'A quiet city rain scene',
            'aspect_ratio' => 'auto',
            'duration_seconds' => 15,
            'output_resolution' => '720p',
        ]);
        $client->imageToVideo->create([
            'model' => 'grok-imagine-video-1.5-preview',
            'source_image_urls' => ['https://cdn.runapi.ai/public/samples/result.png'],
            'prompt' => 'Animate the still image',
            'aspect_ratio' => 'auto',
            'duration_seconds' => 8,
            'output_resolution' => '720p',
        ]);

        $textBody = json_decode((string) $transport->requests[0]->getBody(), true, flags: JSON_THROW_ON_ERROR);
        $imageBody = json_decode((string) $transport->requests[1]->getBody(), true, flags: JSON_THROW_ON_ERROR);

        self::assertSame('grok-imagine-video-1.5-preview', $textBody['model']);
        self::assertSame('auto', $textBody['aspect_ratio']);
        self::assertArrayNotHasKey('motion_style', $textBody);
        self::assertSame('grok-imagine-video-1.5-preview', $imageBody['model']);
        self::assertSame(['https://cdn.runapi.ai/public/samples/result.png'], $imageBody['source_image_urls']);
        self::assertArrayNotHasKey('source_task_id', $imageBody);
    }

    public function testFastVideoRequestsUseFastInputs(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_fast_t2v"}'),
            new Response(200, [], '{"id":"task_fast_i2v"}'),
        ]);
        $client = new GrokImagineClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $client->textToVideo->create([
            'model' => 'grok-imagine-video-1.5-fast',
            'prompt' => 'A paper plane crossing a sunlit room',
            'reference_image_urls' => ['https://cdn.runapi.ai/public/samples/result.png'],
            'aspect_ratio' => '16:9',
            'duration_seconds' => 5,
            'output_resolution' => '720p',
        ]);
        $client->imageToVideo->create([
            'model' => 'grok-imagine-video-1.5-fast',
            'source_image_urls' => ['https://cdn.runapi.ai/public/samples/result.png'],
            'reference_image_urls' => ['https://cdn.runapi.ai/public/samples/reference.png'],
            'prompt' => 'Animate the still image',
            'aspect_ratio' => '3:2',
            'duration_seconds' => 21,
            'output_resolution' => '720p',
        ]);

        $textBody = json_decode((string) $transport->requests[0]->getBody(), true, flags: JSON_THROW_ON_ERROR);
        $imageBody = json_decode((string) $transport->requests[1]->getBody(), true, flags: JSON_THROW_ON_ERROR);

        self::assertSame('grok-imagine-video-1.5-fast', $textBody['model']);
        self::assertSame(['https://cdn.runapi.ai/public/samples/result.png'], $textBody['reference_image_urls']);
        self::assertSame('720p', $textBody['output_resolution']);
        self::assertSame('grok-imagine-video-1.5-fast', $imageBody['model']);
        self::assertSame(['https://cdn.runapi.ai/public/samples/result.png'], $imageBody['source_image_urls']);
        self::assertSame(['https://cdn.runapi.ai/public/samples/reference.png'], $imageBody['reference_image_urls']);
        self::assertArrayNotHasKey('source_task_id', $imageBody);
    }
}
