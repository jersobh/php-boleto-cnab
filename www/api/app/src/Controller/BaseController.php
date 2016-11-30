<?php
namespace App\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Container;

class BaseController
{
    protected $view;
    protected $logger;
    protected $mongodb;

    public function __construct(Container $c)
    {
        $this->view = $c->get('view');
        $this->logger = $c->get('logger');
        $this->flash = $c->get('flash');
        $this->mongodb = $c->get('mongodb');
    }


}
