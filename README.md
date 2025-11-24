# Varint and ZigZag encoding implementation

[Variable-width integers](https://protobuf.dev/programming-guides/encoding/#varints) are at the core of the wire format of protobuf, kafka protocol and many other protocols and codecs.

## Installation

```shell
composer require thesis/varint
```

## Usage

The library uses `\BcMath\Number` instead of `int` in its API to support the serialization of large numbers and avoid overflow issues.
A library built on top of `thesis/varint` may choose to offer `int` in its API if it's certain that no overflow issues will occur or handle overflow errors itself.
This is not the responsibility of *this* library.

Example of **varint** encoding using `BcMath` implementation:
```php
use Thesis\Varint;
use BcMath\Number;

$codec = Varint\BcMath::Codec;

$buffer = $codec->encodeVarint(new Number('125'));
echo $codec->decodeVarint($buffer)->value; // '125'
```

[Zigzag](https://lemire.me/blog/2022/11/25/making-all-your-integers-positive-with-zigzag-encoding/) encoding is used for serializing negative varint numbers.

```php
use Thesis\Varint;
use BcMath\Number;

$codec = Varint\BcMath::Codec;

$buffer = $codec->encodeVarint($codec->encodeZigZag(new Number('-125')));
echo $codec->decodeZigZag($codec->decodeVarint($buffer))->value; // '-125'
```

You can get the size of a varint in bytes before encoding it:
```php
use Thesis\Varint;
use BcMath\Number;

$codec = Varint\BcMath::Codec;

echo $codec->size(new Number('128')); // 2
```

Likewise, you can decode a varint to get both the number and its size in bytes:
```php
use Thesis\Varint;
use BcMath\Number;

$codec = Varint\BcMath::Codec;

$sized = $codec->decodeVarintSized($codec->encodeVarint(new Number('128')));
echo $sized->value; // '128'
echo $sized->size; // 2
```

This is useful to use in protocols because you don't know in advance how many bytes to consume from the buffer to read a varint.
Therefore, you read it first and then consume that many bytes from the buffer based on the size obtained from `$sized->size`.

## Why not use `brick/math`?

`brick/math` is a fairly good library, and an early version of this library used it specifically. However, while developing the protobuf tooling, it became clear that working with `numeric-string` was inconvenient, so a type that was simple and stable enough to be part of the API was needed.
Here, the `BigInteger` from `brick/math` faces a competitor in the form of the `Number` type from `bcmath` for PHP 8.4.

Since `bcmath` is a [bundled extension](https://www.php.net/manual/en/extensions.membership.php#extensions.membership.bundled), users won't need to install it explicitly, making it a more stable dependency compared to `brick/math`,
which still hasn't reached a major version. Although `brick/math` allows using the `gmp` extension instead of `bcmath` as a faster alternative, this speed is lost behind `brick/math`'s abstractions, as a simple benchmark of varint/zigzag encoding demonstrated.

Therefore, we decided to settle on `bcmath` and explicitly use its `Number` type.
