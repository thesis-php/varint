<?php

declare(strict_types=1);

namespace Thesis\Varint;

/**
 * @api
 */
interface ZigZagCodec
{
    /**
     * @param numeric-string $value
     * @return numeric-string
     */
    public function encodeZigZag(string $value): string;

    /**
     * @param numeric-string $value
     * @return numeric-string
     */
    public function decodeZigZag(string $value): string;
}
