<?php

declare(strict_types=1);

namespace RunApi\GrokImagine;

/**
 * Constants for model slugs supported by the Grok Imagine PHP SDK.
 */
final class Types
{
    /** @var list<string> */
    public const TEXT_TO_IMAGE_MODELS = ['grok-imagine-text-to-image'];

    /** @var list<string> */
    public const TEXT_TO_VIDEO_MODELS = ['grok-imagine-text-to-video', 'grok-imagine-video-1.5-preview'];

    /** @var list<string> */
    public const IMAGE_TO_VIDEO_MODELS = ['grok-imagine-image-to-video', 'grok-imagine-video-1.5-preview'];

    /** @var list<string> */
    public const EDIT_IMAGE_MODELS = ['grok-imagine-edit-image'];

    /** @var list<string> */
    public const EXTEND_VIDEO_MODELS = [];

    /** @var list<string> */
    public const UPSCALE_IMAGE_MODELS = [];

    private function __construct()
    {
    }
}
