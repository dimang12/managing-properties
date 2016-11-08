<?php
/*
 *
 */
namespace Application\Controller;

use Application\Model\ImageTable;
use Application\Model\PropertyTable;
use Application\Model\ProvinceTable;
use Application\Model\UploadHandler;
use Application\Model\User;
use Zend\Json\Json;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\View\View;

class IndexController extends AbstractActionController
{

    /**
     * init function which run before call action
     * @param \Zend\Mvc\MvcEvent $e
     * @return mixed
     */
    public function onDispatch(\Zend\Mvc\MvcEvent $e)
    {
        $session  = new Container("UserLogin");
        $login = $this->params("action");

        if(empty($session->userId) && $login !="login"){
            $this->redirect()->toRoute('login');
        }

        return parent::onDispatch($e);
    }

    /**
     * @return array|ViewModel
     */
    public function indexAction()
    {
        $db = new PropertyTable($this->getAdapter());
        $properties = $db->getRecentProperty();

        $dbImage =  new ImageTable($this->getAdapter());

        /*
         * pass variable to view
         */
        return new ViewModel(array(
            "properties" => $properties
        ));

    }

    /**
     * @return ViewModel
     */
    public function detailAction(){
        $propertyId = $this->params()->fromQuery("pid");
        $page = $this->params()->fromQuery("page");

        $db = new PropertyTable($this->getAdapter());
        $property = $db->getPropertyDetail($propertyId);
        $prices = $db->getPriceByClassify($propertyId, $property);

        /*
         * pass variable to view
         */
        return new ViewModel(array(
            "property"=>$property,
            "page" => $page
        ));
    }

    /**
     * @return ViewModel
     */
    public function addnewAction(){
        if(Permission::getPermission()==false){
            $this->redirect()->toRoute("deny");
        }

        // declare params
        $dbProvince = new ProvinceTable($this->getAdapter());
        $province = $dbProvince->getAllProvice();

        /*
         * pass params to view
         */
        return new ViewModel(array(
            "province" => Json::encode($province)
        ));
    }

    /**
     * @return ViewModel
     */
    public function editAction(){
        if(Permission::getPermission()==false){
            $this->redirect()->toRoute("deny");
        }

        //get param
        $propertyId = $this->params()->fromQuery("pid");

        // declare params
        $dbProvince = new ProvinceTable($this->getAdapter());
        $dbProperty = new PropertyTable($this->getAdapter());

        $province = $dbProvince->getAllProvice();
        $property = $dbProperty->getPropertyDetail($propertyId);
        $prices = $dbProperty->getPriceOfProperty($propertyId);
        $priceTax = current($dbProperty->getPriceOfProperty($propertyId, 1));

        /*
         * add price tax
         */
        if(count($priceTax)>0){
            $property[0]["price_tax"] = $priceTax["price"];
        }

        /*
         * pass params to view
         */
        return new ViewModel(array(
            "province" => Json::encode($province),
            "property" => $property,
            "property_id" => $propertyId,
            "prices" => $prices,


        ));
    }

    /**
     * @return bool
     */
    public function removepriceAction(){
        /*
         * set layout to empty
         */
        $this->layout("layout/empty_layout");

        /*
         * variables
         */
        $id = $this->params()->fromRoute("id");

        /*
         * check if price not empty
         */
        if(!empty($id)){
            $db = new PropertyTable($this->getAdapter());
            $db->removePrice($id);
        }

        /*
         * return false to escape load view
         */
        return false;
    }

    /**
     * for user login
     */
    public function loginAction(){
        //declare params
        $session  = new Container("UserLogin");
        $db = new User($this->getAdapter());

        $userName = $this->params()->fromPost("txtUserName");
        $userPass = $this->params()->fromPost("txtPassword");


        if(!empty($userName) && !empty($userPass)){
            $user = $db->checkUser($userName, $userPass);
            if(count($user)>0){
                $session->userId = $user["user_id"];
                $session->userRole = $user["role_id"];
                $session->userName = $user["user_name"];
                $this->redirect()->toRoute("home");
            }else{

            }
        }
    }



