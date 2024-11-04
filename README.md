# Duplicate files detector

**Deprecated**: Will not longer be maintained as [jdupes](https://codeberg.org/jbruchon/jdupes) is a lot faster.

Detects duplicate files in a whole directory (and its subdirectories) based on their md5 hash.

## Installation

```
composer install
```

## Usage

```
./console detect [options] [--] <scanDir>
```

Arguments:

- **scanDir**: The directory to scan

Options:

- **-f, --follow**: Follows symlinks, **false** by default.

## Unit tests

```
./phpunit
```
