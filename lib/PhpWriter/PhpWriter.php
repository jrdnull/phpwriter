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

    /**
     * @param Writer $writer
     */
    public function __construct(Writer $writer)
    {
        $this->writer = $writer;
        $this->indentLevel = 0;
    }

    /**
     * Emits namespace
     *
     * @param string $namespace
     * @return $this
     */
    public function emitNamespace($namespace)
    {
        $this->writer->write('namespace ' . $namespace . ';' . PHP_EOL . PHP_EOL);
        return $this;
    }

    /**
     * Emits import with optional alias
     *
     * @param string $class
     * @param string null $as
     * @return $this
     */
    public function emitImport($class, $as = null)
    {
        $this->writer->write('use ' . $class . ($as !== null ? ' as ' . $as : '') . ';' . PHP_EOL);
        return $this;
    }

    /**
     * Emits abstract for use before beginType or beginMethod
     *
     * @return $this
     */
    public function emitAbstract()
    {
        $this->writer->write('abstract ');
        return $this;
    }

    /**
     * Emits final for use before beginType or beginMethod
     *
     * @return $this
     */
    public function emitFinal()
    {
        $this->writer->write('final ');
        return $this;
    }

    /**
     * Emits beginning of type
     *
     * @param string $name
     * @param string $type
     * @param string $extends
     * @param string[] $implements
     * @return $this
     */
    public function beginType($name, $type, $extends = null, $implements = [])
    {
        if ($type === self::TYPE_TRAIT && ($extends !== null || !empty($implements))) {
            throw new \InvalidArgumentException('Trait cannot extend or implement.');
        }

        $this->writer->write($type . ' ' . $name);

        if ($extends !== null) {
            $this->writer->write(' extends ' . $extends);
        }

        if (!empty($implements)) {
            $this->writer->write(' implements ' . implode(', ', $implements));
        }

        $this->writer->write(PHP_EOL . '{' . PHP_EOL);
        $this->indentLevel++;
        return $this;
    }

    /**
     * Emits end of type
     *
     * @return $this
     */
    public function endType()
    {
        $this->writer->write('}' . PHP_EOL);
        $this->indentLevel--;
        return $this;
    }

    /**
     * Emits constant
     *
     * @param string $name
     * @param mixed $value
     *
     * @return $this
     */
    public function emitConstant($name, $value)
    {
        $this->writer->write($this->indent('const ' . $name . ' = ' . $value . ';' . PHP_EOL));
        return $this;
    }

    /**
     * Emits property
     *
     * @param string $name
     * @param string $visibility
     * @param bool $static
     * @param mixed
     * @return $this
     */
    public function emitProperty($name, $visibility, $static = false, $init = null)
    {
        $statement = $visibility . ($static ? ' static ' : ' ') . '$' . $name;
        if ($init !== null) {
            $statement .= ' = ' . var_export($init, true);
        }

        $this->writer->write($this->indent($statement . ';' . PHP_EOL));
        return $this;
    }

    /**
     * Emits use trait
     *
     * @param string $trait
     * @return $this
     */
    public function emitUseTrait($trait)
    {
        return $this->emitUseTraits([$trait]);
    }

    /**
     * Emits use traits
     *
     * @param string[] $traits
     * @return $this
     */
    public function emitUseTraits($traits)
    {
        $this->writer->write($this->indent('use ' . implode(', ', $traits) . ';' . PHP_EOL));
        return $this;
    }

    /**
     * Emits beginning of method
     *
     * @param string $name
     * @param string $visibility
     * @param string[] $args
     * @param mixed[] $defaultArgs [name => default]
     * @param bool $static
     * @return $this
     */
    public function beginMethod($name, $visibility, $args = [], $defaultArgs = [], $static = false) {
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

    /**
     * Emits end of method
     *
     * @return $this
     */
    public function endMethod()
    {
        $this->indentLevel--;
        $this->writer->write($this->indent('}' . PHP_EOL));
        return $this;
    }

    /**
     * Emits statement
     *
     * @param string $statement without trailing semicolon
     * @return $this
     */
    public function emitStatement($statement)
    {
        $this->writer->write($this->indent($statement . ';' . PHP_EOL));
        return $this;
    }

    /**
     * Indents the string to the current level
     *
     * @param string $statement
     * @return string
     */
    protected function indent($statement)
    {
        return str_repeat(self::INDENT, $this->indentLevel) . $statement;
    }
}
