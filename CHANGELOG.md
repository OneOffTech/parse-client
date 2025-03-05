# Changelog

All notable changes to `oneofftech-parse-client` will be documented in this file.

## v0.2.0 - 2025-03-05

### What's Changed

This release brings compatibility with [Parxy 0.5.0](https://github.com/OneOffTech/parxy/releases/tag/v0.5.0). You should update your Parxy instance too!

**Breaking changes**

- Mime type parameter removed from the `parse()` method signature

**All changes**

* Add LLamaCloud support by @avvertix in https://github.com/OneOffTech/parse-client/pull/5
* Add Unstructured processor by @avvertix in https://github.com/OneOffTech/parse-client/pull/6
* Remove mime type parameter, file discovered automatically by @avvertix in https://github.com/OneOffTech/parse-client/pull/7
* Consider not found test to return html as a web page might be returne… by @avvertix in https://github.com/OneOffTech/parse-client/pull/8

**Full Changelog**: https://github.com/OneOffTech/parse-client/compare/v0.1.0...v0.2.0

## v0.1.0 - 2025-02-21

The first release of the Parse client library.

This library allows to use [OneOffTech/parxy](https://github.com/OneOffTech/parxy) to extract text from PDF files.
