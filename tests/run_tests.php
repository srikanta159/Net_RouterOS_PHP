<?php

/**
 * Net_RouterOS_PHP Test Runner.
 * Runs all unit tests and outputs status.
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/../autoload.php';
require_once __DIR__ . '/TestCase.php';

$testFiles = glob(__DIR__ . '/*Test.php');

$totalTests = 0;
$passedTests = 0;
$failedTests = 0;
$errors = array();

echo "Running Net_RouterOS_PHP Test Suite\n";
echo "====================================\n\n";

foreach ($testFiles as $file) {
    require_once $file;
    $className = 'PEAR2\\Net\\RouterOS\\Test\\' . basename($file, '.php');

    if (!class_exists($className)) {
        continue;
    }

    $reflection = new ReflectionClass($className);
    $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

    foreach ($methods as $method) {
        if (strpos($method->getName(), 'test') === 0) {
            $totalTests++;
            $testName = basename($file, '.php') . '::' . $method->getName();

            $instance = new $className();
            $expectedException = null;

            try {
                $method->invoke($instance);

                if (method_exists($instance, 'getExpectedException') && ($expected = $instance->getExpectedException())) {
                    throw new Exception("Expected exception '{$expected}' was not thrown.");
                }

                echo "  [PASS] {$testName}\n";
                $passedTests++;
            } catch (Throwable $e) {
                if (method_exists($instance, 'getExpectedException') && ($expected = $instance->getExpectedException()) && $e instanceof $expected) {
                    echo "  [PASS] {$testName} (caught expected {$expected})\n";
                    $passedTests++;
                } else {
                    echo "  [FAIL] {$testName}: " . $e->getMessage() . "\n";
                    $failedTests++;
                    $errors[] = array(
                        'test' => $testName,
                        'message' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    );
                }
            }
        }
    }
}

echo "\n------------------------------------\n";
echo "Total Tests:  {$totalTests}\n";
echo "Passed:       {$passedTests}\n";
echo "Failed:       {$failedTests}\n";
echo "------------------------------------\n";

if ($failedTests > 0) {
    echo "\nFailures Detail:\n";
    foreach ($errors as $err) {
        echo "\n* " . $err['test'] . ":\n  " . $err['message'] . "\n";
    }
    exit(1);
}

echo "\nALL TESTS PASSED SUCCESSFULLY!\n";
exit(0);

