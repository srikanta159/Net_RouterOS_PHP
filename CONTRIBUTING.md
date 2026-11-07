# Contributing to Net_RouterOS_PHP

Thank you for considering contributing to `Net_RouterOS_PHP`! Open source contributions of all kinds—bug reports, pull requests, documentation improvements, and feature requests—are welcome and appreciated.

## Development Workflow

### 1. Fork and Clone
```bash
git clone https://github.com/<your-username>/Net_RouterOS_PHP.git
cd Net_RouterOS_PHP
```

### 2. Install Dependencies
If developing with Composer:
```bash
composer install
```

### 3. Run the Test Suite
You can run the tests using either the built-in runner:
```bash
php tests/run_tests.php
```
or via Composer:
```bash
composer test
```
or via PHPUnit (if installed):
```bash
vendor/bin/phpunit
```

### 4. Code Standards
- Adhere to PSR-12 coding style guidelines.
- Maintain compatibility with PHP 7.4 through PHP 8.4+.
- Include tests for any bug fixes or new features.
- Avoid introducing deprecation notices under PHP 8.x (e.g., provide `#[\ReturnTypeWillChange]` where necessary for SPL interfaces).

### 5. Pull Requests
- Keep PRs focused on a single concern or fix.
- Ensure all tests pass locally before opening the PR.
- Provide a clear explanation of what problem the PR solves.

