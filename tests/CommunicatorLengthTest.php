<?php

namespace PEAR2\Net\RouterOS\Test;

require_once __DIR__ . '/TestCase.php';

use PEAR2\Net\RouterOS\Communicator;
use PEAR2\Net\RouterOS\LengthException;

class CommunicatorLengthTest extends TestCase
{
    public function testEncodeLength1Byte()
    {
        $encoded0 = Communicator::encodeLength(0);
        $this->assertEquals(1, strlen($encoded0));
        $this->assertEquals(chr(0), $encoded0);

        $encoded127 = Communicator::encodeLength(127);
        $this->assertEquals(1, strlen($encoded127));
        $this->assertEquals(chr(127), $encoded127);
    }

    public function testEncodeLength2Bytes()
    {
        $encoded128 = Communicator::encodeLength(128);
        $this->assertEquals(2, strlen($encoded128));

        $encoded1000 = Communicator::encodeLength(1000);
        $this->assertEquals(2, strlen($encoded1000));
    }

    public function testEncodeLengthNegativeThrows()
    {
        $this->expectException(LengthException::class);
        Communicator::encodeLength(-1);
    }

    public function testIsSeekableStream()
    {
        $this->assertFalse(Communicator::isSeekableStream('not a stream'));
        $this->assertFalse(Communicator::isSeekableStream(null));
        $this->assertFalse(Communicator::isSeekableStream(12345));

        $fp = fopen('php://temp', 'r+');
        $this->assertTrue(Communicator::isSeekableStream($fp));
        fclose($fp);
    }
}

