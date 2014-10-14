<?php

namespace PhpWriter\Io;

abstract class Writer
{
    abstract public function write($str);

    public function flush()
    {
    }

    public function close()
    {
    }
}
