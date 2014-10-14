<?php

namespace PhpWriter;

use PhpWriter\Io\Writer;

class PhpWriter
{
    const INDENT = '    ';

    const TYPE_CLASS = 'class';
    const TYPE_INTERFACE = 'interface';
    const TYPE_TRAIT = 'trait';

    const VISIBILITY_PRIVATE = 'private';
    const VISIBILITY_PROTECTED = 'protected';
    const VISIBILITY_PUBLIC = 'public';

    protected $writer;
    protected $indentLevel;

    public function __construct(Writer $writer)
    {
        $this->writer = $writer;
        $this->indentLevel = 0;
    }

    public function emitNamespace($namespace)
    {
        $this->writer->write('namespace ' . $namespace . ';' . PHP_EOL . PHP_EOL);
        return $this;
    }

    public function emitImport($class, $as = null)
    {
        $this->writer->write('use ' . $class . ($as !== null ? ' as ' . $as : '') . ';' . PHP_EOL);
        return $this;
    }

    public function emitAbstract()
    {
        $this->writer->write('abstract ');
        return $this;
    }

    public function emitFinal()
    {
        $this->writer->write('final ');
        return $this;
    }

    public function beginType($name, $type, $extends = null, $implements = null)
    {
        if ($type === self::TYPE_TRAIT && ($extends !== null || $implements !== null)) {
            throw new \InvalidArgumentException('Trait cannot extend or implement.');
        }

        $this->writer->write($type . ' ' . $name);

        if ($extends !== null) {
            $this->writer->write(' extends ' . $extends);
        }

        if ($implements !== null) {
            $classes = is_array($implements) ? implode(', ', $implements) : $implements;
            $this->writer->write(' implements ' . $classes);
        }

        $this->writer->write(PHP_EOL . '{' . PHP_EOL);
        $this->indentLevel++;
        return $this;
    }

    public function endType()
    {
        $this->writer->write('}' . PHP_EOL);
        $this->indentLevel--;
        return $this;
    }

    public function emitConstant($name, $value)
    {
        $this->writer->write($this->indent('const ' . $name . ' = ' . $value . ';' . PHP_EOL));
        return $this;
    }

    public function emitProperty($name, $visibility, $static = false, $init = null)
    {
        $statement = $visibility . ($static ? ' static ' : ' ') . '$' . $name;
        if ($init !== null) {
            $statement .= ' = ' . var_export($init, true);
        }

        $this->writer->write($this->indent($statement . ';' . PHP_EOL));
        return $this;
    }

    public function emitUseTraits($traits)
    {
        $classes = is_array($traits) ? implode(', ', $traits) :  $traits;
        $this->writer->write($this->indent('use ' . $classes . ';' . PHP_EOL));
        return $this;
    }

    public function beginMethod(
        $name,
        $visibility,
        $args = array(),
        $defaultArgs = array(),
        $static = false
    ) {
        $argList = '';

        if (!empty($args)) {
            foreach ($args as $arg) {
                $argList .= '$' . $arg . ', ';
            }
        }

        if (!empty($defaultArgs)) {
            foreach ($defaultArgs as $arg => $default) {
                $argList .= '$' . $arg . ' = ' . var_export($default, true) . ', ';
            }
        }

        $argList = $argList ? substr($argList, 0, -2) : '';

        $signature = $visibility .  ($static ? ' static ' : ' ') . 'function ' . $name;
        $signature .= '(' . $argList . ')';

        $this->writer->write($this->indent($signature));
        $this->writer->write(PHP_EOL . $this->indent('{') . PHP_EOL);
        $this->indentLevel++;
        return $this;
    }

    public function endMethod()
    {
        $this->indentLevel--;
        $this->writer->write($this->indent('}' . PHP_EOL));
        return $this;
    }

    public function emitStatement($statement)
    {
        $this->writer->write($this->indent($statement . ';' . PHP_EOL));
        return $this;
    }

    protected function indent($statement)
    {
        return str_repeat(self::INDENT, $this->indentLevel) . $statement;
    }
}
