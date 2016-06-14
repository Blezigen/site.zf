<?php
namespace Admin\Controller\Factory;

use Admin\Controller\CategoryController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CategoryControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerLocator)
    {
        $serviceLocator = $controllerLocator->getServiceLocator();

        $result = new CategoryController();
        
        $result->setDoctrine($serviceLocator->get('\Doctrine\ORM\EntityManager'));

        return $result;
    }
}