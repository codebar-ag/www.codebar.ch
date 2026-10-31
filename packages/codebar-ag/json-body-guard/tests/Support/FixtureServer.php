<?php

declare(strict_types=1);

namespace CodebarAg\JsonBodyGuard\Tests\Support;

use RuntimeException;

final class FixtureServer
{
    /**
     * @param  resource  $process
     */
    private function __construct(
        private $process,
        private readonly string $host,
        private readonly int $port,
    ) {}

    public static function start(): self
    {
        $host = '127.0.0.1';
        $port = self::freePort($host);
        $root = dirname(__DIR__).'/Fixtures/public';

        $process = proc_open(
            [PHP_BINARY, '-S', "{$host}:{$port}", '-t', $root, $root.'/index.php'],
            [1 => ['file', '/dev/null', 'w'], 2 => ['file', '/dev/null', 'w']],
            $pipes,
        );

        if (! is_resource($process)) {
            throw new RuntimeException('Could not start the PHP built-in server.');
        }

        $server = new self($process, $host, $port);
        $server->waitUntilReady();

        return $server;
    }

    public function stop(): void
    {
        proc_terminate($this->process);
        proc_close($this->process);
    }

    public function get(string $path): Response
    {
        return $this->request("GET {$path} HTTP/1.1\r\nHost: {$this->host}\r\nConnection: close\r\n\r\n");
    }

    public function post(string $path, string $body, string $contentType, bool $chunked = false): Response
    {
        $headers = "POST {$path} HTTP/1.1\r\nHost: {$this->host}\r\nConnection: close\r\nContent-Type: {$contentType}\r\n";

        if ($chunked) {
            return $this->request($headers."Transfer-Encoding: chunked\r\n\r\n".dechex(strlen($body))."\r\n{$body}\r\n0\r\n\r\n");
        }

        return $this->request($headers.'Content-Length: '.strlen($body)."\r\n\r\n{$body}");
    }

    private function request(string $raw): Response
    {
        $errno = 0;
        $error = '';
        $socket = self::quietly(function () use (&$errno, &$error) {
            return stream_socket_client("tcp://{$this->host}:{$this->port}", $errno, $error, 5);
        });

        if ($socket === false) {
            throw new RuntimeException("Could not connect to the fixture server: {$error}");
        }

        fwrite($socket, $raw);
        $response = stream_get_contents($socket);
        fclose($socket);

        if ($response === false || $response === '') {
            throw new RuntimeException('Empty response from the fixture server.');
        }

        [$head, $body] = array_pad(explode("\r\n\r\n", $response, 2), 2, '');
        preg_match('#^HTTP/\d\.\d (\d{3})#', $head, $matches);

        return new Response((int) ($matches[1] ?? 0), $body);
    }

    private function waitUntilReady(): void
    {
        for ($attempt = 0; $attempt < 50; $attempt++) {
            $socket = self::quietly(fn () => stream_socket_client("tcp://{$this->host}:{$this->port}", timeout: 0.2));

            if ($socket !== false) {
                fclose($socket);

                return;
            }

            usleep(100_000);
        }

        throw new RuntimeException('The PHP built-in server did not come up.');
    }

    /**
     * @template T
     *
     * @param  callable(): T  $callback
     * @return T
     */
    private static function quietly(callable $callback): mixed
    {
        set_error_handler(static fn (): bool => true);

        try {
            return $callback();
        } finally {
            restore_error_handler();
        }
    }

    private static function freePort(string $host): int
    {
        $socket = stream_socket_server("tcp://{$host}:0", $errno, $error);

        if ($socket === false) {
            throw new RuntimeException("Could not reserve a port: {$error}");
        }

        $name = stream_socket_get_name($socket, false);
        fclose($socket);

        return (int) substr((string) $name, strrpos((string) $name, ':') + 1);
    }
}
