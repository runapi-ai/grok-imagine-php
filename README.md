# Grok Imagine PHP SDK for RunAPI

[![Packagist](https://img.shields.io/packagist/v/runapi-ai/grok-imagine)](https://packagist.org/packages/runapi-ai/grok-imagine)
[![License](https://img.shields.io/github/license/runapi-ai/grok-imagine-php)](https://github.com/runapi-ai/grok-imagine-php/blob/main/LICENSE)

The Grok Imagine PHP SDK is the Composer package for Grok Imagine on RunAPI. Use it when your PHP application needs associative-array request bodies for image and video generation, task status lookup, polling helpers, file helpers, and consistent RunAPI errors.

## Install

```bash
composer require runapi-ai/grok-imagine
```

## Quick start

```php
<?php

require __DIR__ . "/vendor/autoload.php";

use RunApi\GrokImagine\GrokImagineClient;

$client = new GrokImagineClient(); // reads RUNAPI_API_KEY

$task = $client->textToImage->create([
    'model' => 'grok-imagine-text-to-image',
    'prompt' => 'A precise product render on white marble',
]);

$status = $client->textToImage->get($task->id);

$result = $client->textToImage->run([
    'model' => 'grok-imagine-text-to-image',
    'prompt' => 'A serene mountain lake at dawn',
]);

echo reset($result->images)->url . PHP_EOL;
```

Use `create()` to submit a task and return quickly, `get()` to fetch the latest task state, and `run()` when a script should create and poll until completion. In web request handlers, prefer `create()` plus webhook or later `get()` polling so a worker is not held open.

Returned file URLs are temporary. Download and store generated files in your own durable storage within the retention window.

All SDK exceptions inherit from `RunApi\Core\Errors\RunApiException`, including validation, authentication, rate limit, task failure, and task timeout errors.

## Links

- Model page: https://runapi.ai/models/grok-imagine
- SDK docs: https://runapi.ai/docs#sdk-grok-imagine
- Product docs: https://runapi.ai/docs#grok-imagine
- Video 1.5 Preview pricing and rate limits: https://runapi.ai/models/grok-imagine/video-1.5-preview
- Video 1.5 Fast pricing and rate limits: https://runapi.ai/models/grok-imagine/video-1.5-fast
- Text-to-video pricing and rate limits: https://runapi.ai/models/grok-imagine/text-to-video
- Image-to-video pricing and rate limits: https://runapi.ai/models/grok-imagine/image-to-video
- Full catalog: https://runapi.ai/models
- GitHub repository: https://github.com/runapi-ai/grok-imagine-php
- Multi-language SDK repository: https://github.com/runapi-ai/grok-imagine-sdk

## License

Licensed under the Apache License, Version 2.0.
