<?php
/**
 * Created by PhpStorm.
 * User: dimang12
 * Date: 10/29/14
 * Time: 9:48 PM
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class MasterController extends AbstractActionController
{

    public function __construct(){

    }

    public function getAdapter(){
        return $this->getServiceLocator()->get("Zend\Db\Adapter\Adapter");
    }
}