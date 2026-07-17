<?php

declare(strict_types=1);

namespace RunApi\GrokImagine\Resources;

use RunApi\Core\Http\HttpClient;
use RunApi\Core\Models\TaskCreateResponse;
use RunApi\Core\RequestOptions;
use RunApi\Core\Resources\TypedConfiguredResource;
use RunApi\GrokImagine\Models\CompletedVideoTaskResponse;
use RunApi\GrokImagine\Models\VideoTaskResponse;
use RunApi\GrokImagine\Types;

/**
 * Generates videos from text prompts.
 */
readonly class TextToVideo extends TypedConfiguredResource
{
    /**
     * Submits a Grok Imagine text-to-video task and returns immediately with a task id.
     *
     * @param array{
     *   model: string,
     *   prompt: string,
     *   aspect_ratio?: string,
     *   callback_url?: string,
     *   duration_seconds?: int,
     *   enable_safety_checker?: bool,
     *   motion_style?: string,
     *   output_resolution?: string,
     *   reference_image_urls?: list<string>
     * } $params
     */
    public function create(array $params, ?RequestOptions $options = null): TaskCreateResponse
    {
        return parent::create($params, $options);
    }

    /**
     * Fetches the current status of a Grok Imagine text-to-video task by id.
     */
    public function get(string $id, ?RequestOptions $options = null): VideoTaskResponse
    {
        $response = parent::get($id, $options);

        /** @var VideoTaskResponse $response */
        return $response;
    }

    /**
     * Submits a Grok Imagine text-to-video task and polls until it completes.
     *
     * @param array{
     *   model: string,
     *   prompt: string,
     *   aspect_ratio?: string,
     *   callback_url?: string,
     *   duration_seconds?: int,
     *   enable_safety_checker?: bool,
     *   motion_style?: string,
     *   output_resolution?: string,
     *   reference_image_urls?: list<string>
     * } $params
     */
    public function run(array $params, ?RequestOptions $options = null): CompletedVideoTaskResponse
    {
        $response = parent::run($params, $options);

        /** @var CompletedVideoTaskResponse $response */
        return $response;
    }

    /**
     * Create the resource using the shared RunAPI HTTP transport.
     */
    public static function fromHttp(HttpClient $http): self
    {
        return new self(
            $http,
            '/api/v1/grok_imagine/text_to_video',
            'grok-imagine/text-to-video',
            VideoTaskResponse::class,
            CompletedVideoTaskResponse::class,
            Types::TEXT_TO_VIDEO_MODELS,
            'text-to-video',
            VideoTaskResponse::class,
            CompletedVideoTaskResponse::class,
        );
    }
}
