<?php
// Routes

$app->get('/', App\Action\HomeAction::class)
    ->setName('homepage');

$app->get('/setup', 'App\Action\SetupAction:setup');

$app->get('/setup/clear', 'App\Action\SetupAction:clear');

//$app->get('/', 'App\Controller\HomeController:dispatch');