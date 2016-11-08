<?php
/**
 * Created by PhpStorm.
 * User: dimang12
 * Date: 12/8/14
 * Time: 2:39 PM
 */
namespace Application\Controller;

use Application\Model\PropertyTable;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

class ReportController extends MasterController
{
    /**
     * @return array|ViewModel
     */
    public function indexAction(){

        /*
         * declare variable
         */
        $page = $this->params()->fromQuery("page",1);
        $db = new PropertyTable($this->getAdapter());
        $option = $this->params()->fromRoute("option");
        $option = (!empty($option))?1:0;

        /*
         *
         */
        $properties = $db->getAllProperty("", $option);
        $paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($properties));
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(20);

        /*
         * return value
         */
        return new ViewModel(array(
            "paginator" => $paginator,
            "option" => $option,
            "page" => $page
        ));
    }
}
?>

