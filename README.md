# PBC - php-boleto-cnab

API's para boletos com registro (CNAB 240/CNAB 400), para gerar boletos, arquivos de remessa, processar arquivos de retorno e integrações com webservices.
### Docker

```sh
$ cd php-boleto-cnab
$ docker-compose up
```
### Tecnologias

O projeto utiliza HHVM 3.15.3 + MongoDB 3.2
* [OpenCnabPHP] - Biblioteca para gerar remessas e processar retornos - Itaú, Caixa e Sicoob
* [CnabPHP] - Biblioteca para gerar remessas e processar retornos - Banco do Brasil
* [boletophp] - Biblioteca para gerar boletos

### Instruções
  - POST http://localhost/setup para gerar as collections no MongoDB 
  - POST http://localhost/setup/clear para fazer um reset da API

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


   [OpenCnabPHP]: <https://github.com/QuilhaSoft/OpenCnabPHP>
   [CnabPHP]: <https://github.com/andersondanilo/CnabPHP>
   [boletophp]: <https://github.com/CobreGratis/boletophp>