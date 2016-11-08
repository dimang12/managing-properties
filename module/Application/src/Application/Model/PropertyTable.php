<?php
/**
 * Created by PhpStorm.
 * User: dimang12
 * Date: 10/10/14
 * Time: 2:25 PM
 */

namespace Application\Model;

use Zend\Db\Sql\Expression;

class PropertyTable extends SuperTableGateway{


    public function __construct($adapter){
        parent::__construct($adapter);
    }


    /*
     * get recently properties
     * @return array of data
     */
    public function getRecentProperty(){
        $sql  = $this->db->select()
                         ->from(array("p"=>"property"))
                         ->join("image", new Expression("image.property_id = p.property_id AND image.img_type = 1"),array("img_file"),"LEFT")
//                         ->join("price", "price.property_id = p.property_id")
                         ->join("price", "price.property_id = p.property_id",array("max_year"=>new Expression("MAX(year)"),"price_market"=>"price"))
                         ->join(array("pro"=>"province"), "pro.province_id = p.province_id")
                         ->order("p.property_id DESC")
//                         ->where("image.img_type=1")
                         ->group("p.property_id")
                         ->where('p.is_delete=0')
                         ->limit(10)
                    ;
        $res  = $this->executeQuery($sql);
        return $res->toArray();
    }

    /*
     * get property detail
     */
    public function getPropertyDetail($propertyId){
        $sql  = $this->db->select()
            ->from(array("p"=>"property"))
            ->join("image", "image.property_id = p.property_id",array("img_file","img_type","image_id"),"LEFT")
//            ->join("price", "price.property_id = p.property_id", array("price_original", "price_tax", "price_latest", "price_market"))
//            ->join("price", "price.property_id = p.property_id" /*array("price_original", "price_tax", "price_latest", "price_market")*/)
            ->join(array("prov"=>"province"), "prov.province_id = p.province_id")
            ->join("swot","swot.property_id=p.property_id",array("swot_strength","swot_weakness", "swot_opportunity", "swot_threat"), "LEFT")
            ->order("p.property_id DESC")
            ->where("p.property_id=" .$propertyId)
            ->where("p.is_delete=0")
//            ->group("p.property_id")

        ;
        return $this->executeQuery($sql)->toArray();
    }

    /**
     * @param $propertyId
     * @return mixed
     */
    public function getPriceOfProperty($propertyId, $type=2){
        $sql = $this->db->select()
                    ->from("price")
                    ->where("property_id={$propertyId}")
                    ->where("price_type=$type")
            ;
        return $this->executeQuery($sql)->toArray();
    }

    public function getPriceByClassify($propertyId, &$property){
        /*
         * declare params
         */
        $prices = array();


        /*
         * get original price
         */
        $sqlO = $this->db->select()
            ->from("price")
            ->where("property_id={$propertyId}")
            ->order("year ASC")->limit(1)
        ;
        $res = current($this->executeQuery($sqlO)->toArray());
        $property[0]["price_original"] = $res["price"];

        /*
         * get market price
         */
        $sqlM = $this->db->select()
            ->from("price")
            ->where("property_id={$propertyId}")
            ->order("year DESC")
        ;
        $res = current($this->executeQuery($sqlM)->toArray());
        $property[0]["price_market"] = $res["price"];
        /*
         * get property price with  government's tax
         */
        $sqlT = $this->db->select()
            ->from("price")
            ->where("property_id={$propertyId}")
            ->where("price_type=1")
        ;
        $res = current($this->executeQuery($sqlT)->toArray());
        $property[0]["price_tax"] = $res["price"];

        /*
         * get last year price
         */
        $sqlL = $this->db->select()
            ->from("price")
            ->where("property_id={$propertyId}")
            ->order("price DESC")
            ->limit(2)
        ;
        $res = $this->executeQuery($sqlL)->toArray();
        $lastYear = end($res);
        $property[0]["price_latest"] = $lastYear["price"];
        /*
         * return array of pricing
         */
//        return $prices;

    }


    /**
     * remove price by price id
     * @param $id
     */
    public function removePrice($id){
        /*
         * check if id is empty
         */
        if(!empty($id)){
            $sql = $this->db->delete("price")->where("price_id={$id}");
            $this->execute($sql);
        }
    }

