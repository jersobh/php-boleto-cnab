<?php
// Routes

$app->get('/', App\Action\HomeAction::class)
    ->setName('homepage');

$app->get('/setup', 'App\Action\SetupAction:setup');

$app->get('/setup/clear', 'App\Action\SetupAction:clear');

$app->group('/remessa', function () {
        $this->post('/gerar', 'App\Action\CnabAction:geraRemessa');
    });
    
$app->group('/retorno', function () {
        $this->post('/processa', 'App\Action\CnabAction:processaRetorno');
    });