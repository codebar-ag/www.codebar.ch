# Changelog

All notable changes to `json-body-guard` will be documented in this file.

## Unreleased

- Initial release: `JsonBodyGuard` with `allow()`, `denyAll()`, `maxBytes()`, `enable()`, `inspect()` and `guard()`.
- Config file `config/json-body-guard.php`, loaded with `fromConfigFile()` before the framework boots.
- Optional Laravel service provider that merges and publishes the config file.

