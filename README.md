# Varint and ZigZag encoding implementation

[Variable-width integers](https://protobuf.dev/programming-guides/encoding/#varints) are at the core of the wire format of protobuf, kafka protocol and many other protocols and codecs.

## Installation

```shell
composer require thesis/varint
```

## Usage

The library uses `numeric-string` instead of `int` in its API to support the serialization of large numbers and avoid overflow issues.
A library built on top of `thesis/varint` may choose to offer `int` in its API if it's certain that no overflow issues will occur or handle overflow errors itself.
This is not the responsibility of *this* library.

The library uses `brick/math` for **varint** and **zigzag** encoding.

Example of **varint** encoding using `Brick` implementation:
```php
use Thesis\Varint;

$codec = Varint\Brick::Codec;

$buffer = $codec->encodeVarint('125');
echo $codec->decodeVarint($buffer); // '125'
```

[Zigzag](https://lemire.me/blog/2022/11/25/making-all-your-integers-positive-with-zigzag-encoding/) encoding is used for serializing negative varint numbers.

```php
use Thesis\Varint;

$codec = Varint\Brick::Codec;

$buffer = $codec->encodeVarint($codec->encodeZigZag('-125'));
echo $codec->decodeZigZag($codec->decodeVarint($buffer)); // '-125'
```
