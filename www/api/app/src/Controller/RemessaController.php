<?php

namespace App\Controller;

use \CnabPHP\Remessa;
use \CnabPHP\Retorno;
use Slim\Container;

final class RemessaController
{
    protected $mongodb;

    function __construct(Container $c)
    {
        $this->mongodb = $c->get('mongodb');
    }

    function geraItau($dados)
    {
        $registros         = $this->mongodb->remessas->count();
        $numero_sequencial = $registros + 1;
        $arquivo           = new Remessa(341, 'cnab400',
            array(
            'nome_empresa' => $dados->razao_social, // seu nome de empresa
            'tipo_inscricao' => 2, // 1 para cpf, 2 cnpj 
            'numero_inscricao' => $dados->numero_inscricao, // seu cpf ou cnpj completo
            'agencia' => $dados->agencia, // agencia sem o digito verificador
            'agencia_dv' => $dados->agencia_dv, // somente o digito verificador da agencia
            'conta' => $dados->conta, // número da conta
            'conta_dv' => $dados->conta_dv, // digito da conta
            'codigo_beneficiario' => $dados->codigo_beneficiario, // codigo fornecido pelo banco
            'numero_sequencial_arquivo' => $numero_sequencial, // sequencial do arquivo um numero novo para cada arquivo gerado
        ));
        $lote              = $arquivo->addLote(array('tipo_servico' => 1)); // tipo_servico  = 1 para cobrança registrada, 2 para sem registro

        foreach ($dados->detalhes as $boleto) {
            $lote->inserirDetalhe(array(
                'codigo_ocorrencia' => 1, //1 = Entrada de título, para outras opçoes ver nota explicativa C004 manual Cnab_SIGCB na pasta docs
                'nosso_numero' => $boleto->nosso_numero, // numero sequencial de boleto
                'seu_numero' => '', // se nao informado usarei o nosso numero
                /* campos necessarios somente para itau cnab400, não precisa comentar se for outro layout    */
                'carteira_banco' => $boleto->carteira, // codigo da carteira ex: 109,RG esse vai o nome da carteira no banco
                'cod_carteira' => $boleto->cod_carteira, // I para a maioria ddas carteiras do itau
                /* campos necessarios somente para itau, não precisa comentar se for outro layout   */
                'especie_titulo' => "DM", // informar dm e sera convertido para codigo em qualquer laytou conferir em especie.php
                'valor' => $boleto->valor, // Valor do boleto como float valido em php
                'emissao_boleto' => 2, // tipo de emissao do boleto informar 2 para emissao pelo beneficiario e 1 para emissao pelo banco
                'protestar' => 2, // 1 = Protestar com (Prazo) dias, 2 = Devolver após (Prazo) dias
                'nome_pagador' => $boleto->nome_pagador, // O Pagador é o cliente, preste atenção nos campos abaixo
                'tipo_inscricao' => $boleto->tipo_pagador, //campo fixo, escreva '1' se for pessoa fisica, 2 se for pessoa juridica
                'numero_inscricao' => $boleto->cpf_cnpj, //cpf ou ncpj do pagador
                'endereco_pagador' => $boleto->endereco_pagador,
                'bairro_pagador' => $boleto->bairro_pagador,
                'cep_pagador' => $boleto->cep_pagador, // com hífem
                'cidade_pagador' => $boleto->cidade_pagador,
                'uf_pagador' => $boleto->uf_pagador,
                'data_vencimento' => $boleto->data_vencimento, // informar a data neste formato
                'data_emissao' => $boleto->data_emissao, // informar a data neste formato
                'vlr_juros' => $boleto->vlr_juros, // Valor do juros de 1 dia'
                'data_desconto' => $boleto->data_desconto, // informar a data neste formato
                'vlr_desconto' => $boleto->vlr_desconto, // Valor do desconto
                'prazo' => $boleto->prazo, // prazo de dias para o cliente pagar após o vencimento
                'mensagem' => $boleto->mensagem,
                'email_pagador' => $boleto->email_pagador, // data da multa
                'data_multa' => $boleto->data_multa, // informar a data neste formato, // data da multa
                'valor_multa' => $boleto->valor_multa, // valor da multa
            ));
        }
//        header("Content-type: text/plain");
//        header("Content-Disposition: attachment; filename=remessa.txt");
        print $arquivo->getText();
    }

