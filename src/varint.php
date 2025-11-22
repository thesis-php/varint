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
    return varintCodec()->encodeVarint($value);
}

/**
 * @api
 * @param non-empty-string $value
 * @return numeric-string
 * @throws MalformedVarintNumber
 */
function decodeVarint(string $value): string
{
    return varintCodec()->decodeVarint($value);
}

/**
 * @api
 */
function varintCodec(?VarintCodec $codec = null): VarintCodec
{
    /** @var ?VarintCodec $cache */
    static $cache;
    $cache ??= ($codec ?? selectVarintCodec());

    return $cache;
}

/**
 * @api
 */
function selectVarintCodec(): VarintCodec
{
    return match (true) {
        \extension_loaded('gmp') => Gmp::Codec,
        \extension_loaded('bcmath') => BcMath::Codec,
        default => throw new \RuntimeException('No supported varint driver: install the bcmath or gmp extension.'),
    };
}
