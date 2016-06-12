<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CategoryController extends AbstractActionController
{
    /**
     * @var array
     */
    private $_entity_manager;

    /**
     * @param $applicationDetails
     */
    public function __construct(\Doctrine\ORM\EntityManager $entity_manager)
    {
        $this->_entity_manager = $entity_manager;
    }

    public function indexAction()
    {
        //var_dump($this->_entity_manager);
//        return new ViewModel([
//            'author' => $this->applicationDetails['application_author'],
//        ]);
    }
}
