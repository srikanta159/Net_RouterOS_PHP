<?php

namespace PEAR2\Net\RouterOS\Test;

require_once __DIR__ . '/TestCase.php';
require_once __DIR__ . '/ResponseTest.php';

use PEAR2\Net\RouterOS\Response;
use PEAR2\Net\RouterOS\ResponseCollection;

class ResponseCollectionTest extends TestCase
{
    public function testEmptyCollection()
    {
        $collection = new ResponseCollection();
        $this->assertCount(0, $collection);
    }

    public function testFilteringByType()
    {
        $r1 = (new TestableResponse(Response::TYPE_DATA))->setTestAttribute('name', 'ether1');
        $r2 = (new TestableResponse(Response::TYPE_DATA))->setTestAttribute('name', 'ether2');
        $r3 = new TestableResponse(Response::TYPE_EMPTY);
        $r4 = new TestableResponse(Response::TYPE_FINAL);

        $collection = new ResponseCollection(array($r1, $r2, $r3, $r4));
        $this->assertCount(4, $collection);

        $dataResponses = $collection->getAllOfType(Response::TYPE_DATA);
        $this->assertCount(2, $dataResponses);
        $this->assertEquals('ether1', $dataResponses[0]->getProperty('name'));
        $this->assertEquals('ether2', $dataResponses[1]->getProperty('name'));

        $emptyResponses = $collection->getAllOfType(Response::TYPE_EMPTY);
        $this->assertCount(1, $emptyResponses);
        $this->assertEquals(Response::TYPE_EMPTY, $emptyResponses[0]->getType());

        $doneResponses = $collection->getAllOfType(Response::TYPE_FINAL);
        $this->assertCount(1, $doneResponses);
    }

    public function testFilteringByTag()
    {
        $r1 = (new TestableResponse(Response::TYPE_DATA))->setTestTag('tag_a');
        $r2 = (new TestableResponse(Response::TYPE_DATA))->setTestTag('tag_b');
        $r3 = (new TestableResponse(Response::TYPE_FINAL))->setTestTag('tag_a');

        $collection = new ResponseCollection(array($r1, $r2, $r3));

        $tagAResponses = $collection->getAllTagged('tag_a');
        $this->assertCount(2, $tagAResponses);

        $tagBResponses = $collection->getAllTagged('tag_b');
        $this->assertCount(1, $tagBResponses);
    }

    public function testArrayAccessAndIteration()
    {
        $r1 = (new TestableResponse(Response::TYPE_DATA))->setTestAttribute('id', '1');
        $r2 = (new TestableResponse(Response::TYPE_DATA))->setTestAttribute('id', '2');

        $collection = new ResponseCollection(array($r1, $r2));

        $this->assertTrue(isset($collection[0]));
        $this->assertTrue(isset($collection[1]));
        $this->assertFalse(isset($collection[2]));

        $this->assertEquals('1', $collection[0]->getProperty('id'));
        $this->assertEquals('2', $collection[1]->getProperty('id'));

        $count = 0;
        foreach ($collection as $index => $resp) {
            $count++;
            $this->assertInstanceOf(Response::class, $resp);
        }
        $this->assertEquals(2, $count);
    }
}

