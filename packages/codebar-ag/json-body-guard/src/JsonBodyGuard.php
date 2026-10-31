<?php

declare(strict_types=1);

namespace CodebarAg\JsonBodyGuard;

final class JsonBodyGuard
{
    public const int DEFAULT_MAX_BYTES = 64 * 1024;

    /** @var list<string> */
    private array $patterns = [];

    private int $maxBytes = self::DEFAULT_MAX_BYTES;

    private string $inputStream = 'php://input';

    private bool $enabled = true;

    /**
     * @param  array<mixed>  $config
     */
    public static function fromConfig(array $config): self
    {
        $allow = $config['allow'] ?? [];
        $patterns = is_array($allow) ? array_values(array_filter($allow, is_string(...))) : [];

        $guard = self::allow(...$patterns);

        if (isset($config['max_bytes']) && is_numeric($config['max_bytes'])) {
            $guard->maxBytes((int) $config['max_bytes']);
        }

        if (array_key_exists('enabled', $config)) {
            $guard->enable((bool) $config['enabled']);
        }

        return $guard;
    }

    public static function fromConfigFile(string $path): self
    {
        $config = require $path;

        return self::fromConfig(is_array($config) ? $config : []);
    }

    public static function allow(string ...$patterns): self
    {
        $guard = new self;
        $guard->patterns = array_values(array_map(self::normalizePath(...), $patterns));

        return $guard;
    }

    public static function denyAll(): self
    {
        return new self;
    }

    public function maxBytes(int $bytes): self
    {
        $this->maxBytes = max(0, $bytes);

        return $this;
    }

    public function enable(bool $enabled = true): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function readingFrom(string $inputStream): self
    {
        $this->inputStream = $inputStream;

        return $this;
    }

    /**
     * @param  array<mixed>|null  $server
     */
    public function guard(?array $server = null): void
    {
        $rejection = $this->inspect($server ?? $_SERVER);

        if ($rejection === null) {
            return;
        }

        $rejection->send();

        exit;
    }

    /**
     * @param  array<mixed>  $server
     */
    public function inspect(array $server): ?Rejection
    {
        if (! $this->enabled || ! $this->declaresJson($server)) {
            return null;
        }

        $path = $this->requestPath($server);
        $length = $this->bodyLength($server);

        if ($length === 0) {
            return null;
        }

        if (! $this->allows($path)) {
            return new Rejection(Reason::PathNotAllowed, $path);
        }

        if ($length > $this->maxBytes) {
            return new Rejection(Reason::TooLarge, $path);
        }

        return null;
    }

    public function allows(string $path): bool
    {
        $path = self::normalizePath($path);

        foreach ($this->patterns as $pattern) {
            if ($pattern === $path) {
                return true;
            }

            if (str_contains($pattern, '*') && preg_match(self::patternToRegex($pattern), $path) === 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  array<mixed>  $server
     */
    private function declaresJson(array $server): bool
    {
        $contentType = $server['CONTENT_TYPE'] ?? $server['HTTP_CONTENT_TYPE'] ?? null;

        if (! is_string($contentType)) {
            return false;
        }

        $contentType = strtolower($contentType);

        return str_contains($contentType, '/json') || str_contains($contentType, '+json');
    }

    /**
     * @param  array<mixed>  $server
     */
    private function bodyLength(array $server): int
    {
        $length = $server['CONTENT_LENGTH'] ?? $server['HTTP_CONTENT_LENGTH'] ?? null;

        if (is_numeric($length)) {
            return max(0, (int) $length);
        }

        set_error_handler(static fn (): bool => true);

        try {
            $body = file_get_contents($this->inputStream, false, null, 0, max(1, $this->maxBytes + 1));
        } finally {
            restore_error_handler();
        }

        return $body === false ? 0 : strlen($body);
    }

    /**
     * @param  array<mixed>  $server
     */
    private function requestPath(array $server): string
    {
        $uri = $server['REQUEST_URI'] ?? $server['PATH_INFO'] ?? '/';
        $path = is_string($uri) ? parse_url($uri, PHP_URL_PATH) : null;

        return self::normalizePath(is_string($path) ? rawurldecode($path) : '/');
    }

    private static function normalizePath(string $path): string
    {
        $path = '/'.ltrim($path, '/');

        return $path === '/' ? $path : rtrim($path, '/');
    }

    private static function patternToRegex(string $pattern): string
    {
        return '#^'.str_replace('\*', '.*', preg_quote($pattern, '#')).'$#';
    }
}
