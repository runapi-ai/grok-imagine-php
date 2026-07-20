# Changelog

## [v0.1.3](https://github.com/runapi-ai/grok-imagine-php/releases/tag/v0.1.3) - 2026-07-20

### Breaking
- Replace Grok Imagine image-to-video `source_image_urls` with scalar `source_image_url`.
  Migration: Pass the source image as `source_image_url`; keep `reference_image_urls` only for optional Fast reference images.


## [v0.1.2](https://github.com/runapi-ai/grok-imagine-php/releases/tag/v0.1.2) - 2026-07-17

### Changed
- Add Fast text-to-video and image-to-video model support and request metadata.

## [v0.1.1](https://github.com/runapi-ai/grok-imagine-php/releases/tag/v0.1.1) - 2026-07-16

### Changed
- Add `grok-imagine-video-1.5-preview` to the Grok Imagine Composer package request surfaces, contract data, tests, and public README.

## [v0.1.0](https://github.com/runapi-ai/grok-imagine-php/releases/tag/v0.1.0) - 2026-06-25

### Added
- Publish the first RunAPI PHP Composer package release for `runapi-ai/grok-imagine`.
- Include typed PHP client resources, package README, Apache-2.0 license, and Composer CI.
