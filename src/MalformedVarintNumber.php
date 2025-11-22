<?php

declare(strict_types=1);

namespace Thesis\Varint;

/**
 * @api
 */
final class MalformedVarintNumber extends \UnexpectedValueException
{
    /**
     * @param non-empty-string $number
     */
    public function __construct(
        public readonly string $number,
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        parent::__construct("The number '{$number}' is not a valid varint.", $code, $previous);
    }
}
