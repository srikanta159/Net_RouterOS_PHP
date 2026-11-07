<?php

namespace PEAR2\Net\RouterOS\Test;

require_once __DIR__ . '/TestCase.php';

use PEAR2\Net\RouterOS\Registry;

class RegistryTest extends TestCase
{
    public function testParseTagWithOwnershipPrefix()
    {
        $parsed = Registry::parseTag('0_1__user_tag');
        $this->assertEquals('0_1__', $parsed[0]);
        $this->assertEquals('user_tag', $parsed[1]);
    }

    public function testParseTagWithoutOwnershipPrefix()
    {
        $parsed = Registry::parseTag('plain_tag');
        $this->assertNull($parsed[0]);
        $this->assertEquals('plain_tag', $parsed[1]);
    }

    public function testParseTagNull()
    {
        $parsed = Registry::parseTag(null);
        $this->assertNull($parsed[0]);
        $this->assertNull($parsed[1]);
    }

    public function testParseTagEmptyOwnership()
    {
        $parsed = Registry::parseTag('0_1__');
        $this->assertEquals('0_1__', $parsed[0]);
        $this->assertNull($parsed[1]);
    }
}

