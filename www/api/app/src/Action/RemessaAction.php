<?php

namespace App\Action;

use \CnabPHP\Remessa;
use \CnabPHP\Retorno;

final class RemessaAction {

    function geraItau($dados) {
        $arquivo = new Remessa(341, 'Cnab400', array(
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
        
        header("Content-type: text/plain");
        header("Content-Disposition: attachment; filename=remessa.txt");
        print $arquivo->getText();
    }

    function geraCaixa($dados) {
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
            'valor' =>100.00, // Valor do boleto como float valido em php
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
        
        header("Content-type: text/plain");
        header("Content-Disposition: attachment; filename=remessa-caixa.txt");
        print $arquivo->getText();
    }

    function geraBB($dados) {
        $codigo_banco = \Cnab\Banco::BANCO_DO_BRASIL;
        $arquivo = new \Cnab\Remessa\Cnab240\Arquivo($codigo_banco);
        $arquivo->configure(array(
            'data_geracao' => new \DateTime(),
            'data_gravacao' => new \DateTime(),
            'nome_fantasia' => $dados->nome_fantasia, // seu nome de empresa
            'razao_social' => $dados->razao_social, // sua razão social
            'cnpj' => $dados->cnpj, // seu cnpj completo
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
            'operacao' =>  1,
            'numero_sequencial_arquivo' => '1',
        ));

        foreach($dados->detalhes as $detalhe){
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
    function geraSantander($dados) {
        echo "TODO";
    }
    
    /**
     * Todo - Bradesco
     * @param type $dados
     */
    function geraBradesco($dados) {
        echo "TODO";
    }

    function geraSicoob($dados) {
        $arquivo = new Remessa(756, 'cnab400', array(
            'nome_empresa' => $dados->nome_fantasia, // seu nome de empresa
            'tipo_inscricao' => 2, // 1 para cpf, 2 cnpj 
            'numero_inscricao' => '123.122.123-56', // seu cpf ou cnpj completo
            'agencia' => '3300', // agencia sem o digito verificador 
            'agencia_dv' => 6, // somente o digito verificador da agencia 
            'conta' => '3264', // número da conta
            'conta_dv' => 6, // digito da conta
            'codigo_beneficiario' => '10668', // codigo fornecido pelo banco
            'codigo_beneficiario_dv' => '2', // codigo fornecido pelo banco
            'numero_sequencial_arquivo' => 1,
            'situacao_arquivo' => 'P' // use T para teste e P para produ��o
        ));
        $lote = $arquivo->addLote(array('tipo_servico' => 1)); // tipo_servico  = 1 para cobran�a registrada, 2 para sem registro

        $lote->inserirDetalhe(array(
            'codigo_ocorrencia' => 1, //1 = Entrada de título, para outras opções ver nota explicativa C004 manual Cnab_SIGCB na pasta docs
            'nosso_numero' => 50, // numero sequencial de boleto
            'seu_numero' => 43, // se nao informado usarei o nosso numero 

            /* campos necessarios somente para itau e siccob,  não precisa comentar se for outro layout    */
            'carteira_banco' => 109, // codigo da carteira ex: 109,RG esse vai o nome da carteira no banco
            'cod_carteira' => "01", // I para a maioria ddas carteiras do itau
            /* campos necessarios somente para itau,  não precisa comentar se for outro layout    */
            'especie_titulo' => "DM", // informar dm e sera convertido para codigo em qualquer laytou conferir em especie.php
            'valor' => 100.00, // Valor do boleto como float valido em php
            'emissao_boleto' => 2, // tipo de emissao do boleto informar 2 para emissao pelo beneficiario e 1 para emissao pelo banco
            'protestar' => 3, // 1 = Protestar com (Prazo) dias, 3 = Devolver ap�s (Prazo) dias
            'prazo_protesto' => 5, // Informar o numero de dias apos o vencimento para iniciar o protesto
            'nome_pagador' => "JOSÉ da SILVA ALVES", // O Pagador � o cliente, preste atenção nos campos abaixo
            'tipo_inscricao' => 1, //campo fixo, escreva '1' se for pessoa fisica, 2 se for pessoa juridica
            'numero_inscricao' => '123.122.123-56', //cpf ou ncpj do pagador
            'endereco_pagador' => 'Rua dos developers,123 sl 103',
            'bairro_pagador' => 'Bairro da insonia',
            'cep_pagador' => '12345-123', // com h�fem
            'cidade_pagador' => 'Londrina',
            'uf_pagador' => 'PR',
            'data_vencimento' => '2016-04-09', // informar a data neste formato
            'data_emissao' => '2016-04-09', // informar a data neste formato
            'vlr_juros' => 0.15, // Valor do juros de 1 dia'
            'data_desconto' => '2016-04-09', // informar a data neste formato
            'vlr_desconto' => '0', // Valor do desconto
            'baixar' => 2, // codigo para indicar o tipo de baixa '1' (Baixar/ Devolver) ou '2' (N�o Baixar / N�o Devolver)
            'prazo_baixa' => 90, // prazo de dias para o cliente pagar ap�s o vencimento
            'mensagem' => 'JUROS de R$0,15 ao dia' . PHP_EOL . "Não receber apos 30 dias",
            'email_pagador' => 'rogerio@ciatec.net', // data da multa
            'data_multa' => '2016-04-09', // informar a data neste formato, // data da multa
            'vlr_multa' => 30.00, // valor da multa
            // campos necessários somente para o sicoob
            'taxa_multa' => 30.00, // taxa de multa em percentual
            'taxa_juros' => 30.00, // taxa de juros em percentual
        ));
        $arquivo->save('remessa-sicoob.txt');
        header("Content-type: text/plain");
        header("Content-Disposition: attachment; filename=remessa-sicoob.txt");
        print $arquivo->getText();
    }

}
