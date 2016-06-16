<?php
namespace Admin\Controller\Factory;

use Admin\Controller\ParserController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ParserControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerLocator)
    {
        $serviceLocator = $controllerLocator->getServiceLocator();
        $result = new ParserController();
        $result->setDoctrine($serviceLocator->get('\Doctrine\ORM\EntityManager'));
        return $result;
    }
    
}