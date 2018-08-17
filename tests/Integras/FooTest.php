<?php

namespace Tests\ProjetoModelo;

use ProjetoModelo\Foo;

/**
 * Breve descrição da classe.
 *
 * @package ProjetoModelo
 * @author Nome <email@domin.com>
 */
class FooTest extends \PHPUnit_Framework_TestCase
{
    private $foo;
    private static $validFirstParameter    = 0.1;
    private static $validSecondParameter   = 0.1;
    private static $invalidFirstParameter   = null;
    private static $invalidSecondParameter = 0;

    public function setUp()
    {
        $this->foo = new Foo;
    }

    /**
     * @test
     * @covers \ProjetoModelo\Foo::add
     * @covers \ProjetoModelo\Foo::paramenterMustBeFloat
     * @expectedException \ProjetoModelo\Exception\IllegalArgumentException
     */
    public function addFailInvalidFirstParameter()
    {
        $add = clone $this->foo;
        $add->add(self::$invalidFirstParameter, self::$validSecondParameter);
    }

    /**
     * @test
     * @covers \ProjetoModelo\Foo::add
     * @covers \ProjetoModelo\Foo::paramenterMustBeFloat
     * @expectedException \ProjetoModelo\Exception\IllegalArgumentException
     */
    public function addFailInvalidSecontParameter()
    {
        $add = clone $this->foo;
        $add->add(self::$validFirstParameter, self::$invalidSecondParameter);
    }

    /**
     * @test
     * @covers \ProjetoModelo\Foo::add
     * @covers \ProjetoModelo\Foo::paramenterMustBeFloat
     */
    public function add()
    {
        $add = clone $this->foo;

        $this->assertEquals(0.2, $add->add(self::$validFirstParameter, self::$validSecondParameter));
    }

    /**
     * @test
     * @covers \ProjetoModelo\Foo::sub
     * @covers \ProjetoModelo\Foo::paramenterMustBeFloat
     * @expectedException \ProjetoModelo\Exception\IllegalArgumentException
     */
    public function subFailInvalidFirstParameter()
    {
        $add = clone $this->foo;
        $add->sub(self::$invalidFirstParameter, self::$validSecondParameter);
    }

    /**
     * @test
     * @covers \ProjetoModelo\Foo::sub
     * @covers \ProjetoModelo\Foo::paramenterMustBeFloat
     * @expectedException \ProjetoModelo\Exception\IllegalArgumentException
     */
    public function subFailInvalidSecontParameter()
    {
        $add = clone $this->foo;
        $add->sub(self::$validFirstParameter, self::$invalidSecondParameter);
    }

    /**
     * @test
     * @covers \ProjetoModelo\Foo::sub
     * @covers \ProjetoModelo\Foo::paramenterMustBeFloat
     */
    public function sub()
    {
        $add = clone $this->foo;
        $this->assertEquals(0, $add->sub(self::$validFirstParameter, self::$validSecondParameter));
    }

    /**
     * @test
     * @covers \ProjetoModelo\Foo::mult
     * @covers \ProjetoModelo\Foo::paramenterMustBeFloat
     * @expectedException \ProjetoModelo\Exception\IllegalArgumentException
     */
    public function multFailInvalidFirstParameter()
    {
        $add = clone $this->foo;
        $add->mult(self::$invalidFirstParameter, self::$validSecondParameter);
    }

    /**
     * @test
     * @covers \ProjetoModelo\Foo::mult
     * @covers \ProjetoModelo\Foo::paramenterMustBeFloat
     * @expectedException \ProjetoModelo\Exception\IllegalArgumentException
     */
    public function multFailInvalidSecontParameter()
    {
        $add = clone $this->foo;
        $add->mult(self::$validFirstParameter, self::$invalidSecondParameter);
    }

    /**
     * @test
     * @covers \ProjetoModelo\Foo::mult
     * @covers \ProjetoModelo\Foo::paramenterMustBeFloat
     */
    public function mult()
    {
        $add = clone $this->foo;
        $this->assertEquals(0.010000000000000002,
           $add->mult(self::$validFirstParameter, self::$validSecondParameter)
        );
    }

    /**
     * @test
     * @covers \ProjetoModelo\Foo::div
     * @covers \ProjetoModelo\Foo::paramenterMustBeFloat
     * @covers \ProjetoModelo\Foo::paramenterMustBeNumeric
     * @expectedException \ProjetoModelo\Exception\IllegalArgumentException
     */
    public function divFailInvalidFirstParameter()
    {
        $add = clone $this->foo;
        $add->div(null, self::$validSecondParameter);
    }

    /**
     * @test
     * @covers \ProjetoModelo\Foo::div
     * @covers \ProjetoModelo\Foo::paramenterMustBeFloat
     * @covers \ProjetoModelo\Foo::paramenterMustBeNumeric
     * @expectedException \ProjetoModelo\Exception\ZeroDivisionException
     */
    public function divFailInvalidSecontParameter()
    {
        $add = clone $this->foo;
        $add->div(self::$validFirstParameter, 0);
    }

    /**
     * @test
     * @covers \ProjetoModelo\Foo::div
     * @covers \ProjetoModelo\Foo::paramenterMustBeFloat
     * @covers \ProjetoModelo\Foo::paramenterMustBeNumeric
     * @expectedException \ProjetoModelo\Exception\ZeroDivisionException
     */
    public function divFailInvalidSecontParameterType()
    {
        $add = clone $this->foo;
        $add->div(self::$validFirstParameter, null);
    }

    /**
     * @test
     * @covers \ProjetoModelo\Foo::div
     * @covers \ProjetoModelo\Foo::paramenterMustBeFloat
     * @covers \ProjetoModelo\Foo::paramenterMustBeNumeric
     */
    public function div()
    {
        $add = clone $this->foo;
        $this->assertEquals(1, $add->div(self::$validFirstParameter, self::$validSecondParameter));
    }
}
