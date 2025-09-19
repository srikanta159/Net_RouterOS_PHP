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

## Installation

### Option 1: Git Clone
```bash
git clone https://github.com/srikanta159/Net_RouterOS_PHP.git
```

### Option 2: Download ZIP
Download the repository as ZIP and extract to your project directory.

## Usage

### Basic Setup
```php
<?php
// Include the main client file
require_once 'Net_RouterOS_PHP/Client.php';

// Use the namespace
use PEAR2\Net\RouterOS\Client;
use PEAR2\Net\RouterOS\Request;
use PEAR2\Net\RouterOS\Response;

// Connect to RouterOS v7.19.4 or any other version
$client = new Client('192.168.1.1', 'admin', 'password');

// Example: Get interface list
$request = new Request('/interface/print');
$responses = $client->sendSync($request);

foreach ($responses as $response) {
    if ($response->getType() === Response::TYPE_DATA) {
        echo "Interface: " . $response->getProperty('name') . "\n";
    }
}

// Close connection
$client->close();
?>
```

### Advanced Example
```php
<?php
require_once 'Net_RouterOS_PHP/Client.php';

use PEAR2\Net\RouterOS\Client;
use PEAR2\Net\RouterOS\Request;
use PEAR2\Net\RouterOS\Response;

try {
    // Connect with error handling
    $client = new Client('192.168.1.1', 'admin', 'password');
    
    // Get system information
    $request = new Request('/system/resource/print');
    $responses = $client->sendSync($request);
    
    foreach ($responses as $response) {
        if ($response->getType() === Response::TYPE_DATA) {
            echo "CPU Load: " . $response->getProperty('cpu-load') . "%\n";
            echo "Free Memory: " . $response->getProperty('free-memory') . " bytes\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} finally {
    if (isset($client)) {
        $client->close();
    }
}
?>
```

## Quick Start

1. **Install PEAR2_Net_Transmitter:**
   ```bash
   composer require pear2/net_transmitter
   ```

2. **Clone the repository:**
   ```bash
   git clone https://github.com/srikanta159/Net_RouterOS_PHP.git
   ```

3. **Include in your PHP project:**
   ```php
   require_once 'vendor/autoload.php'; // PEAR2_Net_Transmitter
   require_once 'Net_RouterOS_PHP/Client.php';
   use PEAR2\Net\RouterOS\Client;
   ```

4. **Connect to RouterOS:**
   ```php
   $client = new Client('192.168.1.1', 'admin', 'password');
   ```

## Requirements

- PHP 5.3 or higher
- RouterOS v6.x or v7.x (including v7.19.4)
- PEAR2_Net_Transmitter package

## PEAR2_Net_Transmitter Package Installation

The RouterOS API client depends on the PEAR2_Net_Transmitter package. Here are several ways to install it:

### Option 1: Composer (Recommended)
```bash
composer require pear2/net_transmitter
```

### Option 2: PEAR2 Installation
```bash
# Install PEAR2 if not already installed
pear install pear/pear2_autoload

# Install the transmitter package
pear install pear2/Net_Transmitter
```

### Option 3: Manual Installation
1. Download from [PEAR2_Net_Transmitter GitHub](https://github.com/pear2/Net_Transmitter)
2. Extract to your project directory
3. Include the autoloader:
   ```php
   require_once 'Net_Transmitter/autoload.php';
   ```

### Option 4: Include in Your Project
If you prefer to include it directly in your project:
```php
// Include the transmitter package before the RouterOS client
require_once 'path/to/Net_Transmitter/autoload.php';
require_once 'Net_RouterOS_PHP/Client.php';
```

### Troubleshooting PEAR2_Net_Transmitter

**Error: "Class 'PEAR2\Net\Transmitter\TcpClient' not found"**
- This means the transmitter package is not properly installed
- Try one of the installation methods above

**Error: "PEAR2_Net_Transmitter not found"**
- Make sure the package is in your PHP include path
- Check that the autoloader is included before the RouterOS client

### Complete Working Example
```php
<?php
// Include PEAR2_Net_Transmitter first
require_once 'vendor/autoload.php'; // If using Composer
// OR
require_once 'Net_Transmitter/autoload.php'; // If manual installation

// Then include the RouterOS client
require_once 'Net_RouterOS_PHP/Client.php';

use PEAR2\Net\RouterOS\Client;

// Now you can use the RouterOS client
$client = new Client('192.168.1.1', 'admin', 'password');
?>
```

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
