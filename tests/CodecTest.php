<?php

declare(strict_types=1);

namespace Thesis\Varint;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Brick::class)]
final class CodecTest extends TestCase
{
    /**
     * @param numeric-string $value
     */
    #[DataProvider('positiveIntegers')]
    public function testVarintRoundTrip(string $value): void
    {
        $codec = Brick::Codec;

        self::assertSame($value, $codec->decodeVarint($codec->encodeVarint($value)));
    }

    /**
     * @param numeric-string $value
     */
    #[DataProvider('positiveIntegers')]
    #[DataProvider('negativeIntegers')]
    public function testZigZagRoundTrip(string $value): void
    {
        $codec = Brick::Codec;

        self::assertSame($value, $codec->decodeZigZag($codec->decodeVarint($codec->encodeVarint($codec->encodeZigZag($value)))));
    }

    /**
     * @return iterable<array{numeric-string}>
     */
    public static function positiveIntegers(): iterable
    {
        yield ['1'];
        yield ['128'];
        yield ['16384'];
        yield ['2097152'];
        yield ['268435456'];
        yield ['18446744073709551615'];
        yield ['340282366920938463463374607431768211455'];
    }

    /**
     * @return iterable<array{numeric-string}>
     */
    public static function negativeIntegers(): iterable
    {
        yield ['-1'];
        yield ['-128'];
        yield ['-256'];
        yield ['-512'];
        yield ['-1024'];
        yield ['-2048'];
        yield ['-32768'];
        yield ['-654321'];
        yield ['-987654321'];
        yield ['-9223372036854775808'];
        yield ['-18446744073709551616'];
        yield ['-340282366920938463463374607431768211456'];
        yield ['-170141183460469231731687303715884105728'];
    }

    /**
     * @param numeric-string $value
     * @param positive-int $size
     */
    #[DataProvider('nonNegativeSizedIntegers')]
    public function testVarintSized(string $value, int $size): void
    {
        $codec = Brick::Codec;

        $number = $codec->decodeVarintSized($codec->encodeVarint($value));
        self::assertSame($size, $codec->size($value));
        self::assertSame($value, $number->value);
        self::assertSame($size, $number->size);
    }

    /**
     * @return iterable<array{numeric-string, positive-int}>
     */
    public static function nonNegativeSizedIntegers(): iterable
    {
        yield ['0', 1];
        yield ['1', 1];
        yield ['128', 2];
        yield ['16384', 3];
        yield ['2097152', 4];
        yield ['268435456', 5];
        yield ['18446744073709551615', 10];
        yield ['340282366920938463463374607431768211455', 19];
    }
}
