<?php
/**
 * Created by PhpStorm.
 * User: dimang12
 * Date: 10/20/14
 * Time: 2:47 PM
 */
namespace Application\Controller;

use Application\Model\PropertyTable;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Session\Container;
use Zend\View\Model\JsonModel;

class PropertyController extends AbstractRestfulController{

    /**
     * @return mixed|JsonModel
     */
    public function getList()
    {
        $arr = array("a"=>"A","b"=>"B");
        return new JsonModel(array(
            'data' => $arr
        ));
    }

    /**
     * get property detail
     * return json model value
     * @param mixed $id
     * @return mixed|JsonModel
     */
    public function get($id)
    {
        $arr = array("a"=>"A","b"=>"B");
        return new JsonModel(array(
            'data' => $arr
        ));
    }

    /**
     * create new property
     * @param mixed $data
     * @return mixed|JsonModel
     */
    public function create($data)
    {
        //save to property
        $insertedId = $this->saveProperty($data);

        //save prices
        $this->savePrice($data, $insertedId);

        //save swot
        $this->saveSwot($data, $insertedId);

        //save image
        if(isset($data["images"])){
            $this->saveImage($data["images"], $insertedId);
        }
//
        $property = new Container('action_property');
        $property->propertyId = $insertedId;
        $property->action = "create";

        /*
         * pass json variable
         */
        return new JsonModel(
            array('id' => $insertedId)
//            array($data)
        );
    }

    /**
     * update property
     * @param mixed $id
     * @param mixed $data
     * @return mixed|JsonModel
     */
    public function update($id, $data)
    {
        // update property
        $this->updateProperty($id, $data);
        //update price
        $this->updatePrice($id, $data);
        //update swot
        $this->updateSwot($id, $data);

        if(isset($data["images"]) && count($data["images"])>0){
            $this->saveImage($data["images"], $id);
        }

        $property = new Container('action_property');
        $property->propertyId = $id;
        $property->action = "update";


        return new JsonModel(
            array($data)
        );
    }

    /**
     * delete property
     * @param mixed $id
     * @return mixed|JsonModel
     */
    public function delete($id)
    {
        $db = new PropertyTable($this->getAdapter());
        $db->deleteProperty($id);
        return new JsonModel(array(

        ));
    }


    /*
     * save into property
     * return integer of last inserted id
     */
    public function saveProperty($data){

        //declare params
        $dataColumns = array(
            "province_id", "prop_name","prop_code", "prop_location", "prop_address", "prop_dimension",
            "prop_size", "prop_road_type", "prop_north", "prop_south", "prop_east", "prop_west", "prop_polygon",
            "prop_description","prop_vision","polygon_size"

        );
        $dataValues = array();
        $insertedId = null;
        $db = new PropertyTable($this->getAdapter());

        foreach($dataColumns as $k => $v){
            $dataValues[$v] = (isset($data[$v]))? $data[$v] : "";
        }

        $dataValues["prop_polygon"] = (isset($data["prop_polygon"]))? json_encode($data["prop_polygon"]):"";

        $insertedId = $db->saveProperty($dataValues);
        return  $insertedId;
    }


    /*
     * save price
     * argument $data is the posted data from client
     * no return value
     */
    public function savePrice($data, $propertyId){
        //declare params
        $db = new PropertyTable($this->getAdapter());
//        $priceOriginal = isset($data["price_original"])?$data["price_original"]:"";
        $priceTax = isset($data["price_tax"])?$data["price_tax"]:"";
//        $priceLatest = isset($data["price_latest"])?$data["price_latest"]:"";
//
//        $dataValues = array(
//            "property_id" => $propertyId,
//            "price_original" => $priceOriginal,
//            "price_tax" => $priceTax,
//            "price_latest" => $priceLatest,
//            "price_market" => $data["price_market"]
//        );
//
//        $db->savePrice($dataValues);
        $db->savePrice(array("property_id" =>$propertyId, "price"=>$priceTax, "year"=>0, "price_type"=>1));
        $prices = $data["pricing"];
        foreach($prices as $k=>$v){
            $db->savePrice(array("property_id" =>$propertyId, "price"=>$v["price"], "year"=>$v["year"], "price_type"=>2));
        }
    }

    /*
     * save swot
     * argument $data is the posted data from client
     * argument $propertyId is the inserted property id
     * no return value
     */
    public function saveSwot($data, $propertyId){
        //declare params
        $db = new  PropertyTable($this->getAdapter());

        if(isset($data["swot_strength"]) || isset($data["swot_weakness"]) || isset($data["swot_opportunity"]) || isset($data["swot_threat"])){
            $dataValue = array(
                "property_id" => $propertyId,
                "swot_strength" => $data["swot_strength"],
                "swot_weakness" => $data["swot_weakness"],
                "swot_opportunity" => $data["swot_opportunity"],
                "swot_threat" => $data["swot_threat"]
            );

            $db->saveSwot($dataValue);
        }

    }

    /*
     *
     */
    public function saveImage($data, $propertyId){
        $db =  new PropertyTable($this->getAdapter());


        foreach($data as $k=>$v){
            $imgType = ($k==0)?1:0;
            $values = array(
                "property_id"=> $propertyId,
                "img_file"=> $v,
                "img_type" => $imgType
            );

            $db->saveImage($values);
        }


    }

    /*
     * update property
     * no return value
     */
    public function updateProperty($id, $data){
        $dataColumns = array(
            "province_id", "prop_name","prop_code", "prop_location", "prop_address", "prop_dimension",
            "prop_size", "prop_road_type", "prop_north", "prop_south", "prop_east", "prop_west", "prop_polygon",
            "prop_description","prop_vision","polygon_size"
        );


        $dataValues = array();
        $db = new PropertyTable($this->getAdapter());

        foreach($dataColumns as $k => $v){
            $dataValues[$v] = (isset($data[$v]))? $data[$v] : "";
        }

        $dataValues["prop_polygon"] = (isset($data["prop_polygon"]))? json_encode($data["prop_polygon"]):"";

        $db->updateProperty($id, $dataValues);
    }

    /*
     * update price
     * no return value
     */
    public function updatePrice($id, $data){
        $db = new PropertyTable($this->getAdapter());
        $prices = $data["pricing"];

        foreach($prices as $k=>$v){
            $db->updatePrice($v["price_id"], array("price"=>$v["price"], "year"=>$v["year"], "property_id"=>$id));
        }
    }


    /*
     * update swot
     * no return value
     */
    public function updateSwot($id, $data){
        //declare variables
        $dataColumns = array("swot_strength","swot_weakness", "swot_opportunity", "swot_threat");
        $dataValues = array();
        $db = new PropertyTable($this->getAdapter());

        foreach($dataColumns as $k => $v){
            $dataValues[$v] = (isset($data[$v]))? $data[$v] : "";
        }

        $db->updateSwot($id, $dataValues);
    }


    public function getAdapter(){
        return $this->getServiceLocator()->get("Zend\Db\Adapter\Adapter");
    }

}