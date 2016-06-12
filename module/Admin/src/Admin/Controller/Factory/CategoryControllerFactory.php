<?php
namespace Admin\Controller\Factory;

use Admin\Controller\CategoryController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CategoryControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerLocator)
    {
        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $serviceLocator = $controllerLocator->getServiceLocator();

        return new CategoryController(
            //\Doctrine\ORM\EntityManager::
            //$serviceLocator->get('config')['application_details']
            $serviceLocator->get('\Doctrine\ORM\EntityManager')
        );
    }
}