<?php
/**
 * Created by PhpStorm.
 * User: dimang12
 * Date: 10/22/14
 * Time: 5:15 PM
 */

namespace Application\Model;

class ProvinceTable extends SuperTableGateway{

    public function __construct($adapter){
        parent::__construct($adapter);
    }

    /*
     * get all provinces
     * return array of data
     */
    public function  getAllProvice(){
        $sql = $this->db->select()
                        ->from("province")
                        ->order("prov_ordering")
            ;

        return $this->executeQuery($sql)->toArray();
    }


}