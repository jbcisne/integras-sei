<?php
use \PHPUnit\Framework\TestCase;
use Jb\Integras\Sei\IntegrasSEI;
use Jb\Integras\Sei\IntegrasConfigParamenterSEI;

class ProcessoTest extends TestCase
{
    const STR_MSG_ZERO_KILL = 'zero kill!';

    private static $cfg = array(
        'version' => '2.6.0'
    );

    /**
     * exemplo de uso de um serviço integrador do SEI
     *
     * @test
     * */
    public function InterfaceServiceRunWithoutParameter()
    {
        $parameter = new IntegrasConfigParamenterSEI(self::$cfg);
        $integraSEI260 = IntegrasSEI::factory($parameter);

        # ao invocar o nome do serviço, o componente executará o método
        # ServiceExample::run(). Este método, se invocado sem parâmetros,
        # apenas retorna a string: zero kill!
        $content = $integraSEI260->ServiceExample();

        $this->assertEquals($content, self::STR_MSG_ZERO_KILL);
    }

    /**
     * exemplo de uso de um serviço integrador do SEI
     *
     * @test
     * */
    public function InterfaceServiceRunWithParameter()
    {
        $arguments = array('zero', 'kill!');

        $parameter = new IntegrasConfigParamenterSEI(self::$cfg);
        $integraSEI260 = IntegrasSEI::factory($parameter);

        # ao invocar o nome do serviço, o componente executará o método
        # ServiceExample::run(). Este método, se invocado sem parâmetros,
        # apenas retorna a representacao JSON do parâmetro informado
        $content = $integraSEI260->ServiceExample($arguments);

        $this->assertEquals($content, json_encode($arguments));
    }
}
