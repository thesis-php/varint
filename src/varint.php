<?php

declare(strict_types=1);

namespace Thesis\Varint;

/**
 * @api
 * @param numeric-string $value
 * @return non-empty-string
 */
function encodeVarint(string $value): string
{
    return selectVarintCodec()->encodeVarint($value);
}

/**
 * @api
 * @param non-empty-string $value
 * @return numeric-string
 * @throws MalformedVarintNumber
 */
function decodeVarint(string $value): string
{
    return selectVarintCodec()->decodeVarint($value);
}

/**
 * @api
 */
function selectVarintCodec(?VarintCodec $codec = null): VarintCodec
{
    /** @var ?VarintCodec $cache */
    static $cache;
    $cache ??= ($codec ?? Brick::Codec);

    return $cache;
}
