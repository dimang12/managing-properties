<?php
/**
 * Created by PhpStorm.
 * User: dimang12
 * Date: 10/9/14
 * Time: 4:24 PM
 */

namespace Application\Model;


use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;

class DB {
    public function __construct(){}

    /*
     * for execute sql statement
     * @return buffer of resultset
     * static function, mean user could call this function without create new object
     */
    public static function executeQuery(Sql $db, $query){
        $statement = $db->prepareStatementForSqlObject($query);
        $rs = new ResultSet();
        return $rs->initialize($statement->execute())->buffer();
    }

    public static function exectue(Sql $db, $query){

    }
} 