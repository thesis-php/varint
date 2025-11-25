<?php

declare(strict_types=1);

namespace Thesis\Varint;

use BcMath\Number;

/**
 * @api
 */
enum BcMath implements
    VarintCodec,
    ZigZagCodec
{
    case Codec;

    public function encodeVarint(Number $num): string
    {
        $buffer = '';

        while ($num->compare(127) > 0) {
            $low7 = $num->mod(128)->value;
            $num = $num->div(128)->round(mode: \RoundingMode::TowardsZero);
            $buffer .= \chr((int) $low7 | 0x80);
        }

        $buffer .= \chr((int) $num->value);

        return $buffer;
    }

    public function decodeVarint(string $value): Number
    {
        return $this->decodeVarintSized($value)->value;
    }

    public function decodeVarintSized(string $value): Sized
    {
        $num    = new Number(0);
        $offset = 0;

        for ($i = 0; $i < \strlen($value); ++$i) {
            $byte = \ord($value[$i]);
            $low7 = $byte & 0x7F;

            $num += new Number($low7)->mul(new Number(128)->pow($offset++));

            if (($byte & 0x80) === 0) {
                return new Sized($num, $i + 1);
            }
        }

        throw new MalformedVarintNumber($value);
    }

    public function size(Number $num): int
    {
        $bits = 0;

        for (; $num->compare(0) === 1; ++$bits) {
            $num = $num->div(2, scale: 0);
        }

        /** @var positive-int */
        return $bits === 0 ? 1 : (int) ceil($bits / 7);
    }

    public function encodeZigZag(Number $num): Number
    {
        return $num->compare(0) < 0 ? $num->mul(-2)->sub(1) : $num->mul(2);
    }

    public function decodeZigZag(Number $num): Number
    {
        $negative = $num->mod(2)->compare(1) === 0;

        $num = $num->div(2, scale: 0)->round(mode: \RoundingMode::TowardsZero);

        return $negative ? $num->add(1)->mul(-1) : $num;
    }
}
