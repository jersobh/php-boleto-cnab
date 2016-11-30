<?php
// Routes

$app->get('/', App\Controller\HomeController::class)
    ->setName('homepage');

$app->get('/setup', 'App\Controller\SetupController:setup');

$app->get('/setup/clear', 'App\Controller\SetupController:clear');

$app->group('/remessa',
    function () {
    $this->post('/gerar', 'App\Controller\CnabController:geraRemessa');
});

$app->group('/retorno',
    function () {
    $this->post('/processa', 'App\Controller\CnabController:processaRetorno');
});
