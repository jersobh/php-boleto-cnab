# PBC - php-boleto-cnab

API's para boletos com registro (CNAB 240/CNAB 400), para gerar boletos, arquivos de remessa, processar arquivos de retorno e integrações com webservices.
### Docker

```sh
$ cd php-boleto-cnab
$ docker-compose up
```
### Tecnologias

O projeto utiliza HHVM 3.15.3 + MongoDB 3.2
* [OpenCnabPHP] - Biblioteca para gerar remessas e processar retornos
* [boletophp] - Biblioteca para gerar boletos

### Instruções
  - POST http://localhost/setup para gerar as collections no MongoDB 
  - POST http://localhost/setup/clear para fazer um reset da API

### Configurações
  - Edite o arquivo sites/default.vhost
  - Altere o parâmetro server_name para seu domínio

### Debugging

Os arquivos de log de serviços (Nginx, HHVM, MongoDB) são salvos automaticamente em logs/.
Os logs gerados pela API são salvos em www/api/log/app.log.