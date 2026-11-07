<?php

namespace PEAR2\Net\RouterOS\Test;

require_once __DIR__ . '/TestCase.php';

use PEAR2\Net\RouterOS\Util;
use DateInterval;

class UtilTest extends TestCase
{
    public function testParseValueBooleans()
    {
        $this->assertTrue(Util::parseValue('true'));
        $this->assertTrue(Util::parseValue('yes'));
        $this->assertFalse(Util::parseValue('false'));
        $this->assertFalse(Util::parseValue('no'));
    }

    public function testParseValueNull()
    {
        $this->assertNull(Util::parseValue(''));
        $this->assertNull(Util::parseValue('nil'));
    }

    public function testParseValueNumbers()
    {
        $this->assertSame(42, Util::parseValue('42'));
        $this->assertSame(3.14, Util::parseValue('3.14'));
    }

    public function testParseValueTime()
    {
        $val = Util::parseValue('1d02:03:04');
        $this->assertInstanceOf(DateInterval::class, $val);
        $this->assertEquals(1, $val->d);
        $this->assertEquals(2, $val->h);
        $this->assertEquals(3, $val->i);
        $this->assertEquals(4, $val->s);
    }

    public function testEscapeString()
    {
        $escaped = Util::escapeString('hello "world"');
        // MikroTik string escaping uses \HEX escapes (e.g., \20 for space, \22 for quote)
        $this->assertEquals('hello\\20\\22world\\22', $escaped);
    }
}
