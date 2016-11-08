<?php
/**
 * Created by PhpStorm.
 * User: dimang12
 * Date: 10/31/14
 * Time: 11:33 AM
 */

namespace Application\Controller;

use Application\Model\User;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

class UserManagementController extends MasterController
{
    public function __construct()
    {

    }

    /*
     * display all users
     * return viewModel
     */
    public function indexAction(){

        if(Permission::getPermission()==false){
            $this->redirect()->toRoute("deny");
        }

        //declare variables
        $db = new User($this->getAdapter());
        $page = $this->params()->fromQuery("page");


        $addUser = new Container("action_user");
        $id = $addUser->user;
        $action = $addUser->action;

        //set to empty
        $addUser->user="";
        $addUser->action= "";

        $users = $db->getAllUser();

        return new ViewModel(array(
            "users" => $users->toArray(),
            "id" => $id,
            "action" => $action
        ));
    }

    /*
     * edit user
     */
    public function editAction()
    {
        if(Permission::getPermission()==false){
            $this->redirect()->toRoute("deny");
        }

        //declare variable
        $db = new User($this->getAdapter());
        $id = $this->params()->fromRoute("id");

        $user = $db->getUser($id);
        $roles = $db->getAllRole();
        return new ViewModel(array(
            "user" => $user,
            "roles" => $roles
        ));
    }

    /*
     * add user
     */
    public function addAction(){

        if(Permission::getPermission()==false){
            $this->redirect()->toRoute("deny");
        }

        //declare variable
        $db = new User($this->getAdapter());
        $roles = $db->getAllRole();

        return new ViewModel(array(
            "roles" => $roles,
            "active" => "user-add"
        ));
    }
}