<?php

declare(strict_types=1);

namespace Thesis\Varint;

/**
 * @api
 */
interface VarintCodec
{
    /**
     * @param numeric-string $value
     * @return non-empty-string
     */
    public function encodeVarint(string $value): string;

    /**
     * @param non-empty-string $value
     * @return numeric-string
     * @throws MalformedVarintNumber
     */
    public function decodeVarint(string $value): string;

    /**
     * @param numeric-string $value
     * @return positive-int
     */
    public function size(string $value): int;

    /**
     * @param non-empty-string $value
     * @throws MalformedVarintNumber
     */
    public function decodeVarintSized(string $value): Number;
}
