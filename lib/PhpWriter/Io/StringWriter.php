<?php

namespace PhpWriter\Io;

class StringWriter extends Writer
{
    protected $data = '';

    public function write($str)
    {
        $this->data .= $str;
    }

    public function toString()
    {
        return $this->data;
    }
}
