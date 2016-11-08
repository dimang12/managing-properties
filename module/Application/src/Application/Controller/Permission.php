<?php
/**
 * Created by PhpStorm.
 * User: dimang12
 * Date: 11/3/14
 * Time: 2:47 PM
 */

namespace Application\Controller;

use Zend\ServiceManager\Di\DiServiceFactory;
use Zend\Session\Container;
use Zend\ServiceManager\ServiceManager;

class Permission extends MasterController
{
    public static function getPermission(){
        $session = new Container("UserLogin");
        if($session->userRole == 1){
            return true;
        }else{
            return false;
        }
    }

}