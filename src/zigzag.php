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
    return selectZigZagCodec()->encodeZigZag($value);
}

/**
 * @api
 * @param numeric-string $value
 * @return numeric-string
 */
function decodeZigZag(string $value): string
{
    return selectZigZagCodec()->decodeZigZag($value);
}

/**
 * @api
 */
function selectZigZagCodec(?ZigZagCodec $codec = null): ZigZagCodec
{
    /** @var ?ZigZagCodec $cache */
    static $cache;
    $cache ??= ($codec ?? Brick::Codec);

    return $cache;
}
