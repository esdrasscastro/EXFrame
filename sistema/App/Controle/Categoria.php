<?php
/**
 * Created by Grupo B+M
 * User: Esdras Castro
 * Date: 17/08/2016
 */

namespace Controle;


use Lib\Sistema;

class Categoria extends Sistema
{
    public function index()
    {
        self::myPrivilege();
        self::header('Categorias');
        require_once(self::$htmlPath."categorias/index.phtml");
        self::footer();
    }

    private function myPrivilege()
    {
        if(parent::$privilegio != 'administrator'){
            new Error(505);
            exit;
        }
    }
}