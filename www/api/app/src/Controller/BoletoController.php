<?php

namespace App\Controller;
require '../autoloader.php';

use Slim\Http\Request;
use Slim\Http\Response;
use OpenBoleto\Banco\BancoDoBrasil;
use OpenBoleto\Banco\Caixa;
use OpenBoleto\Banco\Itau;
use OpenBoleto\Banco\Bradesco;
use OpenBoleto\Banco\Santander;
use OpenBoleto\Banco\Sicoob;
use OpenBoleto\Banco\Cecred;
use OpenBoleto\Banco\Sicredi;

use OpenBoleto\Agente;

final class BoletoController extends BaseController {

    private $dados;

    public function __construct($dados, $sacado, $cedente) {
        $this->dados = $dados;
        $this->dados['sacado'] = $sacado;
        $this->dados['cedente'] = $cedente;
    }

    function geraBB() {
        $boleto = new BancoDoBrasil($this->dados);
        try {
            return $boleto->getOutput();
        } catch (\RuntimeException $e) {
            echo $e->getMessage();
            die;
        }
    }

    function geraCaixa() {
        $boleto = new Caixa($this->dados);
        try {
            return $boleto->getOutput();
        } catch (\RuntimeException $e) {
            echo $e->getMessage();
            die;
        }
    }

    function geraItau() {
        $boleto = new Itau($this->dados);
        try {
            return $boleto->getOutput();
        } catch (\RuntimeException $e) {
            echo $e->getMessage();
            die;
        }
    }

    function geraBradesco() {
        $boleto = new Bradesco($this->dados);
        try {
            return $boleto->getOutput();
        } catch (\RuntimeException $e) {
            echo $e->getMessage();
            die;
        }
    }

    function geraSantander() {
        $boleto = new Santander($this->dados);
        try {
            return $boleto->getOutput();
        } catch (\RuntimeException $e) {
            echo $e->getMessage();
            die;
        }
    }

    function geraSicoob() {
        $boleto = new Sicoob($this->dados);
        try {
            echo $boleto->getOutput();
        } catch (\RuntimeException $e) {
            echo $e->getMessage();
            die;
        }
    }

    function geraCecred() {
        $boleto = new Cecred($this->dados);
        try {
            echo $boleto->getOutput();
        } catch (\RuntimeException $e) {
            echo $e->getMessage();
            die;
        }
    }

    function testeBoleto() {
        $sacado = new Agente('Fernando Maia', '023.434.234-34', 'ABC 302 Bloco N', '72000-000', 'Brasília', 'DF');
        $cedente = new Agente('Empresa de cosméticos LTDA', '02.123.123/0001-11', 'CLS 403 Lj 23', '71000-000', 'Brasília', 'DF');
        $boleto = new BancoDoBrasil(array(
            // Parâmetros obrigatórios
            'dataVencimento' => new DateTime('2013-01-24'),
            'valor' => 23.00,
            'sequencial' => 1234567,
            'sacado' => $sacado,
            'cedente' => $cedente,
            'agencia' => 1724, // Até 4 dígitos
            'carteira' => 18,
            'conta' => 10403005, // Até 8 dígitos
            'convenio' => 1234, // 4, 6 ou 7 dígitos
            // Caso queira um número sequencial de 17 dígitos, a cobrança deverá:
            // - Ser sem registro (Carteiras 16 ou 17)
            // - Convênio com 6 dígitos
            // Para isso, defina a carteira como 21 (mesmo sabendo que ela é 16 ou 17, isso é uma regra do banco)
            // Parâmetros recomendáveis
            //'logoPath' => 'http://empresa.com.br/logo.jpg', // Logo da sua empresa
            'contaDv' => 2,
            'agenciaDv' => 1,
            'descricaoDemonstrativo' => array( // Até 5
                'Compra de materiais cosméticos',
                'Compra de alicate',
            ),
            'instrucoes' => array( // Até 8
                'Após o dia 30/11 cobrar 2% de mora e 1% de juros ao dia.',
                'Não receber após o vencimento.',
            ),
            // Parâmetros opcionais
            //'resourcePath' => '../resources',
            //'moeda' => BancoDoBrasil::MOEDA_REAL,
            //'dataDocumento' => new DateTime(),
            //'dataProcessamento' => new DateTime(),
            //'contraApresentacao' => true,
            //'pagamentoMinimo' => 23.00,
            //'aceite' => 'N',
            //'especieDoc' => 'ABC',
            //'numeroDocumento' => '123.456.789',
            //'usoBanco' => 'Uso banco',
            //'layout' => 'layout.phtml',
            //'logoPath' => 'http://boletophp.com.br/img/opensource-55x48-t.png',
            //'sacadorAvalista' => new Agente('Antônio da Silva', '02.123.123/0001-11'),
            //'descontosAbatimentos' => 123.12,
            //'moraMulta' => 123.12,
            //'outrasDeducoes' => 123.12,
            //'outrosAcrescimos' => 123.12,
            //'valorCobrado' => 123.12,
            //'valorUnitario' => 123.12,
            //'quantidade' => 1,
        ));

        try {
            echo 'teste';
            echo $boleto->getOutput();
        } catch (\RuntimeException $e) {
            echo $e->getMessage();
            die;
        }

    }

}
