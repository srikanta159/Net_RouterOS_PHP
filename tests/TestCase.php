<?php

namespace PEAR2\Net\RouterOS\Test;

if (class_exists('PHPUnit\\Framework\\TestCase')) {
    abstract class BaseTestCase extends \PHPUnit\Framework\TestCase
    {
    }
} else {
    abstract class BaseTestCase
    {
        protected $expectedException = null;

        public function expectException($exceptionClass)
        {
            $this->expectedException = $exceptionClass;
        }

        public function getExpectedException()
        {
            return $this->expectedException;
        }

        public function resetExpectedException()
        {
            $this->expectedException = null;
        }

        public function assertTrue($condition, $message = '')
        {
            if ($condition !== true) {
                throw new \AssertionError($message ?: 'Failed asserting that condition is true.');
            }
        }

        public function assertFalse($condition, $message = '')
        {
            if ($condition !== false) {
                throw new \AssertionError($message ?: 'Failed asserting that condition is false.');
            }
        }

        public function assertEquals($expected, $actual, $message = '')
        {
            if ($expected != $actual) {
                throw new \AssertionError($message ?: sprintf('Failed asserting that %s matches expected %s.', var_export($actual, true), var_export($expected, true)));
            }
        }

        public function assertSame($expected, $actual, $message = '')
        {
            if ($expected !== $actual) {
                throw new \AssertionError($message ?: sprintf('Failed asserting that %s is identical to expected %s.', var_export($actual, true), var_export($expected, true)));
            }
        }

        public function assertNull($actual, $message = '')
        {
            if ($actual !== null) {
                throw new \AssertionError($message ?: sprintf('Failed asserting that %s is null.', var_export($actual, true)));
            }
        }

        public function assertNotNull($actual, $message = '')
        {
            if ($actual === null) {
                throw new \AssertionError($message ?: 'Failed asserting that value is not null.');
            }
        }

        public function assertCount($expectedCount, $countable, $message = '')
        {
            $actualCount = count($countable);
            if ($actualCount !== $expectedCount) {
                throw new \AssertionError($message ?: sprintf('Failed asserting that count %d matches expected %d.', $actualCount, $expectedCount));
            }
        }

        public function assertInstanceOf($expectedClass, $object, $message = '')
        {
            if (!($object instanceof $expectedClass)) {
                throw new \AssertionError($message ?: sprintf('Failed asserting that %s is an instance of %s.', get_class($object), $expectedClass));
            }
        }
    }
}

abstract class TestCase extends BaseTestCase
{
}

