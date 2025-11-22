<?php

declare(strict_types=1);

namespace Thesis\Varint;

/**
 * @api
 * @param numeric-string $value
 * @return numeric-string
 */
function encodeZigZag(string $value): string
{
    return zigZagCodec()->encodeZigZag($value);
}

/**
 * @api
 * @param numeric-string $value
 * @return numeric-string
 */
function decodeZigZag(string $value): string
{
    return zigZagCodec()->decodeZigZag($value);
}

/**
 * @api
 */
function zigZagCodec(?ZigZagCodec $codec = null): ZigZagCodec
{
    /** @var ?ZigZagCodec $cache */
    static $cache;
    $cache ??= ($codec ?? selectZigZagCodec());

    return $cache;
}

/**
 * @api
 */
function selectZigZagCodec(): ZigZagCodec
{
    return match (true) {
        \extension_loaded('gmp') => Gmp::Codec,
        \extension_loaded('bcmath') => BcMath::Codec,
        default => throw new \RuntimeException('No supported varint driver: install the bcmath or gmp extension.'),
    };
}
