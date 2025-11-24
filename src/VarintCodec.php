<?php

declare(strict_types=1);

namespace Thesis\Varint;

use BcMath\Number;

/**
 * @api
 */
interface VarintCodec
{
    /**
     * @return non-empty-string
     */
    public function encodeVarint(Number $num): string;

    /**
     * @param non-empty-string $value
     * @throws MalformedVarintNumber
     */
    public function decodeVarint(string $value): Number;

    /**
     * @param non-empty-string $value
     * @throws MalformedVarintNumber
     */
    public function decodeVarintSized(string $value): Sized;

    /**
     * @return positive-int
     */
    public function size(Number $num): int;
}
