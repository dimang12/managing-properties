<?php
/**
 * Created by PhpStorm.
 * User: dimang12
 * Date: 10/11/14
 * Time: 11:36 AM
 */

namespace Application\Model;

class ImageTable extends SuperTableGateway{

    public  function __construct($adapter){
        parent::__construct($adapter);
    }

    public function getImage($imageId){
        $sql = $this->db->select()
                        ->from("image")
                        ->where("image_id=$imageId")
            ;
        return $this->executeQuery($sql)->toArray();
    }

    public function getDetaultImage($propertyId){
        $sql = $this->db->select()
                    ->from("image")
                    ->where("property_id = $propertyId AND img_type=1")
                    ->limit(1)
            ;

        return $this->execute($sql)->toArray();


    }
}