<?php
/**
 * Created by PhpStorm.
 * User: dimang12
 * Date: 10/10/14
 * Time: 2:31 PM
 * Create a custom table gateway
 */


namespace Application\Model;

use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\TableGateway;

class SuperTableGateway extends TableGateway{

    public $db;
    public $adapter;

    public function __construct($adapter){
        $this->adapter = $adapter;
        $this->db = new Sql($adapter);
    }

    /*
     * execute sql statement
     * @return buffer of data
     */
    public function executeQuery($sql){
        return DB::executeQuery($this->db, $sql);
    }

    /*
     * execute sql statement
     * @return last inserted id
     */
    public function execute($sql){
        $inserted = $this->db->prepareStatementForSqlObject($sql)->execute();
        return $inserted->getGeneratedValue();
    }



}