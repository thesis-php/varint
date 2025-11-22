<?php

declare(strict_types=1);

namespace Thesis\Varint;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

#[CoversClass(BcMath::class)]
#[CoversClass(Gmp::class)]
#[CoversClass(Brick::class)]
final class CodecTest extends TestCase
{
    /**
     * @param numeric-string $value
     */
    #[TestWith(['1'])]
    #[TestWith(['128'])]
    #[TestWith(['16384'])]
    #[TestWith(['2097152'])]
    #[TestWith(['268435456'])]
    #[TestWith(['18446744073709551615'])]
    #[TestWith(['340282366920938463463374607431768211455'])]
    public function testVarintRoundTrip(string $value): void
    {
        foreach ([BcMath::Codec, Gmp::Codec, Brick::Codec] as $codec) {
            self::assertSame($value, $codec->decodeVarint($codec->encodeVarint($value)));
        }
    }

    /**
     * @param numeric-string $value
     */
    #[TestWith(['1'])]
    #[TestWith(['128'])]
    #[TestWith(['16384'])]
    #[TestWith(['2097152'])]
    #[TestWith(['268435456'])]
    #[TestWith(['18446744073709551615'])]
    #[TestWith(['340282366920938463463374607431768211455'])]
    #[TestWith(['-1'])]
    #[TestWith(['-128'])]
    #[TestWith(['-256'])]
    #[TestWith(['-512'])]
    #[TestWith(['-1024'])]
    #[TestWith(['-2048'])]
    #[TestWith(['-32768'])]
    #[TestWith(['-654321'])]
    #[TestWith(['-987654321'])]
    #[TestWith(['-9223372036854775808'])]
    #[TestWith(['-18446744073709551616'])]
    #[TestWith(['-340282366920938463463374607431768211456'])]
    #[TestWith(['-170141183460469231731687303715884105728'])]
    public function testZigZagRoundTrip(string $value): void
    {
        foreach ([BcMath::Codec, Gmp::Codec, Brick::Codec] as $codec) {
            self::assertSame($value, $codec->decodeZigZag($codec->decodeVarint($codec->encodeVarint($codec->encodeZigZag($value)))));
        }
    }
}
