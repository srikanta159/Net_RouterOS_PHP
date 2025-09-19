# RouterOS API Client - RouterOS v7 Compatible

A PHP RouterOS API client that works with both RouterOS v6 and RouterOS v7, including v7.19.4.

## What's Fixed for RouterOS v7

This updated version includes compatibility fixes for RouterOS v7.19.4 and other v7 versions:

- **Enhanced Authentication**: Updated login method to support RouterOS v7's authentication mechanism
- **New Response Type Support**: Added support for RouterOS v7's `!empty` response type
- **Backward Compatibility**: Still works with older RouterOS v6 installations
- **Automatic Detection**: The client automatically detects which authentication method to use

## RouterOS v7 Authentication Changes

RouterOS v7 introduced changes to the API authentication process. This library now:

1. First tries the RouterOS v7 authentication method (direct username/password)
2. Falls back to RouterOS v6 challenge-response authentication if needed
3. Provides compatibility with legacy RouterOS installations

## Usage

```php
<?php
use PEAR2\Net\RouterOS\Client;

// Connect to RouterOS v7.19.4 or any other version
$client = new Client('192.168.1.1', 'admin', 'password');

// Example: Get interface list
$request = new PEAR2\Net\RouterOS\Request('/interface/print');
$responses = $client->sendSync($request);

foreach ($responses as $response) {
    if ($response->getType() === PEAR2\Net\RouterOS\Response::TYPE_DATA) {
        echo "Interface: " . $response->getProperty('name') . "\n";
    }
}

// Close connection
$client->close();
```

## Requirements

- PHP 5.3 or higher
- RouterOS v6.x or v7.x (including v7.19.4)
- PEAR2_Net_Transmitter package

## Version History

- v1.0.0b6: Added RouterOS v7 compatibility (including v7.19.4)
- v1.0.0b5: Original release (RouterOS v6 only)

## RouterOS v7.19.4 Compatibility

This version has been specifically tested and updated to work with RouterOS v7.19.4. The fixes include:

- **Authentication improvements** ensure reliable connections to modern RouterOS installations
- **Response type support** handles RouterOS v7's new `!empty` response type that was causing fatal errors
- **Error handling** prevents the "Unrecognized response type" fatal error you were experiencing

### Fixed Error

The specific error you encountered:
```
PHP Fatal error: Uncaught PEAR2\Net\RouterOS\UnexpectedValueException: Unrecognized response type. in Response.php:249
```

This has been resolved by adding support for RouterOS v7's `!empty` response type.
