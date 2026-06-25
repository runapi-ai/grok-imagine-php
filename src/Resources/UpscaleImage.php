<?php

declare(strict_types=1);

namespace RunApi\GrokImagine\Resources;

use RunApi\Core\Http\HttpClient;
use RunApi\Core\Models\TaskCreateResponse;
use RunApi\Core\RequestOptions;
use RunApi\Core\Resources\TypedConfiguredResource;
use RunApi\GrokImagine\Models\CompletedImageTaskResponse;
use RunApi\GrokImagine\Models\ImageTaskResponse;
use RunApi\GrokImagine\Types;

/**
 * Increases the resolution of a previously generated Grok Imagine video.
 */
readonly class UpscaleImage extends TypedConfiguredResource
{
    /**
     * Submits a Grok Imagine video upscale task and returns immediately with a task id.
     *
     * @param array{
     *   model: string,
     *   callback_url?: string,
     *   prompt?: string
     * } $params
     */
    public function create(array $params, ?RequestOptions $options = null): TaskCreateResponse
    {
        return parent::create($params, $options);
    }

    /**
     * Fetches the current status of a Grok Imagine video upscale task by id.
     */
    public function get(string $id, ?RequestOptions $options = null): ImageTaskResponse
    {
        $response = parent::get($id, $options);

        /** @var ImageTaskResponse $response */
        return $response;
    }

    /**
     * Submits a Grok Imagine video upscale task and polls until it completes.
     *
     * @param array{
     *   model: string,
     *   callback_url?: string,
     *   prompt?: string
     * } $params
     */
    public function run(array $params, ?RequestOptions $options = null): CompletedImageTaskResponse
    {
        $response = parent::run($params, $options);

        /** @var CompletedImageTaskResponse $response */
        return $response;
    }

    /**
     * Create the resource using the shared RunAPI HTTP transport.
     */
    public static function fromHttp(HttpClient $http): self
    {
        return new self(
            $http,
            '/api/v1/grok_imagine/upscale_image',
            'grok-imagine/upscale-image',
            ImageTaskResponse::class,
            CompletedImageTaskResponse::class,
            Types::UPSCALE_IMAGE_MODELS,
            'upscale-image',
            ImageTaskResponse::class,
            CompletedImageTaskResponse::class,
        );
    }
}