    /*
     * view all properties
     * mapping: /all-property
     */
    public function viewallAction(){
        //get params
        $page = $this->params()->fromQuery("page",1);
        $searchText = $this->params()->fromQuery("s");
        $addedProp = $this->params()->fromQuery("pid");
        $provinceId = $this->params()->fromQuery("prov");

        //declare
        $properties = array();
        $paginator = null;
        $db = new PropertyTable($this->getAdapter());

        $properties = $db->getAllProperty($searchText);
        $provinces = $this->extractProvince($properties);

        //filter by province
        if(!empty($provinceId)){
            $properties = $this->filterByProvince($properties, $provinceId);
        }

        $paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($properties));
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(10);

        //pass variable to layout
        $this->layout()->setVariable('searchText', $searchText);

        $propSession = new Container("action_property");
        $id = $propSession->propertyId;
        $action = $propSession->action;

        //set to empty
        $propSession->propertyId="";
        $propSession->action= "";

        /*
         * pass to view
         */
        return new ViewModel(array(
            "properties" => $properties,
            "paginator" => $paginator,
            "provinces" => $provinces,
            "search" => $searchText,
            "provinceId" => $provinceId,
            "addedProp" => $addedProp,
            "page" => $page,
            "propSessionId" => $id,
            "action" =>$action
        ));
    }


    /*
     * advance search action
     * mapping: /advance-seach
     */
    public function advanceAction(){

        $dbProvince = new ProvinceTable($this->getAdapter());

        $provinces = $dbProvince->getAllProvice();

        return new ViewModel(array(
            "provinces" => $provinces
        ));
    }

    /**
     * delete image
     * @return bool
     */
    public function removeimageAction(){
        //set empty layout
        $this->layout("layout/empty_layout");

        $imgId = $this->params()->fromRoute("id");
        $db = new PropertyTable($this->getAdapter());
        $dbImg = new ImageTable($this->getAdapter());

        $curImage = current($dbImg->getImage($imgId));

        if(file_exists(getcwd()."/public/images/files/".$curImage["img_file"]))
            unlink(getcwd()."/public/images/files/".$curImage["img_file"]);

        if(file_exists(getcwd()."/public/images/files/thumbnail/".$curImage["img_file"]))
            unlink(getcwd()."/public/images/files/thumbnail/".$curImage["img_file"]);

        $db->deleteImage($imgId);
        return false;
    }


    /*
     * update default image
     */
    public function setdefaultimageAction(){
        //set empty layout
        $this->layout("layout/empty_layout");

        $imgId = $this->params()->fromQuery("id");
        $db = new PropertyTable($this->getAdapter());

        $db->setDefaultImage($imgId);
        return false;
    }


    /*
     * to logout user
     */
    public function logoutAction(){
        $session  = new Container("UserLogin");
        $session->getManager()->destroy();
        $this->redirect()->toRoute('login');
    }

    /*
     * deny action
     */
    public function denyAction(){

    }

    /*
     * upload image
     */
    public function uploadimgAction(){
        $this->layout("layout/empty_layout");
        $uploadHandler = new UploadHandler();
        return false;
    }

    /*
     * to extract the provinces in existing properties
     * return array of provinces
     */
    public function extractProvince($properties){
        $provinces = array();
        foreach($properties as $k => $v){
            $existing = false;
            if(count($provinces) > 0){
                if(array_key_exists($v["province_id"], $provinces)) $existing = true;
            }
            if($existing==false) {
                $provinces[$v["province_id"]] = $v["prov_name"];
            }
        }
        return $provinces;
    }

    /*
     * to filter properties by province id
     * return array of properties
     */
    public function filterByProvince($properties, $provinceId){
        $newProvinces = array();

        foreach($properties as $k=>$v){
            if($v["province_id"]==$provinceId){
                $newProvinces[] = $v;
            }
        }
        return $newProvinces;
    }

    /*
     * get Database adapter
     */
    public function getAdapter(){
        return $this->getServiceLocator()->get("Zend\Db\Adapter\Adapter");
    }

}
