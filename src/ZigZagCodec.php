<?php

declare(strict_types=1);

namespace Thesis\Varint;

use BcMath\Number;

/**
 * @api
 */
interface ZigZagCodec
{
    public function encodeZigZag(Number $num): Number;

    public function decodeZigZag(Number $num): Number;
}
