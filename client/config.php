<?php
/**
 * Configuracao de manipulacao do SEI
 */
return array(
    /* identificador do sistema que fará acesso ao SEI */
    'system_external' => 'name',

    /* agrupador de serviços disponíveis para o sistema informado em 'external_external' */
    'system_grouper' => 'name',

    /*
     * devido ao fato que cada versão do sei pode ser conectado via banco ou webservice,
     * esta propriedade define a forma padrão de conexão com os serviços
     */
    'system_default_connection' => 'webservice',

    /*
     * O número máximo de documentos por chamada é limitado através do parâmetro
     * SEI_WS_NUM_MAX_DOCS (menu Infra/Parâmetros).
     */
    'sei_ws_num_max_docs' => 5,

    /* versões do SEI disponíveis */
    'version' => array(
        '2.6.0' => array(
            'url_base' => 'http://[dominio]/sei',
            'connect' => array(
                /* configuração de acesso ao banco de dados do SEI */
                'database' => array(
                    'pdo_driver' => null,
                    'hostname' => null,
                    'username' => null,
                    'password' => null,
                    'database' => null,
                ),

                /* configuracao de acesso ao webservice do SEI */
                'webservice' => array(
                    'wsdl' => 'http://[dominio]/sei/controlador_ws.php?servico=sei',
                    'options' => array(
                        'encoding' => 'ISO-8859-1',
                        'soap_version' => SOAP_1_1,
                        'cache_wsdl' => WSDL_CACHE_NONE,
                    )
                ),
            ),

            /*
             * lista de serviços disponíveis pelo componente cada nome na lista a seguir deve corresponder
             * a uma classe na pasta library/modules/integras/sei/version/services/SEI{version}{type} em que:
             *
             * {version}    corresponde a verão do SEI em uso e definida na entrada acima;
             * {type}       corresponde ao tipo de acesso: database, webservice
             **/
            'services' => array(
                'consultarDocumento',
                'consultarProcedimento',
                'gerarProcedimento',
                'incluirDocumento',
                'listarExtensoesPermitidas',
                'listarSeries',
                'listarTiposProcedimento',
                'listarUnidades',
                'listarUsuarios',

            ),
        ),
        '3.0.0' => array(
            'url_base' => 'http://10.221.1.208/sei',
            'curl_ssl_verify' => true,
            'connect' => array(
                /* configuracao de acesso ao webservice do SEI */
                'webservice' => array(
                    'wsdl' => 'http://10.221.1.208/sei/controlador_ws.php?servico=sei',
                    'endpoint' => 'http://10.221.1.208/sei/ws/SeiWS.php',
                    'options' => array(
                        'encoding' => 'ISO-8859-1',
                        'soap_version' => SOAP_1_1,
                        'cache_wsdl' => WSDL_CACHE_NONE,
                    )
                ),
            ),

            /*
             * lista de serviços disponíveis pelo componente cada nome na lista a seguir deve corresponder
             * a uma classe na pasta library/modules/integras/sei/version/services/SEI{version}{type} em que:
             *
             * {version}    corresponde a verão do SEI em uso e definida na entrada acima;
             * {type}       corresponde ao tipo de acesso: database, webservice
             **/
            'services' => array(
                'adicionarArquivo',
                'adicionarConteudoArquivo',
                'consultarDocumento',
                'consultarProcedimento',
                'listarAndamentos',
                'listarHipotesesLegais',
                'listarMarcadoresUnidade',
                'listarPaises',
                'listarUnidades',
                'listarUsuarios',
                'reabrirProcesso',
            ),
        )
    )

);
