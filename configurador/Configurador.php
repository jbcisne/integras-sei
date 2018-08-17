<?php

session_start();

$configurador = new Configurador();

$action = $_POST['action'];
unset($_POST['action']);
$_SESSION[$action] = $_POST;

echo $configurador->$action();


class Configurador
{
    public function getMethods()
    {
        $wsdl = $_SESSION['getMethods']['wsdl'];

        try {
            $client = new \SoapClient($wsdl);

            $function = $client->__getFunctions();

            $arrMethods = [];
            foreach ($function as $item) {
                $pattern = '/^(?P<func_return>[^\s]+)\s+(?P<func_name>[^(]+)\((?P<func_args>[^\)]+)\)/';
                preg_match_all($pattern, $item, $result1);

                $pattern = '/(?:\s?(?P<arg_type>\w+)\s(?P<arg_name>\$\w+),?)/';
                preg_match_all($pattern, $result1['func_args'][0], $result2);

                $args = array();
                foreach ($result2['arg_type'] as $key => $type) {
                    $args[$result2['arg_name'][$key]] = $type;
                }

                $arrMethods[$result1['func_name'][0]] = array(
                    'return' => $result1['func_return'][0],
                    'args' => $args
                );
            }

            ksort($arrMethods, SORT_NATURAL);
            $return = ['error' => false, 'dados' => $arrMethods, 'msg' => null];
        } catch (\Exception $e) {
            $return = array(
                'error' => true,
                'dados' => null,
                'msg' => 'Não foi possível obter informações do WSDL. Verifique a URL do serviço'
            );
        }


        return json_encode($return);
    }

    public function listarSeries()
    {
        try {
            $client = new SoapClient($_SESSION['getMethods']['wsdl']);

            $result = $client->__call(
                __FUNCTION__,
                [
                    'SiglaSistema' => $_SESSION['getMethods']['sistema'],
                    'IdentificacaoServico' => $_SESSION['getMethods']['agrupador'],
                ]
            );

            echo '<pre>';
            print_r($result);


        } catch (Exception $e) {
            return 'Ocorreu um erro: ' . $e->getMessage();
        }
    }

    public function listarUnidades()
    {
        try {
            $client = new SoapClient($_SESSION['getMethods']['wsdl']);

            $result = $client->__call(
                __FUNCTION__,
                [
                    'SiglaSistema' => $_SESSION['getMethods']['sistema'],
                    'IdentificacaoServico' => $_SESSION['getMethods']['agrupador'],
                ]
            );

            if (isset($_SESSION['listarUnidades']['testeConfig'])) {
                if (is_array($result)) {
                    return json_encode(['error' => false, 'msg' => 'Configurações validadas com sucesso.']);
                }
            } else {
                echo '<pre>';
                print_r($result);
            }

        } catch (Exception $e) {
            if (isset($_SESSION['listarUnidades']['testeConfig'])) {
                return json_encode(['error' => true, 'msg' => 'Ocorreu um erro: ' . $e->getMessage()]);
            } else {
                return 'Ocorreu um erro: ' . $e->getMessage();
            }
        }
    }

    public function listarTiposProcedimento()
    {
        try {
            $client = new SoapClient($_SESSION['getMethods']['wsdl']);

            $result = $client->__call(
                __FUNCTION__,
                [
                    'SiglaSistema' => $_SESSION['getMethods']['sistema'],
                    'IdentificacaoServico' => $_SESSION['getMethods']['agrupador'],
                ]
            );

            echo '<pre>';
            print_r($result);

        } catch (Exception $e) {
            return 'Ocorreu um erro: ' . $e->getMessage();
        }
    }

    public function saveMethods()
    {
        $arrDados = array(
            'sei' => array(
                'system_external' => $_SESSION['getMethods']['sistema'],
                'system_grouper' => $_SESSION['getMethods']['agrupador'],
                'system_default_connection' => 'webservice',
                'sei_ws_num_max_docs' => $_SESSION['getMethods']['num_max_docs'],
                'version' => array(
                    $_SESSION['getMethods']['versao'] => array (
                        'connect' => array(
                            'webservice' => array(
                                'wsdl' => $_SESSION['getMethods']['wsdl'],
                                'options' => array(
                                    'encoding' => 'ISO-8859-1',
                                    'soap_version' => 'SOAP_1_1',
                                )
                            )
                        ),
                        'services' => $_SESSION['saveMethods']['operations'],
                    )
                )
            )
        );

        $fileName = '/configSEI' . str_replace('.', '', $_SESSION['getMethods']['versao']) . '.php';
        $path = dirname(__FILE__) . '/tmp';

        $fullFileName = $path . $fileName;

        try {

            if (!is_writable($path)) {
                throw new Exception('Permission denied to create config file');
            }

            file_put_contents(
                $fullFileName,
                '<?php ' . PHP_EOL . PHP_EOL
                . '/* Configuracao de manipulacao do SEI */' . PHP_EOL . PHP_EOL
                . 'return ' . var_export($arrDados, true) . ';'
            );

            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($fullFileName));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($fullFileName));
            readfile($fullFileName);

            unlink($fullFileName);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        exit;

    }

}
