<?php
	
/**
 * Created by Movementes.com
 * User: Esdras Castro
 * Date: 18/03/2016
 * Time: 11:51
 * Project: dhire
 * File: Session.php
 */
namespace Lib;

abstract class Session {

    public static function create($index='', $value=''){
        if(is_array($index))
            foreach($index as $i=>$v)
                $_SESSION[ $i ] = $v;
        else
            if(!empty($index) and !empty($value)) $_SESSION[ $index ] = $value;
    }

    public static function fatherExists($index=''){
        if(!empty($index))
            return isset($_SESSION[$index]);

        return false;
    }

    public static function get($index='', $clear=false){
        if(!empty($index)){
            if(isset($_SESSION[$index])){
                $session = $_SESSION[$index];
                if($clear)unset( $_SESSION[$index]);
                return $session;
            }
        }
        return '';
    }
}