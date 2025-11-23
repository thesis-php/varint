<?php

declare(strict_types=1);

namespace Thesis\Varint;

/**
 * @api
 */
final readonly class Number
{
    /**
     * @param numeric-string $value
     * @param positive-int $size
     */
    public function __construct(
        public string $value,
        public int $size,
    ) {}
}
