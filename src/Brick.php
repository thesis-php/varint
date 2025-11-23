<?php

declare(strict_types=1);

namespace Thesis\Varint;

use Brick\Math\BigInteger;
use Brick\Math\RoundingMode;

/**
 * @api
 */
enum Brick implements
    VarintCodec,
    ZigZagCodec
{
    case Codec;

    public function encodeVarint(string $value): string
    {
        $num    = BigInteger::of($value);
        $buffer = '';

        while ($num->isGreaterThan(127)) {
            $low7 = $num->mod(128)->toInt();
            $num = $num->dividedBy(128, RoundingMode::DOWN);
            $buffer .= \chr($low7 | 0x80);
        }

        $buffer .= \chr($num->toInt());

        return $buffer;
    }

    public function decodeVarint(string $value): string
    {
        return $this->decodeVarintSized($value)->value;
    }

    public function size(string $value): int
    {
        /** @var positive-int */
        return (int) ceil(\strlen(BigInteger::of($value)->toBase(2)) / 7);
    }

    public function decodeVarintSized(string $value): Number
    {
        $num    = BigInteger::zero();
        $offset = 0;

        for ($i = 0; $i < \strlen($value); ++$i) {
            $byte = \ord($value[$i]);
            $low7 = $byte & 0x7F;

            $num = $num->plus(
                BigInteger::of($low7)->multipliedBy(BigInteger::of(128)->power($offset++)),
            );

            if (($byte & 0x80) === 0) {
                return new Number((string) $num, $i + 1);
            }
        }

        throw new MalformedVarintNumber($value);
    }

    public function encodeZigZag(string $value): string
    {
        $num = BigInteger::of($value);

        return (string) ($num->isNegative() ? $num->multipliedBy(-2)->minus(1) : $num->multipliedBy(2));
    }

    public function decodeZigZag(string $value): string
    {
        $num = BigInteger::of($value);

        $negative = $num->and(BigInteger::one())->isEqualTo(BigInteger::one());

        $num = $num->dividedBy(2, RoundingMode::DOWN);

        return (string) ($negative ? $num->plus(1)->multipliedBy(-1) : $num);
    }
}
