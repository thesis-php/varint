<?php

declare(strict_types=1);

namespace Thesis\Varint;

/**
 * @api
 */
enum BcMath implements
    VarintCodec,
    ZigZagCodec
{
    case Codec;

    public function encodeVarint(string $value): string
    {
        $buffer = '';

        while (bccomp($value, '127') > 0) {
            $low7 = bcmod($value, '128');
            $value = bcdiv($value, '128');
            $buffer .= \chr((int) $low7 + 0x80);
        }

        $buffer .= \chr((int) bcmod($value, '128'));

        return $buffer;
    }

    public function decodeVarint(string $value): string
    {
        $offset = 0;
        $num    = '0';

        for ($i = 0; $i < \strlen($value); ++$i) {
            $byte = \ord($value[$i]);
            $low7 = $byte & 0x7F;

            $num = bcadd($num, bcmul((string) $low7, bcpow('128', (string) $offset++)));

            if (($byte & 0x80) === 0) {
                return $num;
            }
        }

        throw new MalformedVarintNumber($value);
    }

    public function encodeZigZag(string $value): string
    {
        return match (bccomp($value, '0') < 0) {
            true => bcsub(bcmul($value, '-2'), '1'),
            default => bcmul($value, '2'),
        };
    }

    public function decodeZigZag(string $value): string
    {
        $num = bcdiv($value, '2');

        return match (bcmod($value, '2')) {
            '1' => bcmul(bcadd($num, '1'), '-1'),
            default => $num,
        };
    }
}
