<?php

namespace PhpWriter\Io;

class StringWriterTest extends \PHPUnit_Framework_TestCase
{
    public function testWriter()
    {
        $writer = new StringWriter();
        $writer->write('some text');

        $this->assertEquals('some text', $writer->getString());
    }
}
