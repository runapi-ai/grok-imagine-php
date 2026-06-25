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
 * Appends new footage to a previously generated Grok Imagine video, starting from a chosen timestamp.
 */
readonly class ExtendVideo extends TypedConfiguredResource
{
    /**
     * Submits a Grok Imagine video extension task and returns immediately with a task id.
     *
     * @param array{
     *   model: string,
     *   callback_url?: string,
     *   extension_duration_seconds?: int,
     *   prompt?: string
     * } $params
     */
    public function create(array $params, ?RequestOptions $options = null): TaskCreateResponse
    {
        return parent::create($params, $options);
    }

    /**
     * Fetches the current status of a Grok Imagine video extension task by id.
     */
    public function get(string $id, ?RequestOptions $options = null): VideoTaskResponse
    {
        $response = parent::get($id, $options);

        /** @var VideoTaskResponse $response */
        return $response;
    }

    /**
     * Submits a Grok Imagine video extension task and polls until it completes.
     *
     * @param array{
     *   model: string,
     *   callback_url?: string,
     *   extension_duration_seconds?: int,
     *   prompt?: string
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
            '/api/v1/grok_imagine/extend_video',
            'grok-imagine/extend',
            VideoTaskResponse::class,
            CompletedVideoTaskResponse::class,
            Types::EXTEND_VIDEO_MODELS,
            'extend-video',
            VideoTaskResponse::class,
            CompletedVideoTaskResponse::class,
        );
    }
}
