# Tigress Manipulator — Programmer's Manual

**Package:** `tigress/manipulator` · **Namespace:** `Tigress`  
**Requires:** PHP >= 8.5, ext-gd, ext-mbstring, ext-fileinfo, chillerlan/php-qrcode ^5.0

---

## Table of Contents

1. [Overview](#overview)
2. [Installation](#installation)
3. [Facade: Manipulator](#facade-manipulator)
4. [TextManipulator](#textmanipulator)
5. [DateManipulator](#datemanipulator)
6. [ImageManipulator](#imagemanipulator)
7. [BBCode](#bbcode)
8. [CalculateBirthday](#calculatebirthday)
9. [CalculatePregnancy](#calculatepregnancy)
10. [NationalIdentification](#nationalidentification)
11. [QrCodeGenerator](#qrcodegenerator)
12. [Code Review — Bugs & Issues](#code-review--bugs--issues)
13. [Code Review — Quality Notes](#code-review--quality-notes)
14. [Contributing](#contributing)

---

## Overview

`tigress/manipulator` is a PHP utility library providing standalone manipulation classes for text, dates, images, BBCode, national identification numbers, QR codes, birthday calculations, and pregnancy date calculations. Each class lives under the `Tigress` namespace and is autoloaded via PSR-4 from `src/`.

All classes expose a static `version(): string` method for introspection. The top-level `Manipulator` class aggregates all versions in a single call.

---

## Installation

```bash
composer require tigress/manipulator
```

All classes are autoloaded under `Tigress\`:

```php
use Tigress\TextManipulator;
use Tigress\ImageManipulator;
// etc.
```

---

## Facade: Manipulator

**File:** `src/Manipulator.php`

The `Manipulator` class is a static facade that returns the version of every sub-class in one call.

```php
$versions = Manipulator::version();
// Returns:
// [
//     'Manipulator' => '2026.02.26',
//     'BBCode'       => '2024.11.28',
//     ...
// ]
```

---

## TextManipulator

**File:** `src/TextManipulator.php`

### Methods

| Method | Signature | Description |
|--------|-----------|-------------|
| `randomText` | `(int $numberOfCharacters): string` | Generate a random alphanumeric + symbol string of the given length. |
| `cleanHTML` | `(string $input): string` | Encode HTML entities (ENT_HTML5, UTF-8). |
| `uncleanHTML` | `(string $input): string` | Decode HTML entities back to raw characters. |
| `cleanURL` | `(string $input): string` | URL-encode a string, replacing `/` with `__` first. |
| `cleanURLUTF8` | `(string $input): string` | Same as `cleanURL` but re-encodes from ISO-8859-1 to UTF-8 first. |
| `uncleanURL` | `(string $input): string` | Inverse of `cleanURL` — replaces `__` with `/` and URL-decodes. |
| `uncleanURLUTF8` | `(string $input): string` | Inverse of `cleanURLUTF8`. |
| `rtrimStringNumber` | `(string $input): string` | Removes trailing zeros and decimal point from a numeric string (e.g. `"3.50"` → `"3.5"`, `"3.00"` → `"3"`). |

### Examples

```php
$tm = new TextManipulator();

echo $tm->randomText(12);          // e.g., "aB3$xK9#mQ2"
echo $tm->cleanHTML('<b>bold</b>'); // "&lt;b&gt;bold&lt;/b&gt;"
echo $tm->cleanURL('hello/world');  // "hello__world" (URL-encoded)
echo $tm->rtrimStringNumber('5.00'); // "5"
```

---

## DateManipulator

**File:** `src/DateManipulator.php`

### Methods

| Method | Signature | Description |
|--------|-----------|-------------|
| `convertDateToTimestamp` | `(string $date): int` | Parse a date string via `strtotime()` and return a Unix timestamp. |
| `convertTimestampToDate` | `(int $timestamp): string` | Format a timestamp as `Y-m-d`. |
| `convertTimestampToDateTime` | `(int $timestamp): string` | Format a timestamp as `Y-m-d H:i:s`. |

### Examples

```php
$dm = new DateManipulator();
$ts = $dm->convertDateToTimestamp('2026-05-24');
echo $dm->convertTimestampToDate($ts);       // "2026-05-24"
echo $dm->convertTimestampToDateTime($ts);   // "2026-05-24 00:00:00"
```

---

## ImageManipulator

**File:** `src/ImageManipulator.php`

Requires the **GD** extension.

### Methods

| Method | Signature | Description |
|--------|-----------|-------------|
| `getBase64Image` | `(string $imagePath): string` | Read an image file and return a `data:` URI with base64 payload. |
| `getImageFromBase64` | `(string $base64String): string` | Decode a `data:` URI back to a temporary file on disk. **Caller must `unlink()` the temp file.** |
| `resizeImage` | `(string $imagePath, int $newWidth = 0, int $newHeight = 0): string` | Resize JPEG/PNG/GIF and return a `data:` URI. If one dimension is 0, it is auto-computed to preserve aspect ratio. |

### Examples

```php
$im = new ImageManipulator();

// Image → data URI
$dataUri = $im->getBase64Image('photo.jpg');

// Resize to width 400, auto height
$thumbUri = $im->resizeImage('photo.jpg', 400);

// Decode data URI back to file
$path = $im->getImageFromBase64($dataUri);
// … use $path …
unlink($path); // cleanup
```

### Supported MIME types
- `image/jpeg`, `image/png`, `image/gif`

---

## BBCode

**File:** `src/BBCode.php`

Converts BBCode markup to HTML.

### Supported tags

| BBCode | HTML |
|--------|------|
| `[b]…[/b]` | `<b>…</b>` |
| `[i]…[/i]` | `<i>…</i>` |
| `[u]…[/u]` | `<u>…</u>` |
| `[strike]…[/strike]` | `<del>…</del>` |
| `[quote]…[/quote]` | `<blockquote>quote:<hr>…<hr></blockquote>` |
| `[quote=author]…[/quote]` | `<blockquote cite="author">quote:<hr>…<hr></blockquote>` |
| `[code]…[/code]` | `<blockquote>code:<hr><code>…</code><hr></blockquote>` |
| `[url]http://…[/url]` | `<a href="…" target="_blank">…</a>` |
| `[url=http://…]text[/url]` | `<a href="…" target="_blank">text</a>` |
| `[url]…[/url]` (no protocol) | Prefixed with `http://` |
| `[email]…[/email]` | `<a href="mailto:…">…</a>` |
| `[*]item` | `<li>item</li>` |
| `[list]…[/list]` | `<ul>…</ul>` |
| `[list=1]…[/list]` | `<ol type="1">…</ol>` |
| `[img]url[/img]` | `<img src="url">` |
| `[img=WxH]url[/img]` | `<img width="W" height="H" src="url">` |
| `[img=wW]url[/img]` | `<img width="W" src="url">` |
| `[img=hH]url[/img]` | `<img height="H" src="url">` |

Also strips `<br>` variants, converts `\r\n` / `\n` to `<br>`, and converts tabs to `&nbsp;&nbsp;&nbsp;&nbsp;`.

### Example

```php
$bb = new BBCode();
echo $bb->processText('[b]bold[/b] and [i]italic[/i]');
// <b>bold</b> and <i>italic</i>
```

---

## CalculateBirthday

**File:** `src/CalculateBirthday.php`

Calculates elapsed time from a given date/time to now.

### Methods

| Method | Signature | Description |
|--------|-----------|-------------|
| `setDate` | `(string $date): void` | Set the birth date (`Y-m-d`). |
| `getDate` | `(): string` | Get the current birth date. |
| `setTime` | `(string $time): void` | Set the birth time (`H:i:s`). |
| `getTime` | `(): string` | Get the current birth time. |
| `calculate` | `(string $type): float\|false\|array\|int` | Calculate the period. Type: `years`, `months`, `weeks`, `days`, `hours`, `minutes`, `seconds`, or `full` (array). |

### Example

```php
$cb = new CalculateBirthday();
$cb->setDate('1990-04-15');
$cb->setTime('14:30:00');

echo $cb->calculate('years');  // 36
echo $cb->calculate('days');   // e.g., 13184
print_r($cb->calculate('full')); // ['years' => 36, 'months' => 1, ...]
```

### Exceptions
- `InvalidArgumentException` for unknown `$type`.
- `DateMalformedStringException` for invalid date/time strings.

---

## CalculatePregnancy

**File:** `src/CalculatePregnancy.php`

Calculates how many weeks or days pregnant someone is, based on an expected delivery date and a reference (current) date. Uses a 281-day (40-week) pregnancy duration internally.

### Methods

| Method | Signature | Description |
|--------|-----------|-------------|
| `calculateWeeks` | `(string $deliveryDate, string $referenceDate): int` | Weeks pregnant. |
| `calculateDays` | `(string $deliveryDate, string $referenceDate): int` | Days pregnant. |
| `calculateWeeksByTimestamp` | `(int $timestampDeliveryDate, int $timestampReferenceDate): int` | Same as `calculateWeeks` but accepts timestamps directly. |
| `calculateDaysByTimestamp` | `(int $timestampDeliveryDate, int $timestampReferenceDate): int` | Same as `calculateDays` but accepts timestamps directly. |

### Example

```php
$cp = new CalculatePregnancy();
echo $cp->calculateWeeks('2026-09-01', '2026-05-24'); // e.g., 24
echo $cp->calculateDays('2026-09-01', '2026-05-24');  // e.g., 173
```

---

## NationalIdentification

**File:** `src/NationalIdentification.php`

Formats national identification numbers and extracts birth dates. Currently supports Belgium and the Netherlands.

### Methods

| Method | Signature | Description |
|--------|-----------|-------------|
| `formatNumber` | `(string $country, mixed $unformattedNumber): string` | Format the number. Returns `false` on invalid length. |
| `getBirthdate` | `(string $country, mixed $unformattedNumber): string` | Extract the birth date (`YYYY-MM-DD`) from the number. Returns `false` on invalid length. |

### Supported countries

#### Belgium (`BE`)
- Input: 11 digits (e.g. `"00012412301"`)
- Formatted: `00.01.24-123.01`
- Birthdate: `1924-01-24` (prefix `19` if year ≥ 20, `20` if year < 20)

#### Netherlands (`NL`)
- Input: 9 digits (e.g. `"123456789"`)
- Formatted: `1234.56.789`

### Example

```php
$ni = new NationalIdentification();

echo $ni->formatNumber('BE', '00012412301');
// "00.01.24-123.01"

echo $ni->getBirthdate('BE', '00012412301');
// "1924-01-24"

echo $ni->formatNumber('NL', '123456789');
// "1234.56.789"
```

---

## QrCodeGenerator

**File:** `src/QrCodeGenerator.php`

Generates QR codes using the [`chillerlan/php-qrcode`](https://github.com/chillerlan/php-qrcode) library.

### Factory method

```php
$qr = QrCodeGenerator::create(/* optional options array */);
```

### Options (defaults)

| Option | Default | Description |
|--------|---------|-------------|
| `version` | `Version::AUTO` | QR version |
| `versionMin` | `7` | Minimum version |
| `eccLevel` | `EccLevel::L` | Error correction level (L, M, Q, H) |
| `outputType` | `QROutputInterface::GDIMAGE_PNG` | Output type |
| `scale` | `10` | Pixel scale |
| `invertMatrix` | `false` | Invert colors |
| `drawLightModules` | `true` | Draw light modules |
| `drawCircularModules` | `false` | Circular module style |
| `circleRadius` | `0.4` | Circle radius ratio |
| `addLogoSpace` | `false` | Reserve space for logo |
| `logoSpaceWidth` | `15` | Logo space width |
| `imageTransparent` | `false` | Transparent background |
| `quality` | `90` | Image quality |
| `gdImageUseUpscale` | `false` | Upscale with GD |

### Methods

| Method | Signature | Description |
|--------|-----------|-------------|
| `render` | `(string $data, ?string $filename = null): mixed` | Render QR and optionally save to file. Returns raw PNG by default. |
| `base64` | `(string $data): string` | Render QR to a base64 `data:image/png` URI. |
| `renderWithLogo` | `(string $data, string $logoPath, ?string $saveTo = null): string` | Render QR with a centered logo (resized to 20% of QR width). Returns base64 URI; optionally saves to file. |

### Examples

```php
// Simple base64 data URI
$pngUri = QrCodeGenerator::create()->base64('https://example.com');

// Save to file
QrCodeGenerator::create()->render('https://example.com', 'qr.png');

// With logo
$logoUri = QrCodeGenerator::create()->renderWithLogo(
    'https://example.com',
    'logo.png',
    'qr-with-logo.png'
);
```

---

## Code Review — Bugs & Issues

### 1. `QrCodeGenerator::$qr` type hint mismatch (CRITICAL)

**File:** `src/QrCodeGenerator.php:25`

```php
public QrCode $qr;  // Class "QrCode" does not exist
```

The import is `use chillerlan\QRCode\QRCode;` but the property type is `QrCode`. PHP class names are case-sensitive, so this will cause a fatal error at runtime. **Fix:** Change to:

```php
public QRCode $qr;
```

---

### 2. `BBCode` — malformed HTML tag (LOW)

**File:** `src/BBCode.php:68`

```php
'<blockquote">code:<hr><code>$1</code><hr></blockquote>',
```

There is a stray `"` before the `>` in the opening tag. Should be:

```php
'<blockquote>code:<hr><code>$1</code><hr></blockquote>',
```

---

### 3. `ImageManipulator::getImageFromBase64` — extension may not be applied (MEDIUM)

**File:** `src/ImageManipulator.php:56`

```php
$tempFilePath = tempnam(sys_get_temp_dir(), 'img_' . uniqid() . '.' . $this->getExtensionFromMimeType($mimeType));
```

PHP's `tempnam($dir, $prefix)` uses the entire second argument as a *prefix* for a randomly generated filename. The `.jpg` / `.png` suffix is treated as part of the prefix, not as a file extension. The resulting file will likely have no extension or a different one. **Fix:** Rename the file after creation:

```php
$tempFilePath = tempnam(sys_get_temp_dir(), 'img_');
$ext = $this->getExtensionFromMimeType($mimeType);
$finalPath = $tempFilePath . '.' . $ext;
rename($tempFilePath, $finalPath);
return $finalPath;
```

---

### 4. `TextManipulator::randomText` uses insecure `rand()` (LOW)

**File:** `src/TextManipulator.php:35`

```php
$randomString .= $characters[rand(0, strlen($characters) - 1)];
```

`rand()` is not cryptographically secure and has poor distribution. Use `random_int()` instead:

```php
$randomString .= $characters[random_int(0, strlen($characters) - 1)];
```

---

### 5. `CalculateBirthday` — property `$interval->days` may be `false` (LOW)

**File:** `src/CalculateBirthday.php:45`

`DateTime::diff()` returns a `DateInterval`. The `days` property is documented as `int|false`. While `false` is rare in PHP 8.x, `calculate('weeks')` uses `$interval->days` directly without a guard. On older or edge-case systems, this could produce a type error.

---

## Code Review — Quality Notes

| Area | Note |
|------|------|
| **No tests** | The package has no test suite (no `phpunit.xml`, no `tests/` directory). Tests should be added for all public methods. |
| **Mixed parameter types** | `NationalIdentification::formatNumber()` and `getBirthdate()` declare `mixed $unformattedNumber` but only strings are meaningful. Tightening to `string` would improve type safety. |
| **No return type on `CalculatePregnancy` properties** | `$DM`, `$pregnancyDuration`, `$periodWeek`, `$periodDay` are untyped. Should be typed (e.g., `private DateManipulator $DM`). |
| **BBCode regex list vs parameterised patterns** | The BBCode implementation is a fixed list of regex patterns. A more extensible approach would compose tag definitions and generate patterns dynamically. |
| **`cleanURL` convention** | Replacing `/` with `__` before encoding is a non-standard convention. Users should be aware that `uncleanURL()` assumes this encoding. |
| **`getImageFromBase64` returns temp file path** | The caller is responsible for deletion. This is documented but could be wrapped in a disposable-style pattern or a `finally`-safe helper. |
| **PSR-4 autoloading matches `Tigress\` → `src/`** | Correct and well-configured in `composer.json`. |

---

## Contributing

1. Fixes for the [bugs listed above](#code-review--bugs--issues) are the highest priority.
2. Add PHPUnit tests covering all public methods.
3. Run static analysis (e.g., PHPStan level 6+) and fix all type issues.
4. When adding a new class, also add its version to `Manipulator::version()`.
5. Follow the existing code style (no comments in source, `php >= 8.5` features like `match`, `enum`-like patterns, union types, constructor promotion where applicable).
