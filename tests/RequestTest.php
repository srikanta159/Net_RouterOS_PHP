<?php

namespace PEAR2\Net\RouterOS\Test;

require_once __DIR__ . '/TestCase.php';

use PEAR2\Net\RouterOS\Request;
use PEAR2\Net\RouterOS\Query;
use PEAR2\Net\RouterOS\InvalidArgumentException;

class RequestTest extends TestCase
{
    public function testBasicRequestCreation()
    {
        $request = new Request('/interface/print');
        $this->assertEquals('/interface/print', $request->getCommand());
    }

    public function testSettingAndGettingArguments()
    {
        $request = new Request('/ip/address/add');
        $request->setArgument('address', '192.168.88.1/24');
        $request->setArgument('interface', 'ether1');

        $this->assertEquals('192.168.88.1/24', $request->getArgument('address'));
        $this->assertEquals('ether1', $request->getArgument('interface'));
        $this->assertNull($request->getArgument('nonexistent'));
    }

    public function testSettingTag()
    {
        $request = new Request('/system/resource/print');
        $request->setTag('custom_tag');
        $this->assertEquals('custom_tag', $request->getTag());
    }

    public function testAttachingQuery()
    {
        $request = new Request('/interface/print');
        $query = Query::where('running', 'true');
        $request->setQuery($query);

        $this->assertSame($query, $request->getQuery());
    }

    public function testInvalidCommandThrowsException()
    {
        $this->expectException(InvalidArgumentException::class);
        new Request('invalid_without_slash');
    }
}

