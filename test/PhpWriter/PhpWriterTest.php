<?php

namespace PhpWriter;

use PhpWriter\Io\StringWriter;

class PhpWriterTest extends \PHPUnit_Framework_TestCase
{
    /** @var PhpWriter */
    private $phpWriter;

    /** @var StringWriter */
    private $stringWriter;

    protected function setUp()
    {
        $this->stringWriter = new StringWriter();
        $this->phpWriter = new PhpWriter($this->stringWriter);
    }

    public function testNamespace()
    {
        $this->phpWriter->emitNamespace('Foo\Bar');

        $this->assertCode('namespace Foo\Bar;' . PHP_EOL . PHP_EOL);
    }

    public function testImport()
    {
        $this->phpWriter->emitImport('Foo\Bar');

        $this->assertCode('use Foo\Bar;' . PHP_EOL);
    }

    public function testImportAs()
    {
        $this->phpWriter->emitImport('Foo\Bar', 'Baz');

        $this->assertCode('use Foo\Bar as Baz;' . PHP_EOL);
    }

    public function testAbstract()
    {
        $this->phpWriter->emitAbstract();

        $this->assertCode('abstract ');
    }

    public function testFinal()
    {
        $this->phpWriter->emitFinal();

        $this->assertCode('final ');
    }

    public function testClass()
    {
        $this->phpWriter->beginType('Foo', PhpWriter::TYPE_CLASS)->endType();

        $this->assertCode('class Foo' . PHP_EOL . '{' . PHP_EOL . '}' . PHP_EOL);
    }

    public function testInterface()
    {
        $this->phpWriter->beginType('Foo', PhpWriter::TYPE_INTERFACE)->endType();

        $this->assertCode('interface Foo' . PHP_EOL . '{' . PHP_EOL . '}' . PHP_EOL);
    }

    public function testTrait()
    {
        $this->phpWriter->beginType('Foo', PhpWriter::TYPE_TRAIT)->endType();

        $this->assertCode('trait Foo' . PHP_EOL . '{' . PHP_EOL . '}' . PHP_EOL);
    }

    public function testAbstractClass()
    {
        $this->phpWriter->emitAbstract()
                        ->beginType('Foo', PhpWriter::TYPE_CLASS)
                        ->endType();

        $this->assertCode('abstract class Foo' . PHP_EOL . '{' . PHP_EOL . '}' . PHP_EOL);
    }

    public function testExtends()
    {
        $this->phpWriter->beginType('Foo', PhpWriter::TYPE_CLASS, 'Bar')->endType();

        $this->assertCode('class Foo extends Bar' . PHP_EOL . '{' . PHP_EOL . '}' . PHP_EOL);
    }

    public function testImplements()
    {
        $this->phpWriter->beginType('Foo', PhpWriter::TYPE_CLASS, null, 'Bar')->endType();

        $this->assertCode('class Foo implements Bar' . PHP_EOL . '{' . PHP_EOL . '}' . PHP_EOL);
    }

    public function testImplementsMany()
    {
        $this->phpWriter->beginType('Foo', PhpWriter::TYPE_CLASS, null, array('Bar', 'Baz'))
                        ->endType();

        $this->assertCode('class Foo implements Bar, Baz' . PHP_EOL . '{' . PHP_EOL . '}' . PHP_EOL);
    }

