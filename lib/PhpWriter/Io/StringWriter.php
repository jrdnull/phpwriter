<?php

namespace PhpWriter\Io;

class StringWriter extends Writer
{
    protected $data = '';

    /**
     * Appends input to the writers string
     *
     * @param string $str
     */
    public function write($str)
    {
        $this->data .= $str;
    }

    /**
     * Does nothing
     */
    public function flush()
    {
    }

    /**
     * Does nothing
     */
    public function close()
    {
    }

    /**
     * Returns the built up string
     *
     * @return string
     */
    public function getString()
    {
        return $this->data;
    }
}
