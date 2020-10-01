<?php

namespace Jb\Integras\Sei\Parse;

use Jb\Integras\Exception\IntegrasException;
use KubAT\PhpSimple\HtmlDomParser;
use simple_html_dom\simple_html_dom;

class Html
{
    /**
     * @var simple_html_dom
     */
    protected $dom;

    /**
     * @var bool
     */
    protected $ssl_verify = true;

    public function loadFromUrl($url)
    {
        $this->dom = HtmlDomParser::str_get_html($this->getHtmlContent($url));
    }

    public function documentos($seiEndPointLocation)
    {
        /** @var array $tableDocs */
        $tableDocs = $this->dom->find('#tblDocumentos');
        $documentos = $this->inflateDocuments($tableDocs, $seiEndPointLocation);
        return $documentos;
    }

    private function inflateDocuments($tableDocs, $seiEndPointLocation)
    {
        $documentos = array();
        /** @var simple_html_dom_node $iterator */
        foreach ($tableDocs as $iterator) {
            /** @var simple_html_dom_node $tr */
            foreach ($iterator->find('tr.infraTrClara') as $tr) {
                // td[0] é um checkbox sem necessidade
                $td = $tr->find('td');
                /** @var simple_html_dom_node $td1 */
                $td1 = current($td[1]->find('a'));

                /* remonta url do documento*/
                $regexPattern = "/'(?P<url>(?P<method>[^\.]+).*)'/";
                preg_match($regexPattern, $td1->attr['onclick'], $matches);
                $printPattern = "window.open('%s/%s');";
                $link = null;
                //so monta link de documentos
                if (isset($matches['url']) && $matches['method'] !== 'processo_acesso_externo_consulta') {
                    $link = sprintf($printPattern, $seiEndPointLocation, $matches['url']);
                }

                $doc = array(
                    'documento' => $td1->innertext(),
                    'link' => $link,
                    'tipo' => $td[2]->innertext(),
                    'data' => $td[3]->innertext(),
                    'unidade' => current($td[4]->find('a'))->innertext(),
                    'parent' => null,
                );

                if (isset($matches['method']) && $matches['method'] === 'processo_acesso_externo_consulta') {
                    $content = HtmlDomParser::str_get_html(
                        $this->getHtmlContent($seiEndPointLocation . '/' . $matches['url'])
                    );
                    $doc['parent'] = $this->inflateDocuments($content->find('#tblDocumentos'), $seiEndPointLocation);
                }

                $documentos[] = $doc;
            }
        }
        return $documentos;
    }

    public function andamentos()
    {
        $tableHist = $this->dom->find('#tblHistorico');

        $historicos = array();
        /** @var simple_html_dom_node $iterator */
        foreach ($tableHist as $iterator) {
            /** @var simple_html_dom_node $tr */
            foreach ($iterator->find('tr') as $key => $tr) {
                if ($key > 0) {
                    $td = $tr->find('td');
                    $historicos[] = array(
                        'data' => $td[0]->innertext(),
                        'unidade' => current($td[1]->find('a'))->innertext(),
                        'descricao' => strip_tags($td[2]->innertext()),
                    );
                }
            }
        }
        return $historicos;
    }

    /**
     * A simple curl implementation to get the content of the url.
     *
     * @param string $url
     * @return string
     * @throws IntegrasException
     */
    private function getHtmlContent($url)
    {
        $ch = curl_init($url);

        if (!ini_get('open_basedir')) {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

        if (!$this->ssl_verify) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        }

        $content = curl_exec($ch);
        if ($content === false) {
            // there was a problem
            $error = curl_error($ch);
            throw new IntegrasException('Error retrieving "' . $url . '" (' . $error . ')');
        }

        return $content;
    }
}
