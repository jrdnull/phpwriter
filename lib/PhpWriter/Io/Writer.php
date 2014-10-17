<?php

namespace PhpWriter\Io;

abstract class Writer
{
    abstract public function write($str);

    abstract public function flush();

    abstract public function close();
}
