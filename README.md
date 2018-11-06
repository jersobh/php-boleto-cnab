# PBC - php-boleto-cnab

API's para boletos com registro (CNAB 240/CNAB 400), para gerar boletos, arquivos de remessa, processar arquivos de retorno e integrações com webservices.
### Docker

```sh
$ cd php-boleto-cnab  
$ docker-compose build  
$ docker-compose up  
```
### Changelog
 - Correções no docker
 - Instalação automática das dependências 
 
### Tecnologias

O projeto utiliza:
* [OpenCnabPHP] - Biblioteca para gerar remessas e processar retornos - Itaú, Caixa e Sicoob
* [CnabPHP] - Biblioteca para gerar remessas e processar retornos - Banco do Brasil
* [boletophp] - Biblioteca para gerar boletos

### Instruções
  - GET http://localhost para página inicial da API
  - GET http://localhost/setup para gerar as collections no MongoDB 
  - GET http://localhost/setup/clear para fazer um reset da API
  - POST http://localhost/remessa/gerar com o json seguindo o padrão abaixo para gerar uma remessa:

Json exemplo: 
```json
{
	"codigo_banco": 756,
	"razao_social": "EMPRESA LTDA",
	"numero_inscricao": 1234567890123,
	"agencia": 1234,
	"agencia_dv": 2,
	"conta": 31234,
	"conta_dv": 3,
	"codigo_beneficiario": 123456,
	"codigo_beneficiario_dv": 2,
	"detalhes": [{
			"nosso_numero": 1234,
			"carteira": 123,
			"cod_carteira": 123,
			"valor": "100.00",
			"nome_pagador": "JÃO DO TESTE",
			"tipo_pagador": 1,
			"cpf_cnpj": "9211932313",
			"endereco_pagador": "Rua A",
			"bairro_pagador": "Bairro B",
			"cep_pagador": "30774942",
			"cidade_pagador": "Belo Horizonte",
			"uf_pagador": "MG",
			"data_vencimento": "02/12/2016",
			"data_emissao": "30/11/2016",
			"vlr_juros": "1.15",
			"taxa_juros": "1%",
			"data_desconto": "26/11/2016",
			"vlr_desconto": "0",
			"prazo": "",
			"mensagem": "",
			"email_pagador": "",
			"data_multa": "",
			"valor_multa": "",
			"taxa_multa": "10%"
		},

		{
			"nosso_numero": 1235,
			"carteira": 123,
			"cod_carteira": 123,
			"valor": "100.00",
			"nome_pagador": "IRMÃO DO JÃO DO TESTE",
			"tipo_pagador": 1,
			"cpf_cnpj": "9211932313",
			"endereco_pagador": "Rua A",
			"bairro_pagador": "Bairro B",
			"cep_pagador": "30774942",
			"cidade_pagador": "Belo Horizonte",
			"uf_pagador": "MG",
			"data_vencimento": "02/12/2016",
			"data_emissao": "30/11/2016",
			"vlr_juros": "1.15",
			"taxa_juros": "1%",
			"data_desconto": "30/11/2016",
			"vlr_desconto": "0",
			"prazo": "",
			"mensagem": "",
			"email_pagador": "",
			"data_multa": "",
			"valor_multa": "",
			"taxa_multa": "10%"
		}

	]



}
```
Json exemplo - Banco do Brasil
```json
    {
	"codigo_banco": 1,
	"razao_social": "EMPRESA LTDA",
	"nome_fantasia": "EMPRESA LTDA",
	"numero_inscricao": 1234567890123,
	"logradouro": "Rua A  somente Banco do Brasil",
	"numero": "889",
	"bairro": "Cabral  somente Banco do Brasil",
	"cidade": "Contagem  somente Banco do Brasil",
	"uf": "MG",
	"cep": "3l849-492",
	"agencia": 1234,
	"agencia_dv": 2,
	"conta": 31234,
	"conta_dv": 3,
	"codigo_beneficiario": 123456,
	"codigo_beneficiario_dv": 2,
	"codigo_convenio": 3,
	"codigo_carteira": 11,
	"variacao_carteira": 1,
	"detalhes": [{
			"nosso_numero": 1235,
			"carteira": 11,
			"aceite": "N",
			"valor": 100.00,
			"nome_pagador": "JÃO DO TESTE",
			"tipo_pagador": 1,
			"cpf_cnpj": "21.222.333.4444-55",
			"endereco_pagador": "Rua A",
			"bairro_pagador": "Bairro B",
			"cep_pagador": "30774942",
			"cidade_pagador": "Belo Horizonte",
			"uf_pagador": "MG",
			"data_vencimento": "02/12/2016",
			"data_emissao": "30/11/2016",
			"data_desconto": "05/12/2016",
			"vlr_desconto": 2.00,
			"vlr_juros": 1.15,
			"taxa_juros": "1%",
			"prazo": "10",
			"mensagem": "NÃO ACEITAR APÓS 10 DIAS",
			"data_multa": "10/12/2016",
			"valor_multa": "30",
			"baixar_apos_dias": 10,
			"dias_iniciar_contagem_juros": 1
		},

		{
			"nosso_numero": 1235,
			"carteira": 11,
			"aceite": "N",
			"valor": 100.00,
			"nome_pagador": "JÃO DO TESTE",
			"tipo_pagador": 1,
			"cpf_cnpj": "21.222.333.4444-55",
			"endereco_pagador": "Rua A",
			"bairro_pagador": "Bairro B",
			"cep_pagador": "30774942",
			"cidade_pagador": "Belo Horizonte",
			"uf_pagador": "MG",
			"data_vencimento": "02/12/2016",
			"data_emissao": "30/11/2016",
			"data_desconto": "05/12/2016",
			"vlr_desconto": 2.00,
			"vlr_juros": 1.15,
			"taxa_juros": "1%",
			"prazo": "10",
			"mensagem": "NÃO ACEITAR APÓS 10 DIAS",
			"data_multa": "10/12/2016",
			"valor_multa": "30",
			"baixar_apos_dias": 10,
			"dias_iniciar_contagem_juros": 1
		}

	]

}
```

### Configurações
Para rodar a API em um domínio:
  - Edite o arquivo sites/default.vhost
  - Altere o parâmetro server_name para seu domínio

### Debugging

Os arquivos de log de serviços (Nginx, HHVM, MongoDB) são salvos automaticamente em logs/.
Os logs gerados pela API são salvos em www/api/log/app.log.

### Homologados

  - Caixa Econômica Federal - Cnab240_SIGCB
  - Banco do Brasil - Cnab240
  - Itaú - Cnab400
  - SICOOB - Cnab400

### TODO

  - Santander
  - Bradesco

### Grupo de Discussão

* [Telegram] - Grupo de discussão no Telegram



   [OpenCnabPHP]: <https://github.com/QuilhaSoft/OpenCnabPHP>
   [CnabPHP]: <https://github.com/andersondanilo/CnabPHP>
   [boletophp]: <https://github.com/CobreGratis/boletophp>
   [telegram]: <https://telegram.me/joinchat/CeCR-wsdisesG2yhCJwRIQ>
