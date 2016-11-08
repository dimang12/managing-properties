<?php
/**
 * Created by PhpStorm.
 * User: dimang12
 * Date: 11/1/14
 * Time: 9:34 AM
 */

namespace Application\Controller;

use Application\Model\User;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Session\Container;
use Zend\View\Model\JsonModel;

class UserController extends AbstractRestfulController
{
    protected $dbUser;


    /*
     * initialize UserController
     *
     */
    public function __construct(){
        //$this->dbUser = new User($this->getAdapter());

    }


    /*
     * get all user
     */
    public function getList(){
        return new JsonModel(
            array(
                //'data' => array("a"=>1, "b"=>2)
            )
        );
    }

    /*
     * create new user
     * return inserted id
     */
    public function create($data){
        $dbUser = new User($this->getAdapter());
        $insertId = $dbUser->saveUser($data);

        $addUser = new Container('action_user');
        $addUser->user = $insertId;
        $addUser->action = "create";

        return new JsonModel(array(
            "id"=>$insertId
        ));
    }

    /*
     * update user
     * return JsonModel data
     */
    public function update($id, $data){
        $dbUser = new User($this->getAdapter());
        if(empty($data["user_pass"])) unset($data["user_pass"]);
        $dbUser->updateUser($id, $data);

        $addUser = new Container('action_user');
        $addUser->user = $id;
        $addUser->action = "update";

        return new JsonModel(
            array("data"=>$data)
        );
    }

    /*
     * delete user
     */
    public function delete($id){
        $user = new Container("action_user");
        $user->action = "delete";
        $dbUser = new User($this->getAdapter());
        $dbUser->deleteUser($id);
        return new JsonModel(array());
    }

    /*
     * get adapter
     */
    public function getAdapter(){
        return $this->getServiceLocator()->get("Zend\Db\Adapter\Adapter");
    }


}