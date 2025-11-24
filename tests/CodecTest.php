<?php

declare(strict_types=1);

namespace Thesis\Varint;

use BcMath\Number;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(BcMath::class)]
final class CodecTest extends TestCase
{
    #[DataProvider('positiveIntegers')]
    public function testVarintRoundTrip(Number $num): void
    {
        $codec = BcMath::Codec;

        self::assertEquals($num, $codec->decodeVarint($codec->encodeVarint($num)));
    }

    #[DataProvider('positiveIntegers')]
    #[DataProvider('negativeIntegers')]
    public function testZigZagRoundTrip(Number $num): void
    {
        $codec = BcMath::Codec;

        self::assertEquals($num, $codec->decodeZigZag($codec->decodeVarint($codec->encodeVarint($codec->encodeZigZag($num)))));
    }

    /**
     * @return iterable<array{Number}>
     */
    public static function positiveIntegers(): iterable
    {
        yield [new Number('1')];
        yield [new Number('128')];
        yield [new Number('16384')];
        yield [new Number('2097152')];
        yield [new Number('268435456')];
        yield [new Number('18446744073709551615')];
        yield [new Number('340282366920938463463374607431768211455')];
    }

    /**
     * @return iterable<array{Number}>
     */
    public static function negativeIntegers(): iterable
    {
        yield [new Number('-1')];
        yield [new Number('-128')];
        yield [new Number('-256')];
        yield [new Number('-512')];
        yield [new Number('-1024')];
        yield [new Number('-2048')];
        yield [new Number('-32768')];
        yield [new Number('-654321')];
        yield [new Number('-987654321')];
        yield [new Number('-9223372036854775808')];
        yield [new Number('-18446744073709551616')];
        yield [new Number('-340282366920938463463374607431768211456')];
        yield [new Number('-170141183460469231731687303715884105728')];
    }

    /**
     * @param positive-int $size
     */
    #[DataProvider('nonNegativeSizedIntegers')]
    public function testVarintSized(Number $num, int $size): void
    {
        $codec = BcMath::Codec;

        $sized = $codec->decodeVarintSized($codec->encodeVarint($num));
        self::assertSame($size, $codec->size($num));
        self::assertEquals($num, $sized->value);
        self::assertSame($size, $sized->size);
    }

    /**
     * @return iterable<array{Number, positive-int}>
     */
    public static function nonNegativeSizedIntegers(): iterable
    {
        yield [new Number('0'), 1];
        yield [new Number('1'), 1];
        yield [new Number('128'), 2];
        yield [new Number('16384'), 3];
        yield [new Number('2097152'), 4];
        yield [new Number('268435456'), 5];
        yield [new Number('18446744073709551615'), 10];
        yield [new Number('340282366920938463463374607431768211455'), 19];
    }
}