    /*
     * search property
     * @return array of data
     */
    public function searchProperty($searchText){
        $sql  = $this->db->select()
                         ->from(array("p"=>"property"))
                         ->join("image", new Expression("image.property_id = p.property_id AND image.img_type = 1"),array("img_file"),"LEFT")
                         ->where("p.prop_name LIKE %$searchText%")
                         ->where("p.is_delete=0")
        ;
        $res = $this->executeQuery($sql);
        return $res->toArray();
    }


    /*
     * get all properties
     * @return array of data
     */
    public function getAllProperty($search = "", $isDelete=0){
        $sql  = $this->db->select()
            ->from(array("p"=>"property"))
            ->join("image", new Expression("image.property_id = p.property_id AND image.img_type = 1"),array("img_file"),"LEFT")
//            ->join("price", "price.property_id = p.property_id")
            ->join("price", "price.property_id = p.property_id",array("max_year"=>new Expression("MAX(year)"),"price_market"=>"price"))
            ->join(array("prov"=>"province"),"prov.province_id = p.province_id")
            ->where("is_delete=$isDelete")
            ->group("p.property_id")
            ->order("p.property_id DESC")
        ;
//        print_r(str_replace('"', "", $sql->getSqlString()));

        //check on search or not
        if(!empty($search)) $sql->where("p.prop_name LIKE '%$search%' OR prov_name LIKE '%$search%'");

        $res = $this->executeQuery($sql);
//        print_r($res->toArray());
        return $res->toArray();
    }


    /*
     * inserted properties
     * @return last inserted id
     */
    public function saveProperty($values){
        $sql = $this->db->insert("property")->values($values);
        return $lastInserted = $this->execute($sql);
    }


    /*
     * insert prices
     * no return value
     */
//    public function savePrice($values){
//        $sql = $this->db->insert("price")->values($values);
//        $this->execute($sql);
//    }

    /**
     * save price
     * @param $value
     */
    public function savePrice($value){
        $sql = $this->db->insert("price")->values($value);
        $this->execute($sql);
    }

    /**
     * update price
     * @param $values array of price record
     * @return void
     */
    public function updatePrices($values){
        $sql = $this->db->update("price")->set($values);
        $this->execute($sql);
    }

    /*
     * insert swot
     * no return value
     */
    public function saveSwot($values){
        $sql = $this->db->insert("swot")->values($values);
        $this->execute($sql);
    }

    /*
     * save image
     * no return value
     */
    public function saveImage($values){
        $sql = $this->db->insert("image")->values($values);
        $this->execute($sql);
    }

    /*
     * update property
     * no return value
     */
    public function updateProperty($id, $values){
        $sql = $this->db->update("property")->set($values)->where("property_id = $id");
        $this->execute($sql);
    }

    /*
     * update price table
     * no return value
     */
    public function updatePrice($id, $values){
        if(!empty($id)){
            $sql = $this->db->update("price")->set($values)->where("price_id = $id");
        }else{
            $values["price_type"]=2;
            $sql = $this->db->insert("price")->values($values);
        }
        $this->execute($sql);
    }

    /*
     * update swot table
     * no return value
     */
    public function updateSwot($id, $values){
        $checkSql = $this->db->select()->from("swot")->where("property_id = $id");
        $res = $this->executeQuery($checkSql)->toArray();
        $sql = null;

        if(count($res) > 0){
            $sql = $this->db->update("swot")->set($values)->where("property_id=$id");
        }else{
            $values["property_id"] = $id;
            $sql = $this->db->insert("swot")->values($values);
        }

        $this->execute($sql);

    }

    /*
     * delete property
     * no return value
     */
    public function deleteProperty($id){
//        $this->execute($this->db->delete("property")->where("property_id=$id"));
        $this->execute($this->db->update("property")->set(array("is_delete"=>1))->where("property_id=$id"));
    }

    /*
     * delete property
     */
    public function deleteImage($imageId){
        $this->execute($this->db->delete("image")->where("image_id=$imageId"));
    }

    /*
     * update default image
     */
    public function setDefaultImage($imageId){
        if(!empty($imageId)){
            //get property id
            $sql = $this->db->select()->from("image")->where("image_id=$imageId");
            $res = current($this->executeQuery($sql)->toArray());
            $propertyId = $res["property_id"];

            //update all images to normal image
            $this->execute($this->db->update("image")->set(array("img_type"=>0))->where("property_id=$propertyId"));

            //set an image to be default image
            $this->execute( $this->db->update("image")->set(array("img_type"=>1))->where("image_id = $imageId"));
        }
    }

}