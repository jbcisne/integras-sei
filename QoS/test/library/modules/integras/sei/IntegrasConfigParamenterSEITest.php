<?php
use \PHPUnit\Framework\TestCase;
use Jb\Integras\Sei\IntegrasSEI;
use Jb\Integras\Sei\IntegrasConfigParamenterSEI;

class IntegrasConfigParamenterSEITest extends TestCase
{
    /**
     * @test
     * @expectedException \Jb\Integras\Sei\Exception\IntegrasConfigParamenterSEIException
     * */
    public function constructThrowsExceptionWithInvalidParamenterLikeAString()
    {
        new IntegrasConfigParamenterSEI('');
        new IntegrasConfigParamenterSEI('a');
        new IntegrasConfigParamenterSEI("");
    }

    /**
     * @test
     * @expectedException \Jb\Integras\Sei\Exception\IntegrasConfigParamenterSEIException
     * */
    public function constructThrowsExceptionWithInvalidParamenterLikeAnInteger()
    {
        new IntegrasConfigParamenterSEI(-1);
        new IntegrasConfigParamenterSEI(0);
        new IntegrasConfigParamenterSEI(+1);
    }

    /**
     * @test
     * @expectedException \Jb\Integras\Sei\Exception\IntegrasConfigParamenterSEIException
     * */
    public function constructThrowsExceptionWithInvalidParamenterLikeADouble()
    {
        new IntegrasConfigParamenterSEI(-1.0);
        new IntegrasConfigParamenterSEI(0.0);
        new IntegrasConfigParamenterSEI(+1.0);
    }

    /**
     * @test
     * @expectedException \Jb\Integras\Sei\Exception\IntegrasConfigParamenterSEIException
     * */
    public function constructThrowsExceptionWithInvalidParamenterLikeABoolean()
    {
        new IntegrasConfigParamenterSEI(true);
        new IntegrasConfigParamenterSEI(false);
    }

    /**
     * @test
     * @expectedException \Jb\Integras\Sei\Exception\IntegrasConfigParamenterSEIException
     * */
    public function constructThrowsExceptionWithVersionParameterNotFound()
    {
        new IntegrasConfigParamenterSEI(array());
        new IntegrasConfigParamenterSEI(new \stdClass);
    }

    /**
     * @test
     * */
    public function get()
    {
        $cfg = array(
            'version' => '2.6.0',
            'versionRaw' => 260,
        );

        $cParamenter = new IntegrasConfigParamenterSEI($cfg);
        $this->assertEquals($cParamenter->get('version'), $cfg['version']);
        $this->assertEquals($cParamenter->version(), $cfg['versionRaw']);
    }

    /**
     * @test
     * */
    public function set()
    {
        $cfg = array(
            'version' => '2.6.0',
            'versionRaw' => 270,
        );

        $cParamenter = new IntegrasConfigParamenterSEI($cfg);
        $cParamenter->set('version', '2.7.0');

        $this->assertEquals($cParamenter->get('version'), '2.7.0');
        $this->assertEquals($cParamenter->version(), $cfg['versionRaw']);
    }

    /**
     * @test
     * */
    public function has()
    {
        $cfg = array('version' => '2.6.0');

        $cParamenter = new IntegrasConfigParamenterSEI($cfg);

        $this->assertTrue($cParamenter->has('version'));
        $this->assertFalse($cParamenter->has('versionRaw'));
    }

    /**
     * @test
     * */
    public function version()
    {
        $cfg = array('version' => '2.6.0');

        $cParamenter = new IntegrasConfigParamenterSEI($cfg);
        $this->assertEquals($cParamenter->version(), '260');
        $this->assertEquals($cParamenter->versionRaw('version'), '2.6.0');
    }
}
