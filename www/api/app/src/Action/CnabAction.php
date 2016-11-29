<?php

namespace App\Action;

use Slim\Http\Request;
use Slim\Http\Response;
use \CnabPHP\Remessa;
use \CnabPHP\Retorno;

final class CnabAction extends BaseAction {

    /**
     * Gera arquivo de remessa (txt)
     * @param Request $request
     * @param Response $response
     * @param type $args
     */
    public function geraRemessa(Request $request, Response $response, $args) {

        try {
            $dados = $request->getParsedBody();
            $dados->nosso_numero = 1;
            $dados->carteira = 109;
            $dados->valor = 100.00;
            $arquivo = new Remessa(104, 'cnab240_SIGCB', array(
                'nome_empresa' => "Empresa ABC", // seu nome de empresa
                'tipo_inscricao' => 2, // 1 para cpf, 2 cnpj 
                'numero_inscricao' => '12345678901234', // seu cpf ou cnpj completo
                'agencia' => '1234', // agencia sem o digito verificador 
                'agencia_dv' => 1, // somente o digito verificador da agencia 
                'conta' => '12345', // número da conta
                'conta_dac' => 1, // digito da conta
                'codigo_beneficiario' => '123456', // codigo fornecido pelo banco
                'numero_sequencial_arquivo' => $dados->nosso_numero, // sequencial do arquivo um numero novo para cada arquivo gerado
            ));
            $lote = $arquivo->addLote(array('tipo_servico' => 1)); // tipo_servico  = 1 para cobrança registrada, 2 para sem registro

            $lote->inserirDetalhe(array(
                'codigo_ocorrencia' => 1, //1 = Entrada de título, para outras opçoes ver nota explicativa C004 manual Cnab_SIGCB na pasta docs
                'nosso_numero' => $dados->nosso_numero, // numero sequencial de boleto
                'seu_numero' => 1, // se nao informado usarei o nosso numero 

                /* campos necessarios somente para itau cnab400, não precisa comentar se for outro layout    */
                'carteira_banco' => $dados->carteira, // codigo da carteira ex: 109,RG esse vai o nome da carteira no banco
                'cod_carteira' => "I", // I para a maioria ddas carteiras do itau
                /* campos necessarios somente para itau, não precisa comentar se for outro layout   */
                'especie_titulo' => "DM", // informar dm e sera convertido para codigo em qualquer laytou conferir em especie.php
                'valor' => $dados->valor, // Valor do boleto como float valido em php
                'emissao_boleto' => 2, // tipo de emissao do boleto informar 2 para emissao pelo beneficiario e 1 para emissao pelo banco
                'protestar' => 2, // 1 = Protestar com (Prazo) dias, 2 = Devolver após (Prazo) dias
                'nome_pagador' => "JOSÉ da SILVA ALVES", // O Pagador é o cliente, preste atenção nos campos abaixo
                'tipo_inscricao' => 1, //campo fixo, escreva '1' se for pessoa fisica, 2 se for pessoa juridica
                'numero_inscricao' => '123.122.123-56', //cpf ou ncpj do pagador
                'endereco_pagador' => 'Rua dos developers,123 sl 103',
                'bairro_pagador' => 'Bairro da insonia',
                'cep_pagador' => '12345-123', // com hífem
                'cidade_pagador' => 'Londrina',
                'uf_pagador' => 'PR',
                'data_vencimento' => '2016-04-09', // informar a data neste formato
                'data_emissao' => '2016-04-09', // informar a data neste formato
                'vlr_juros' => 0.15, // Valor do juros de 1 dia'
                'data_desconto' => '2016-04-09', // informar a data neste formato
                'vlr_desconto' => '0', // Valor do desconto
                'prazo' => 5, // prazo de dias para o cliente pagar após o vencimento
                'mensagem' => 'JUROS de R$0,15 ao dia' . PHP_EOL . "Não receber apos 30 dias",
                'email_pagador' => 'rogerio@ciatec.net', // data da multa
                'data_multa' => '2016-04-09', // informar a data neste formato, // data da multa
                'valor_multa' => 30.00, // valor da multa
            ));
            $remessa = array('nosso_numero' => $dados->nosso_numero, 'carteira' => $dados->carteira, 'valor' => $dados->valor, 'criado' => new \MongoDate());
            $this->mongodb->remessas->insert($remessa);
            header("Content-type: text/plain");
            header("Content-Disposition: attachment; filename=remessa.txt");
            print $arquivo->getText();
        } catch (Exception $ex) {
            
        }
    }

    /**
     * Processa arquivo de retorno e salva no banco.
     * @param Request $request
     * @param Response $response
     * @param type $args
     */
    public function processaRetorno(Request $request, Response $response, $args) {
        $arquivo = $request->getParam('arquivo'); //Pega caminho do arquivo enviado por parâmetro
        $fileContent = file_get_contents($arquivo); //Pega dados do arquivo de retorno
        $arquivo = new Retorno($fileContent); //Processa retorno
        $registros = $arquivo->getRegistros(); //Peg registros
        foreach ($registros as $registro) {
            //Pra cada registro, fazemos a inclusão se foi pago
            if ($registro->codigo_movimento == 6) {
                $retorno = array('nosso_numero' => $registro->nosso_numero, 'carteira' => $registro->carteira, 'valor_recebido' => $registro->vlr_pago, 'data_pagamento' => $registro->data_ocorrencia, 'processado' => new \MongoDate());
                $this->mongodb->retornos->insert($retorno);
            }
        }
    }

}
