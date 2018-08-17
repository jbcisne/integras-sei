<?php
use \PHPUnit\Framework\TestCase;
use Jb\Integras\Sei\IntegrasSEI;
use Jb\Integras\Sei\IntegrasConfigParamenterSEI;

class IntegrasSEITest extends TestCase
{
    private $stub = null;

    private static $NSVersao260 = 'src\modules\integras\sei\version\IntegrasSEI260';

    private static $cfg = array(
        'version' => '2.6.0'
    );

    private static $cfgParamenter = null;

    public function setUp()
    {
        self::$cfgParamenter = new IntegrasConfigParamenterSEI(self::$cfg);

        $this->stub = $this->getMockForAbstractClass(
            'Jb\Integras\Sei\IntegrasSEI',
            array(self::$cfgParamenter)
        );
    }

    /**
     * @test
     * */
    public function hasServiceReturnTrueIfServiceIsAvailable()
    {
        $this->stub->expects($this->any())
             ->method('hasService')
             ->will($this->returnValue(TRUE));

        $this->assertTrue($this->stub->hasServiceImplemented('stub_available_service'));
    }

    /**
     * @test
     * */
    public function hasServiceReturnFalseIfServiceIsUnAvailable()
    {
        $this->stub->expects($this->any())
             ->method('hasService')
             ->will($this->returnValue(FALSE));

        $this->assertFalse($this->stub->hasServiceImplemented('stub_unavailable_service'));
    }

    /**
     * @test
     * */
    public function callStaticallyClassIntegraSeiFileExistsReturnTrueIfExists()
    {
        $class = get_class($this->stub);
        $this->assertTrue($class::classIntegraSeiFileExists(self::$cfgParamenter->version()));
    }

    /**
     * @test
     * */
    public function classIntegraSeiFileExistsReturnTrueIfExists()
    {
        $this->assertTrue($this->stub->classIntegraSeiFileExists(self::$cfgParamenter->version()));
    }

    /**
     * @test
     * */
    public function callStaticallyClassIntegraSeiFileExistsReturnFalseIfExists()
    {
        $class = get_class($this->stub);

        # versão não suporgada
        $this->assertFalse($class::classIntegraSeiFileExists(self::$cfgParamenter->version() . '-fake'));
    }

    /**
     * @test
     * */
    public function classIntegraSeiFileExistsReturnFalseIfExists()
    {
        # versão não suporgada
        $this->assertFalse($this->stub->classIntegraSeiFileExists(self::$cfgParamenter->version() . '-fake'));
    }

    /**
     * @test
     * */
    public function callStaticallyClassIntegrasSEINamespace()
    {
        $class = get_class($this->stub);
        $expected = $class::ClassIntegrasSEINamespace(self::$cfgParamenter->version());

        $this->assertEquals($expected, self::$NSVersao260);
    }

    /**
     * @test
     * */
    public function ClassIntegrasSEINamespace()
    {
        # versão não suporgada
        $expected = $this->stub->ClassIntegrasSEINamespace(self::$cfgParamenter->version());

        $this->assertEquals($expected, self::$NSVersao260);
    }

    /**
     * @test
     * */
    public function factorySEI260()
    {
        $this->assertInstanceOf(
            self::$NSVersao260
            , IntegrasSEI::factory(self::$cfgParamenter)
        );
    }

    /**
     * @test
     * @expectedException \Jb\Integras\Sei\Exception\IntegrasSEIVersionNotSupportedException
     * */
    public function factoryInvalidVersionThrowsIntegrasSEIVersionNotSupportedException()
    {
        $cfg = clone self::$cfgParamenter;
        $cfg->set('version', 'fake');

        IntegrasSEI::factory($cfg);
    }
}
