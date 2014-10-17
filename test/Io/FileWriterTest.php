<?php

namespace PhpWriter\Io;

class FileWriterTest extends \PHPUnit_Framework_TestCase
{
    public function testWriter()
    {
        $filename = tempnam(sys_get_temp_dir(), 'phpwriter');
        $writer = new FileWriter($filename);
        $writer->write('some text');
        $writer->close();

        $this->assertEquals('some text', file_get_contents($filename));

        // clean up temp file
        unlink($filename);
    }
}