    public function testExtendsAndImplements()
    {
        $this->phpWriter->beginType('Foo', PhpWriter::TYPE_CLASS, 'Bar', array('Baz', 'Qux'))
                        ->endType();

        $this->assertCode(
            'class Foo extends Bar implements Baz, Qux' . PHP_EOL . '{' . PHP_EOL . '}' . PHP_EOL
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testTraitCannotExtend()
    {
        $this->phpWriter->beginType('Foo', PhpWriter::TYPE_TRAIT, 'Bar');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testTraitCannotImplement()
    {
        $this->phpWriter->beginType('Foo', PhpWriter::TYPE_TRAIT, null, 'Bar');
    }

    public function testConstant()
    {
        $this->phpWriter->emitConstant('FOO', 'BAR');

        $this->assertCode('const FOO = BAR;' . PHP_EOL);
    }

    public function testProperty()
    {
        $this->phpWriter->emitProperty('foo', PhpWriter::VISIBILITY_PUBLIC);

        $this->assertCode('public $foo;' . PHP_EOL);
    }

    public function testStaticProperty()
    {
        $this->phpWriter->emitProperty('foo', PhpWriter::VISIBILITY_PROTECTED, true);

        $this->assertCode('protected static $foo;' . PHP_EOL);
    }

    public function testPropertyInitialised()
    {
        $this->phpWriter->emitProperty('foo', PhpWriter::VISIBILITY_PRIVATE, false, 12);

        $this->assertCode('private $foo = 12;' . PHP_EOL);
    }

    public function testUseTrait()
    {
        $this->phpWriter->emitUseTraits('Foo');

        $this->assertCode('use Foo;' . PHP_EOL);
    }

    public function testUseTraits()
    {
        $this->phpWriter->emitUseTraits(array('Foo', 'Bar'));

        $this->assertCode('use Foo, Bar;' . PHP_EOL);
    }

    public function testMethod()
    {
        $this->phpWriter->beginMethod('foo', PhpWriter::VISIBILITY_PUBLIC)->endMethod();

        $this->assertCode('public function foo()' . PHP_EOL . '{' . PHP_EOL . '}' . PHP_EOL);
    }

    public function testMethodWithArgs()
    {
        $this->phpWriter->beginMethod(
            'foo',
            PhpWriter::VISIBILITY_PUBLIC,
            array('bar', 'baz')
        )->endMethod();

        $this->assertCode('public function foo($bar, $baz)' . PHP_EOL . '{' . PHP_EOL . '}' . PHP_EOL);
    }

    public function testMethodWithDefaultArgs()
    {
        $this->phpWriter->beginMethod(
            'foo',
            PhpWriter::VISIBILITY_PUBLIC,
            array('bar'),
            array('baz' => 12, 'qux' => true)
        )->endMethod();

        $this->assertCode(
            'public function foo($bar, $baz = 12, $qux = true)'
            . PHP_EOL .'{' . PHP_EOL . '}' . PHP_EOL
        );
    }

    public function testMethodWithDefaultArgsStatic()
    {
        $this->phpWriter->beginMethod(
            'foo',
            PhpWriter::VISIBILITY_PRIVATE,
            array('bar'),
            array('baz' => 12, 'qux' => true),
            true
        )->endMethod();

        $this->assertCode(
            'private static function foo($bar, $baz = 12, $qux = true)'
            . PHP_EOL .'{' . PHP_EOL . '}' . PHP_EOL
        );
    }

    public function testStatement()
    {
        $this->phpWriter->emitStatement('echo \'foo\'');

        $this->assertCode('echo \'foo\';' . PHP_EOL);
    }

    public function testKitchenSink()
    {
        $this->phpWriter->emitNamespace('FooBar')
                        ->emitImport('Cat', 'Dog')
                        ->beginType('Foo', PhpWriter::TYPE_CLASS, 'Bar', 'Baz')
                        ->emitUseTraits('Bazzer')
                        ->emitConstant('RANDOM_NUMBER', 4)
                        ->emitProperty('foo', PhpWriter::VISIBILITY_PRIVATE, false, 12)
                        ->beginMethod('getFoo', PhpWriter::VISIBILITY_PUBLIC)
                        ->emitStatement('return $this->foo')
                        ->endMethod()
                        ->endType();

        $this->assertCode(
            'namespace FooBar;' . PHP_EOL
            . PHP_EOL
            . 'use Cat as Dog;' . PHP_EOL
            . 'class Foo extends Bar implements Baz' . PHP_EOL
            . '{' . PHP_EOL
            . '    use Bazzer;' . PHP_EOL
            . '    const RANDOM_NUMBER = 4;' . PHP_EOL
            . '    private $foo = 12;' . PHP_EOL
            . '    public function getFoo()' . PHP_EOL
            . '    {' . PHP_EOL
            . '        return $this->foo;' . PHP_EOL
            . '    }' . PHP_EOL
            . '}' . PHP_EOL
        );
    }

    /**
     * Assert that expected code is generated
     *
     * @param string $expected
     */
    private function assertCode($expected)
    {
        $this->assertEquals($expected, $this->stringWriter->toString());
    }
}
