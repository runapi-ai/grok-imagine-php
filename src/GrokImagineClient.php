<?php

declare(strict_types=1);

namespace RunApi\GrokImagine;

use RunApi\Core\BaseClient;
use RunApi\Core\ClientOptions;
use RunApi\GrokImagine\Resources\EditImage;
use RunApi\GrokImagine\Resources\ExtendVideo;
use RunApi\GrokImagine\Resources\ImageToVideo;
use RunApi\GrokImagine\Resources\TextToImage;
use RunApi\GrokImagine\Resources\TextToVideo;
use RunApi\GrokImagine\Resources\UpscaleImage;

/**
 * The Grok-Imagine multimodal generation API client.
 *
 * Exposes typed model resources plus the universal files and account resources.
 */
final class GrokImagineClient extends BaseClient
{
    /**
     * Text to image operations.
     */
    public readonly TextToImage $textToImage;
    /**
     * Text to video operations.
     */
    public readonly TextToVideo $textToVideo;
    /**
     * Image to video operations.
     */
    public readonly ImageToVideo $imageToVideo;
    /**
     * Edit image operations.
     */
    public readonly EditImage $editImage;
    /**
     * Extend video operations.
     */
    public readonly ExtendVideo $extendVideo;
    /**
     * Upscale image operations.
     */
    public readonly UpscaleImage $upscaleImage;

    /**
     * Create a Grok Imagine client with optional API key, base URL, and transport overrides.
     */
    public function __construct(ClientOptions $options = new ClientOptions())
    {
        parent::__construct($options);
        $this->textToImage = TextToImage::fromHttp($this->http);
        $this->textToVideo = TextToVideo::fromHttp($this->http);
        $this->imageToVideo = ImageToVideo::fromHttp($this->http);
        $this->editImage = EditImage::fromHttp($this->http);
        $this->extendVideo = ExtendVideo::fromHttp($this->http);
        $this->upscaleImage = UpscaleImage::fromHttp($this->http);
    }
}
