# Net_RouterOS_PHP

[![CI Status](https://github.com/srikanta159/Net_RouterOS_PHP/actions/workflows/ci.yml/badge.svg)](https://github.com/srikanta159/Net_RouterOS_PHP/actions)
[![Latest Stable Version](https://img.shields.io/badge/version-1.0.0-blue.svg)](https://github.com/srikanta159/Net_RouterOS_PHP)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D7.4%20%7C%208.x-8892BF.svg)](https://www.php.net/)
[![License: LGPL-2.1](https://img.shields.io/badge/License-LGPL_2.1-green.svg)](https://opensource.org/licenses/LGPL-2.1)

A modernized PHP API client for **MikroTik RouterOS**, supporting both **RouterOS v7** (including v7.19.4+) and **RouterOS v6**. Fully compatible with modern PHP (**PHP 7.4 through PHP 8.4+**).

---

## Why This Package Exists

The original `pear2/net_routeros` library was abandoned and broke when MikroTik introduced **RouterOS v7**:

1. **Authentication Overhaul**: RouterOS v6.43+ and v7 introduced direct credential authentication, deprecating the legacy MD5 CHAP challenge-response handshake (`=ret=`). Legacy clients fail with `Invalid username or password`.
2. **New Response Type (`!empty`)**: RouterOS v7 returns `!empty` instead of empty `!re` or `!done` in many queries. Legacy clients crash with:
   ```
   PHP Fatal error: Uncaught PEAR2\Net\RouterOS\UnexpectedValueException: Unrecognized response type in Response.php
   ```
3. **Modern PHP Deprecations**: PHP 8.1, 8.2, 8.3, and 8.4 introduced deprecations for SPL interfaces without return types (`#[\ReturnTypeWillChange]`) and dynamic property access.

**Net_RouterOS_PHP** solves all of these problems while maintaining full backward compatibility with legacy RouterOS v6 devices and existing codebases.

---

## Features

- **RouterOS v7 & v6 Dual Support**: Automatically detects and handles RouterOS v7 plaintext authentication, with seamless fallback to RouterOS v6 MD5 challenge-response.
- **RouterOS v7 `!empty` Protocol Handling**: Native parsing of `!empty` response sentences across responses and collections.
- **PHP 8.x Ready**: Zero deprecation notices on PHP 8.1, 8.2, 8.3, and 8.4.
- **PSR-4 Autoloading**: Standard Composer layout (`src/`), with standalone `autoload.php` fallback for non-Composer setups.
- **Full Encryption Support**: Connect securely via TLS/SSL on API port `8729`.
- **Query Builder**: Intuitive, chainable syntax for filtering RouterOS commands (`Query::where(...)`).
- **Synchronous & Asynchronous**: Support for synchronous blocking requests, asynchronous non-blocking queries, and callbacks.
- **Automated Test Suite**: Tested across PHP 7.4 to 8.4 on Linux and Windows via GitHub Actions CI.

---

## Requirements

- **PHP**: `^7.4 || ^8.0 || ^8.1 || ^8.2 || ^8.3 || ^8.4`
- **Extensions**: `ext-iconv` (required), `ext-openssl` (for TLS on port 8729), `ext-sockets`
- **Dependency**: `pear2/net_transmitter` (`^1.0@alpha`)

---

## Installation

### Option 1: Composer (Recommended)

Add the package to your project:

```bash
composer require srikanta159/net_routeros_php
```

> **Note:** If your root `composer.json` has `minimum-stability: stable`, ensure you add `"minimum-stability": "alpha"` and `"prefer-stable": true` to allow `pear2/net_transmitter`.

### Option 2: Git Clone / Manual Download

Clone the repository into your project:

```bash
git clone https://github.com/srikanta159/Net_RouterOS_PHP.git
```

Include the standalone autoloader:

```php
require_once 'Net_RouterOS_PHP/autoload.php';
```

---

## Quick Start

### 1. Basic Synchronous Request

```php
<?php

require_once 'vendor/autoload.php'; // or require_once 'autoload.php';

use PEAR2\Net\RouterOS\Client;
use PEAR2\Net\RouterOS\Request;
use PEAR2\Net\RouterOS\Response;

try {
    // Connect to RouterOS (v6 or v7)
    $client = new Client('192.168.88.1', 'admin', 'your_password');

    // Send command
    $request = new Request('/system/resource/print');
    $responses = $client->sendSync($request);

    foreach ($responses as $response) {
        if ($response->getType() === Response::TYPE_DATA) {
            echo "Version:    " . $response->getProperty('version') . "\n";
            echo "CPU Load:   " . $response->getProperty('cpu-load') . "%\n";
            echo "Free Memory: " . round($response->getProperty('free-memory') / 1024 / 1024, 2) . " MB\n";
            echo "Uptime:     " . $response->getProperty('uptime') . "\n";
        }
    }

    $client->close();
} catch (\Throwable $e) {
    echo "Connection error: " . $e->getMessage() . "\n";
}
```

---

### 2. Encrypted Connection (API-SSL on Port 8729)

```php
<?php

use PEAR2\Net\RouterOS\Client;
use PEAR2\Net\RouterOS\Request;
use PEAR2\Net\Transmitter\NetworkStream;

// Connect over TLS on port 8729
$client = new Client(
    '192.168.88.1',
    'admin',
    'your_password',
    8729,
    false,
    null,
    NetworkStream::CRYPTO_TLS
);

$request = new Request('/interface/print');
$responses = $client->sendSync($request);

foreach ($responses->getAllOfType(PEAR2\Net\RouterOS\Response::TYPE_DATA) as $iface) {
    echo "Interface: " . $iface->getProperty('name') . " (" . $iface->getProperty('type') . ")\n";
}

$client->close();
```

---

### 3. Filtering with Query Builder

```php
<?php

use PEAR2\Net\RouterOS\Client;
use PEAR2\Net\RouterOS\Request;
use PEAR2\Net\RouterOS\Query;
use PEAR2\Net\RouterOS\Response;

$client = new Client('192.168.88.1', 'admin', 'password');

// Query: Find ethernet interfaces that are running
$query = Query::where('type', 'ether', Query::OP_EQ)
    ->andWhere('running', 'true', Query::OP_EQ);

$request = new Request('/interface/print');
$request->setQuery($query);

$responses = $client->sendSync($request);

foreach ($responses->getAllOfType(Response::TYPE_DATA) as $response) {
    echo "Running Interface: " . $response->getProperty('name') . "\n";
}

$client->close();
```

---

### 4. Handling RouterOS v7 `!empty` Responses

```php
<?php

use PEAR2\Net\RouterOS\Client;
use PEAR2\Net\RouterOS\Request;
use PEAR2\Net\RouterOS\Response;

$client = new Client('192.168.88.1', 'admin', 'password');

// If a query matches zero rows in RouterOS v7, a !empty response is returned
$request = new Request('/ip/firewall/nat/print');
$request->setQuery(PEAR2\Net\RouterOS\Query::where('comment', 'nonexistent_rule', PEAR2\Net\RouterOS\Query::OP_EQ));

$responses = $client->sendSync($request);

if ($responses->getAllOfType(Response::TYPE_EMPTY)->count() > 0) {
    echo "No matching NAT rules found (RouterOS v7 !empty received).\n";
}

$client->close();
```

---

## Running Tests

Run the built-in test runner without any external test framework:

```bash
php tests/run_tests.php
```

Or using Composer:

```bash
composer test
```

---

## Publishing to Packagist

To make this package installable via `composer require srikanta159/net_routeros_php`:

1. Commit and push your changes to GitHub:
   ```bash
   git add .
   git commit -m "Modernize package: PSR-4, PHP 8.x compatibility, test suite, and CI"
   git push origin main
   ```
2. Create a GitHub Release or Git Tag:
   ```bash
   git tag -a v1.0.0 -m "Release v1.0.0: Full RouterOS v7 and PHP 8.x support"
   git push origin v1.0.0
   ```
3. Go to [Packagist.org](https://packagist.org/), log in with GitHub, click **Submit**, and paste your repository URL:
   `https://github.com/srikanta159/Net_RouterOS_PHP`
4. Enable the **GitHub Service Hook** (under GitHub repo Settings > Webhooks) so new releases are updated automatically on Packagist.

---

## Contributing

Contributions are welcome! Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct and development workflow.

---

## License

This package is free and open-source software licensed under the **GNU Lesser General Public License v2.1 or later** ([LGPL-2.1-or-later](LICENSE)), with permission granted under LGPL Section 3 to apply the terms of the GNU General Public License.

- Original Author: **Vasil Rangelov** ([boen.robot@gmail.com](mailto:boen.robot@gmail.com))
- RouterOS v7 & Modern PHP Maintainer: **Srikanta Das** ([@srikanta159](https://github.com/srikanta159))
