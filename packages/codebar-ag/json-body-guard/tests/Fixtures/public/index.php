<?php

declare(strict_types=1);

use CodebarAg\JsonBodyGuard\JsonBodyGuard;

$root = __DIR__;

while (! file_exists($root.'/vendor/autoload.php') && dirname($root) !== $root) {
    $root = dirname($root);
}

require $root.'/vendor/autoload.php';

JsonBodyGuard::allow('/webhooks/*')->maxBytes(1024)->guard();

header('Content-Type: text/plain');
echo 'reached-app';
