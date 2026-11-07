<?php

namespace PEAR2\Net\RouterOS\Test;

require_once __DIR__ . '/TestCase.php';

use PEAR2\Net\RouterOS\Response;
use PEAR2\Net\RouterOS\UnexpectedValueException;

class TestableResponse extends Response
{
    public function __construct($type = null)
    {
        if ($type !== null) {
            $this->setType($type);
        }
    }

    public function setTestType($type)
    {
        return $this->setType($type);
    }

    public function setTestAttribute($name, $value)
    {
        $this->setAttribute($name, $value);
        return $this;
    }

    public function setTestTag($tag)
    {
        $this->setTag($tag);
        return $this;
    }
}

class ResponseTest extends TestCase
{
    public function testRouterOSv7TypeEmptySupport()
    {
        $this->assertEquals('!empty', Response::TYPE_EMPTY);

        $response = new TestableResponse();
        $response->setTestType(Response::TYPE_EMPTY);
        $this->assertEquals(Response::TYPE_EMPTY, $response->getType());
    }

    public function testStandardResponseTypes()
    {
        $response = new TestableResponse();

        $response->setTestType(Response::TYPE_FINAL);
        $this->assertEquals('!done', $response->getType());

        $response->setTestType(Response::TYPE_DATA);
        $this->assertEquals('!re', $response->getType());

        $response->setTestType(Response::TYPE_ERROR);
        $this->assertEquals('!trap', $response->getType());

        $response->setTestType(Response::TYPE_FATAL);
        $this->assertEquals('!fatal', $response->getType());
    }

    public function testUnrecognizedResponseTypeThrowsException()
    {
        $this->expectException(UnexpectedValueException::class);
        $response = new TestableResponse();
        $response->setTestType('!unknown_type');
    }

    public function testAttributesAndProperties()
    {
        $response = new TestableResponse(Response::TYPE_DATA);
        $response->setTestAttribute('name', 'ether1');
        $response->setTestAttribute('disabled', 'false');
        $response->setTestTag('req1');

        $this->assertEquals('ether1', $response->getProperty('name'));
        $this->assertEquals('false', $response->getProperty('disabled'));
        $this->assertEquals('req1', $response->getTag());
    }
}