    function geraCaixa($dados)
    {
        $registros         = $this->mongodb->remessas->count();
        $numero_sequencial = $registros + 1;
        $arquivo           = new Remessa(104, 'cnab240_SIGCB',
            array(
            'nome_empresa' => $dados->razao_social, // seu nome de empresa
            'tipo_inscricao' => 2, // 1 para cpf, 2 cnpj
            'numero_inscricao' => $dados->numero_inscricao, // seu cpf ou cnpj completo
            'agencia' => $dados->agencia, // agencia sem o digito verificador
            'agencia_dv' => $dados->agencia_dv, // somente o digito verificador da agencia
            'conta' => $dados->conta, // número da conta
            'conta_dv' => $dados->conta_dv, // digito da conta
            'codigo_beneficiario' => $dados->codigo_beneficiario, // codigo fornecido pelo banco
            'numero_sequencial_arquivo' => $numero_sequencial, // sequencial do arquivo um numero novo para cada arquivo gerado
        ));
        $lote              = $arquivo->addLote(array('tipo_servico' => 1)); // tipo_servico  = 1 para cobrança registrada, 2 para sem registro

        foreach ($dados->detalhes as $boleto) {
            $lote->inserirDetalhe(array(
                'codigo_ocorrencia' => 1, //1 = Entrada de título, para outras opçoes ver nota explicativa C004 manual Cnab_SIGCB na pasta docs
                'nosso_numero' => $boleto->nosso_numero, // numero sequencial de boleto
                'seu_numero' => '', // se nao informado usarei o nosso numero
                /* campos necessarios somente para itau cnab400, não precisa comentar se for outro layout    */
                'carteira_banco' => $boleto->carteira, // codigo da carteira ex: 109,RG esse vai o nome da carteira no banco
                'cod_carteira' => $boleto->cod_carteira, // I para a maioria ddas carteiras do itau
                /* campos necessarios somente para itau, não precisa comentar se for outro layout   */
                'especie_titulo' => "DM", // informar dm e sera convertido para codigo em qualquer laytou conferir em especie.php
                'valor' => $boleto->valor, // Valor do boleto como float valido em php
                'emissao_boleto' => 2, // tipo de emissao do boleto informar 2 para emissao pelo beneficiario e 1 para emissao pelo banco
                'protestar' => 2, // 1 = Protestar com (Prazo) dias, 2 = Devolver após (Prazo) dias
                'nome_pagador' => $boleto->nome_pagador, // O Pagador é o cliente, preste atenção nos campos abaixo
                'tipo_inscricao' => $boleto->tipo_pagador, //campo fixo, escreva '1' se for pessoa fisica, 2 se for pessoa juridica
                'numero_inscricao' => $boleto->cpf_cnpj, //cpf ou ncpj do pagador
                'endereco_pagador' => $boleto->endereco_pagador,
                'bairro_pagador' => $boleto->bairro_pagador,
                'cep_pagador' => $boleto->cep_pagador, // com hífem
                'cidade_pagador' => $boleto->cidade_pagador,
                'uf_pagador' => $boleto->uf_pagador,
                'data_vencimento' => $boleto->data_vencimento, // informar a data neste formato
                'data_emissao' => $boleto->data_emissao, // informar a data neste formato
                'vlr_juros' => $boleto->vlr_juros, // Valor do juros de 1 dia'
                'data_desconto' => $boleto->data_desconto, // informar a data neste formato
                'vlr_desconto' => $boleto->vlr_desconto, // Valor do desconto
                'prazo' => $boleto->prazo, // prazo de dias para o cliente pagar após o vencimento
                'mensagem' => $boleto->mensagem,
                'email_pagador' => $boleto->email_pagador, // data da multa
                'data_multa' => $boleto->data_multa, // informar a data neste formato, // data da multa
                'valor_multa' => $boleto->valor_multa, // valor da multa
            ));
        }

        header("Content-type: text/plain");
        header("Content-Disposition: attachment; filename=remessa-caixa.txt");
        print $arquivo->getText();
    }

