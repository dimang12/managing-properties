<?php
/**
 * Created by PhpStorm.
 * User: dimang12
 * Date: 10/9/14
 * Time: 4:33 PM
 */

namespace Application\Model;


use Zend\Db\Sql\Sql;

class User extends SuperTableGateway{
    /*
     * declare class properties
     */


    /*
     * initialize class by construction
     */
    public function __construct($adapter){
        parent::__construct($adapter);
    }


    /*
     * get all users
     */
    public function getAllUser(){
        $sql = $this->db->select()
                    ->from("user")
                    ->join("role", "user.role_id = role.role_id")
                ;
        return $this->executeQuery($sql);
    }

    /*
     * get user by id
     * return current array of data
     */
    public function getUser($id){
        $sql = $this->db->select()
                        ->from("user")
                        ->join("role", "user.role_id = role.role_id")
                        ->where("user.user_id = $id")
                ;
        return current($this->executeQuery($sql)->toArray());
    }


    /*
     * check user whether existing or not
     * @return record of user
     */
    public function checkUser($userName, $password){
        $sql = $this->db->select()
                        ->from("user")
                        ->where("user_name = '$userName' AND user_pass='$password'")
            ;
        $res = DB::executeQuery($this->db, $sql);

        if ($res->count()>0){
            return current($res->toArray());
        }
        else return array();
    }

    /*
     * get all role
     */
    public function getAllRole(){
        $sql = $this->db->select()
                        ->from("role")
            ;
        return DB::executeQuery($this->db, $sql);
    }

    /*
     * create new user
     * return inserted id
     */
    public function saveUser($data){
        $curDate = date('Y-m-d H:i:s');
        $data["user_created_date"] = $curDate;
        $data["user_last_login"] = $curDate;

        $sql = $this->db->insert("user")->values($data);
        return $this->execute($sql);
    }


    /*
     * update user
     * no return value
     */
    public function updateUser($id, $data){
        $this->execute($this->db->update("user")->set($data)->where("user_id = $id"));
    }


    /*
     * delete user
     */
    public function deleteUser($id){
        $this->execute($this->db->delete("user")->where("user_id = $id"));
    }
}