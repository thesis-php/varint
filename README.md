# Varint and ZigZag encoding implementation

Variable-width integers are at the core of the wire format of protobuf, kafka protocol and many other protocols and codecs.

## Installation

```shell
composer require thesis/varint
```

## Usage

The library uses `numeric-string` instead of `int` in its API to support the serialization of large numbers and avoid overflow issues.
A library built on top of `thesis/varint` may choose to offer `int` in its API if it's certain that no overflow issues will occur or handle overflow errors itself.
This is not the responsibility of *this* library.

You can explicitly choose the **varint** implementation. The library supports serialization based on `bcmath` and `gmp`.

Example using `bcmath`:
```php
use Thesis\Varint;

$codec = Varint\BcMath::Codec;

$buffer = $codec->encodeVarint('125');
echo $codec->decodeVarint($buffer); // '125'
```

Example using `gmp`:
```php
use Thesis\Varint;

$codec = Varint\Gmp::Codec;

$buffer = $codec->encodeVarint('125');
echo $codec->decodeVarint($buffer); // '125'
```

Example of automatic **varint** driver selection based on loaded extensions:

```php
use Thesis\Varint;

$codec = Varint\selectVarintCodec();

$buffer = $codec->encodeVarint('125');
echo $codec->decodeVarint($buffer);
```

[Zigzag](https://lemire.me/blog/2022/11/25/making-all-your-integers-positive-with-zigzag-encoding/) encoding is used for serializing negative varint numbers.

```php
use Thesis\Varint;

$varint = Varint\selectVarintCodec(); // gmp or bcmath
$zigzag = Varint\selectZigZagCodec(); // gmp or bcmath

$buffer = $varint->encodeVarint($zigzag->encodeZigZag('-125'));
echo $zigzag->decodeZigZag($varint->decodeVarint($buffer)); // '-125'
```