    function geraBB($dados)
    {
        
        $codigo_banco = \Cnab\Banco::BANCO_DO_BRASIL;
        $arquivo      = new \Cnab\Remessa\Cnab240\Arquivo($codigo_banco);
        $arquivo->configure(array(
            'data_geracao' => new \DateTime(),
            'data_gravacao' => new \DateTime(),
            'nome_fantasia' => $dados->nome_fantasia, // seu nome de empresa
            'razao_social' => $dados->razao_social, // sua razão social
            'cnpj' => $dados->numero_inscricao, // seu cnpj completo
            'banco' => $codigo_banco, //código do banco
            'codigo_convenio' => 11,
            'codigo_carteira' => $dados->codigo_carteira,
            'variacao_carteira' => 1,
            'logradouro' => $dados->logradouro,
            'numero' => $dados->numero,
            'bairro' => $dados->bairro,
            'cidade' => $dados->cidade,
            'uf' => $dados->uf,
            'cep' => $dados->cep,
            'agencia' => $dados->agencia,
            'agencia_dv' => $dados->agencia_dv,
            'conta' => $dados->conta, // número da conta
            'conta_dv' => $dados->conta_dv, // digito da conta
            'operacao' => 1,
            'numero_sequencial_arquivo' => '1',
        ));

        foreach ($dados->detalhes as $detalhe) {
            $arquivo->insertDetalhe(array(
                'codigo_ocorrencia' => 1, // 1 = Entrada de título, futuramente poderemos ter uma constante
                'nosso_numero' => '12345',
                'numero_documento' => '12345',
                'especie' => \Cnab\Especie::BB_DUPLICATA_MERCANTIL, // Você pode consultar as especies Cnab\Especie
                'valor' => 100.39, // Valor do boleto
                'instrucao1' => 2, // 1 = Protestar com (Prazo) dias, 2 = Devolver após (Prazo) dias, futuramente poderemos ter uma constante
                'instrucao2' => 0, // preenchido com zeros
                'sacado_nome' => 'Nome do cliente', // O Sacado é o cliente, preste atenção nos campos abaixo
                'sacado_tipo' => 'cpf', //campo fixo, escreva 'cpf' (sim as letras cpf) se for pessoa fisica, cnpj se for pessoa juridica
                'sacado_cpf' => '111.111.111-11',
                'sacado_logradouro' => 'Logradouro do cliente',
                'sacado_bairro' => 'Bairro do cliente',
                'sacado_cep' => '11111222', // sem hífem
                'sacado_cidade' => 'Cidade do cliente',
                'sacado_uf' => 'SP',
                'data_vencimento' => new \DateTime('2014-06-08'),
                'data_cadastro' => new \DateTime('2014-06-01'),
                'juros_de_um_dia' => 0.10, // Valor do juros de 1 dia'
                'data_desconto' => new \DateTime('2014-06-01'),
                'valor_desconto' => 10.0, // Valor do desconto
                'prazo' => 10, // prazo de dias para o cliente pagar após o vencimento
                'taxa_de_permanencia' => '0', //00 = Acata Comissão por Dia (recomendável), 51 Acata Condições de Cadastramento na CAIXA
                'mensagem' => 'Descrição do boleto',
                'data_multa' => new \DateTime('2014-06-09'), // data da multa
                'valor_multa' => 10.0, // valor da multa
            ));
        }

        $arquivo->save('remessa-bb.txt');
        header("Content-type: text/plain");
        header("Content-Disposition: attachment; filename=remessa-bb.txt");
        print $arquivo->getText();
    }

    /**
     * Todo - Santander
     * @param type $dados
     */
    function geraSantander($dados)
    {
        echo "TODO";
    }

    /**
     * Todo - Bradesco
     * @param type $dados
     */
    function geraBradesco($dados)
    {
        echo "TODO";
    }

