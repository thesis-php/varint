# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.2.0] 2025-11-25

### Changed

* Use only `bcmath` and type `Number`.

## [0.1.1] 2025-11-24

### Added

* Ability to get varint size using `VarintCodec::size` before encoding it.
* Ability to get varint numeric value and size using `VarintCodec::decodeVarintSize`.

## [0.1.0] 2025-11-23

### Added

* Varint and zigzag implementation based on `brick/math`.

