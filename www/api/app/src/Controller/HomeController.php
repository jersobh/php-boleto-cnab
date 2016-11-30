<?php
namespace App\Controller;

use Slim\Http\Request;
use Slim\Http\Response;

final class HomeController extends BaseController
{

    public function __invoke(Request $request, Response $response, $args)
    {
        $this->logger->info("Home page action dispatched");
        
        $this->view->render($response, 'home.twig');
        return $response;
    }
}
