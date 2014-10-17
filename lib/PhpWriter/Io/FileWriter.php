<?php

namespace PhpWriter\Io;

class FileWriter extends Writer
{
    /**
     * @var resource
     */
    protected $fh;

    /**
     * @param string $filename
     * @param bool $append
     *
     * @throws \InvalidArgumentException when given an unwritable file
     * @throws \RuntimeException when opening file fails
     */
    public function __construct($filename, $append = true)
    {
        if (!is_writable($filename)) {
            throw new \InvalidArgumentException('File ' . $filename . ' is not writable.');
        }

        if (($this->fh = fopen($filename, $append ? 'a' : 'w')) === false) {
            throw new \RuntimeException('Failed to open file ' . $filename);
        }
    }

    /**
     * Writes the given string to the output
     *
     * @param string $str
     * @throws \RuntimeException
     */
    public function write($str)
    {
        if (fwrite($this->fh, $str) === false) {
            throw new \RuntimeException('Writing to file failed.');
        }
    }

    /**
     * Flushes the output to the file
     *
     * @throws \RuntimeException
     */
    public function flush()
    {
        if (!fflush($this->fh)) {
            throw new \RuntimeException('Flushing file failed.');
        }
    }

    /**
     * Flushes the output and closes the file
     *
     * @throws \RuntimeException
     */
    public function close()
    {
        $this->flush();
        if (!fclose($this->fh)) {
            throw new \RuntimeException('Closing file failed.');
        }
    }
}
