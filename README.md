
PHPWriter
=========
[![Build Status](https://travis-ci.org/jrdnull/phpwriter.svg?branch=master)](https://travis-ci.org/jrdnull/phpwriter)
[![Code Climate](https://codeclimate.com/github/jrdnull/phpwriter/badges/gpa.svg)](https://codeclimate.com/github/jrdnull/phpwriter)
[![Test Coverage](https://codeclimate.com/github/jrdnull/phpwriter/badges/coverage.svg)](https://codeclimate.com/github/jrdnull/phpwriter)

Inspired by [JavaWriter](https://github.com/square/javawriter), PHPWriter generates PHP source code.

Example
-------

```php
$phpWriter->openTag()
          ->beginType('Person', PhpWriter::TYPE_CLASS)
              ->emitDocblock([
                  '@Id',
                  '@Column(type="integer")',
                  '@GeneratedValue',
                  '@var int'
              ])
              ->emitProperty('id', PhpWriter::VISIBILITY_PROTECTED)
              ->emitNewline()
              ->emitDocblock(['@Column(type="string")', '@var string'])
              ->emitProperty('name', PhpWriter::VISIBILITY_PROTECTED)
              ->emitNewline()
              ->beginMethod('getId', PhpWriter::VISIBILITY_PUBLIC)
                  ->emitStatement('return $this->id')
              ->endMethod()
              ->emitNewline()
              ->beginMethod('getName', PhpWriter::VISIBILITY_PUBLIC)
                  ->emitStatement('return $this->name')
              ->endMethod()
              ->emitNewline()
              ->beginMethod('setName', PhpWriter::VISIBILITY_PUBLIC, ['name'])
                  ->emitStatement('$this->name = $name')
              ->endMethod()
          ->endType();
```

Produces:

```php
<?php
class Person
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     * @var int
     */
    protected $id;

    /**
     * @Column(type="string")
     * @var string
     */
    protected $name;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}
```

License
-------

    The MIT License (MIT)
    
    Copyright (c) 2014 Jordon Smith
    
    Permission is hereby granted, free of charge, to any person obtaining a copy
    of this software and associated documentation files (the "Software"), to deal
    in the Software without restriction, including without limitation the rights
    to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
    copies of the Software, and to permit persons to whom the Software is
    furnished to do so, subject to the following conditions:
    
    The above copyright notice and this permission notice shall be included in all
    copies or substantial portions of the Software.
    
    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
    OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
    SOFTWARE.
