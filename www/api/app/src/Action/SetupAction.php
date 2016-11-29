<?php

namespace App\Action;

use Slim\Http\Request;
use Slim\Http\Response;
use Monolog\ErrorHandler;

final class SetupAction extends BaseAction {

    public function setup() {
        
        try{
            $this->mongodb->command(array(
            "create" => 'users',
            "autoIndexId" => true,
        ));
        $this->mongodb->command(array(
            "create" => 'remessas',
            "autoIndexId" => true,
        ));

        $this->mongodb->command(array(
            "create" => 'retornos',
            "autoIndexId" => true,
        ));

        //Cria um usuário padrão
        $admin = array('username' => 'admin', 'password' => md5('admin'), 'created' => new \MongoDate());
        $this->mongodb->users->insert($admin);
        
        echo "API instalada com sucesso!";
        
        } catch (Exception $ex) {
            die($ex->getMessage()); 
        }
        
        
    }

    public function clear() {
        try{
            $this->mongodb->users->drop();
            echo "API reiniciada com sucesso!";
        } catch (Exception $ex) {
            die($ex->getMessage()); 
        }
    }

}
