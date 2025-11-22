<?php

declare(strict_types=1);

namespace Thesis\Varint;

/**
 * @api
 */
enum Gmp implements
    VarintCodec,
    ZigZagCodec
{
    case Codec;

    public function encodeVarint(string $value): string
    {
        $buffer = '';
        $num    = gmp_init($value, 10);

        while (gmp_cmp($num, 127) > 0) {
            $low7 = gmp_intval(gmp_and($num, 0x7F));
            $num = gmp_div_q($num, 128);
            $buffer .= \chr($low7 | 0x80);
        }

        $buffer .= \chr(gmp_intval($num));

        return $buffer;
    }

    public function decodeVarint(string $value): string
    {
        $offset = 0;
        $num    = gmp_init(0, 10);

        for ($i = 0; $i < \strlen($value); ++$i) {
            $byte = \ord($value[$i]);
            $low7 = $byte & 0x7F;

            $num = gmp_add($num, gmp_mul($low7, gmp_pow(128, $offset++)));

            if (($byte & 0x80) === 0) {
                /** @var numeric-string */
                return gmp_strval($num);
            }
        }

        throw new MalformedVarintNumber($value);
    }

    public function encodeZigZag(string $value): string
    {
        $num = gmp_init($value, 10);

        /** @var numeric-string */
        return gmp_strval(match (gmp_cmp($num, 0) < 0) {
            true => gmp_sub(gmp_mul($num, -2), 1),
            default => gmp_mul($num, 2),
        });
    }

    public function decodeZigZag(string $value): string
    {
        $num = gmp_init($value, 10);

        /** @var numeric-string */
        return gmp_strval(match (gmp_intval(gmp_and($num, 1)) === 1) {
            true => gmp_mul(gmp_add(gmp_div_q($num, 2), 1), -1),
            default => gmp_div_q($num, 2),
        });
    }
}