    function geraSicoob($dados)
    {
        $registros         = $this->mongodb->remessas->count();
        $numero_sequencial = $registros + 1;

        //var_dump( $dados->taxa_juros);die;
        $arquivo = new Remessa(756, 'cnab400',
            array(
            'nome_empresa' => $dados->razao_social, // seu nome de empresa
            'tipo_inscricao' => 2, // 1 para cpf, 2 cnpj
            'numero_inscricao' => $dados->numero_inscricao, // seu cpf ou cnpj completo
            'agencia' => $dados->agencia, // agencia sem o digito verificador
            'agencia_dv' => $dados->agencia_dv, // somente o digito verificador da agencia
            'conta' => $dados->conta, // número da conta
            'conta_dv' => $dados->conta_dv, // digito da conta
            'codigo_beneficiario' => $dados->codigo_beneficiario, // codigo fornecido pelo banco
            'codigo_beneficiario_dv' => $dados->codigo_beneficiario, //somente sicoob
            'numero_sequencial_arquivo' => $numero_sequencial, // sequencial do arquivo um numero novo para cada arquivo gerado
        ));
        $lote    = $arquivo->addLote(array('tipo_servico' => 1)); // tipo_servico  = 1 para cobran�a registrada, 2 para sem registro

        foreach ($dados->detalhes as $boleto) {
            $lote->inserirDetalhe(array(
                'codigo_ocorrencia' => 1, //1 = Entrada de título, para outras opçoes ver nota explicativa C004 manual Cnab_SIGCB na pasta docs
                'nosso_numero' => $boleto->nosso_numero, // numero sequencial de boleto
                'seu_numero' => '', // se nao informado usarei o nosso numero
                /* campos necessarios somente para itau cnab400, não precisa comentar se for outro layout    */
                'carteira_banco' => $boleto->carteira, // codigo da carteira ex: 109,RG esse vai o nome da carteira no banco
                'cod_carteira' => $boleto->cod_carteira, // I para a maioria ddas carteiras do itau
                /* campos necessarios somente para itau, não precisa comentar se for outro layout   */
                'especie_titulo' => "DM", // informar dm e sera convertido para codigo em qualquer laytou conferir em especie.php
                'valor' => $boleto->valor, // Valor do boleto como float valido em php
                'emissao_boleto' => 2, // tipo de emissao do boleto informar 2 para emissao pelo beneficiario e 1 para emissao pelo banco
                'protestar' => 2, // 1 = Protestar com (Prazo) dias, 2 = Devolver após (Prazo) dias
                'nome_pagador' => $boleto->nome_pagador, // O Pagador é o cliente, preste atenção nos campos abaixo
                'tipo_inscricao' => $boleto->tipo_pagador, //campo fixo, escreva '1' se for pessoa fisica, 2 se for pessoa juridica
                'numero_inscricao' => $boleto->cpf_cnpj, //cpf ou ncpj do pagador
                'endereco_pagador' => $boleto->endereco_pagador,
                'bairro_pagador' => $boleto->bairro_pagador,
                'cep_pagador' => $boleto->cep_pagador, // com hífem
                'cidade_pagador' => $boleto->cidade_pagador,
                'uf_pagador' => $boleto->uf_pagador,
                'data_vencimento' => $boleto->data_vencimento, // informar a data neste formato
                'data_emissao' => $boleto->data_emissao, // informar a data neste formato
                'vlr_juros' => $boleto->vlr_juros, // Valor do juros de 1 dia'
                'taxa_juros' => $boleto->taxa_juros,
                'data_desconto' => $boleto->data_desconto, // informar a data neste formato
                'vlr_desconto' => $boleto->vlr_desconto, // Valor do desconto
                'prazo' => $boleto->prazo, // prazo de dias para o cliente pagar após o vencimento
                'mensagem' => $boleto->mensagem,
                'email_pagador' => $boleto->email_pagador, // data da multa
                'data_multa' => $boleto->data_multa, // informar a data neste formato, // data da multa
                'valor_multa' => $boleto->valor_multa, // valor da multa
                'taxa_multa' => $boleto->taxa_multa, //somente sicoob
            ));
        }
       
        print $arquivo->getText();
    }
}