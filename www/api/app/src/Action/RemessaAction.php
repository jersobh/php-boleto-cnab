<?php

namespace App\Action;

use \CnabPHP\Remessa;
use \CnabPHP\Retorno;

final class RemessaAction {
    
    function geraItau(){
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
    }
    
    private function geraCaixa(){
        $dados = $request->getParsedBody();
            $dados->codigo_banco;
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
    }
    
    private function geraBB(){
        $codigo_banco = \Cnab\Banco::BANCO_DO_BRASIL;
$arquivo = new Cnab\Remessa\Cnab400\Arquivo($codigo_banco);
$arquivo->configure(array(
    'data_geracao'  => new DateTime(),
    'data_gravacao' => new DateTime(), 
    'nome_fantasia' => 'Nome Fantasia da sua empresa', // seu nome de empresa
    'razao_social'  => 'Razão social da sua empresa',  // sua razão social
    'cnpj'          => '111', // seu cnpj completo
    'banco'         => $codigo_banco, //código do banco
    'logradouro'    => 'Logradouro da Sua empresa',
    'numero'        => 'Número do endereço',
    'bairro'        => 'Bairro da sua empresa', 
    'cidade'        => 'Cidade da sua empresa',
    'uf'            => 'Sigla da cidade, ex SP',
    'cep'           => 'CEP do endereço da sua cidade',
    'agencia'       => '1111', 
    'conta'         => '22222', // número da conta
    'conta_dac'     => '2', // digito da conta
));

// você pode adicionar vários boletos em uma remessa
$arquivo->insertDetalhe(array(
    'codigo_ocorrencia' => 1, // 1 = Entrada de título, futuramente poderemos ter uma constante
    'nosso_numero'      => '1234567',
    'numero_documento'  => '1234567',
    'carteira'          => '109',
    'especie'           => Cnab\Especie::ITAU_DUPLICATA_DE_SERVICO, // Você pode consultar as especies Cnab\Especie
    'valor'             => 100.39, // Valor do boleto
    'instrucao1'        => 2, // 1 = Protestar com (Prazo) dias, 2 = Devolver após (Prazo) dias, futuramente poderemos ter uma constante
    'instrucao2'        => 0, // preenchido com zeros
    'sacado_nome'       => 'Nome do cliente', // O Sacado é o cliente, preste atenção nos campos abaixo
    'sacado_tipo'       => 'cpf', //campo fixo, escreva 'cpf' (sim as letras cpf) se for pessoa fisica, cnpj se for pessoa juridica
    'sacado_cpf'        => '111.111.111-11',
    'sacado_logradouro' => 'Logradouro do cliente',
    'sacado_bairro'     => 'Bairro do cliente',
    'sacado_cep'        => '11111222', // sem hífem
    'sacado_cidade'     => 'Cidade do cliente',
    'sacado_uf'         => 'SP',
    'data_vencimento'   => new DateTime('2014-06-08'),
    'data_cadastro'     => new DateTime('2014-06-01'),
    'juros_de_um_dia'     => 0.10, // Valor do juros de 1 dia'
    'data_desconto'       => new DateTime('2014-06-01'),
    'valor_desconto'      => 10.0, // Valor do desconto
    'prazo'               => 10, // prazo de dias para o cliente pagar após o vencimento
    'taxa_de_permanencia' => '0', //00 = Acata Comissão por Dia (recomendável), 51 Acata Condições de Cadastramento na CAIXA
    'mensagem'            => 'Descrição do boleto',
    'data_multa'          => new DateTime('2014-06-09'), // data da multa
    'valor_multa'         => 10.0, // valor da multa
));

// para salvar
$arquivo->save('meunomedearquivo.txt');
header("Content-type: text/plain");
            header("Content-Disposition: attachment; filename=remessa.txt");
            print $arquivo->getText();
    }
    
    private function geraSantander(){
        
    }
    
    private function geraSicoob(){
        
    }
}
