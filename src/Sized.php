<?php

declare(strict_types=1);

namespace Thesis\Varint;

use BcMath\Number;

/**
 * @api
 */
final readonly class Sized
{
    /**
     * @param positive-int $size
     */
    public function __construct(
        public Number $value,
        public int $size,
    ) {}
}
