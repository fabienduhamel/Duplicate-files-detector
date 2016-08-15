# Duplicate files detector

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
