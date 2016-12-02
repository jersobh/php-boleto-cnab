<?php

namespace App\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use \CnabPHP\Remessa;
use \CnabPHP\Retorno;

final class CnabController extends BaseController
{
    private $remessa_controller;

    public function __construct(\Slim\Container $c)
    {
        parent::__construct($c);

        $this->remessa_controller = $c->get('remessa');
    }

    /**
     * Gera arquivo de remessa (txt)
     * @param Request $request
     * @param Response $response
     * @param type $args
     */
    public function geraRemessa(Request $request, Response $response, $args)
    {

        $dados = $request->getBody();
        $dados = json_decode($dados);
        //var_dump($dados->codigo_banco);die;
        switch ($dados->codigo_banco) {
            case 1:
                $this->remessa_controller->geraBB($dados);
                break;
            case 33:
                $this->remessa_controller->geraSantander($dados);
                break;
            case 104:
                $this->remessa_controller->geraCaixa($dados);
                break;
            case 237:
                $this->remessa_controller->geraBradesco($dados);
                break;
            case 341:
                $this->remessa_controller->geraItau($dados);
                break;
            case 756:
                $this->remessa_controller->geraSicoob($dados);
                break;
            default:
                echo "Você deve passar o código do banco como parâmetro";
        }

        $remessa = array('nosso_numero' => $dados->nosso_numero, 'carteira' => $dados->carteira,
            'valor' => $dados->valor, 'criado' => new \MongoDate());
        $this->mongodb->remessas->insert($remessa);
    }

    /**
     * Processa arquivo de retorno e salva no banco.
     * @param Request $request
     * @param Response $response
     * @param type $args
     */
    public function processaRetorno(Request $request, Response $response, $args)
    {
        $files = $request->getUploadedFiles();
        if (empty($files['newfile'])) {
            $response->withJson('{ "erro" : { "Nenhum arquivo enviado"} }');
            $response->withStatus(500);
            return $response;
        }

        $arquivo     = $files['newfile'];
        //$arquivo = $request->getParam('arquivo'); //Pega caminho do arquivo enviado por parâmetro
        $fileContent = file_get_contents($arquivo); //Pega dados do arquivo de retorno
        $arquivo     = new Retorno($fileContent); //Processa retorno
        $registros   = $arquivo->getRegistros(); //Peg registros
        foreach ($registros as $registro) {
            //Pra cada registro, fazemos a inclusão se foi pago
            if ($registro->codigo_movimento == 6) {
                $retorno = array('nosso_numero' => $registro->nosso_numero, 'carteira' => $registro->carteira,
                    'valor_recebido' => $registro->vlr_pago, 'data_pagamento' => $registro->data_ocorrencia,
                    'processado' => new \MongoDate());
                $this->mongodb->retornos->insert($retorno);
            }
        }
    }
}
