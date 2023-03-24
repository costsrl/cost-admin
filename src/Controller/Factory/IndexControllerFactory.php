<?php
namespace CostAdmin\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use CostAuthentication\Controller\IndexController;

class IndexControllerFactory implements FactoryInterface
{
    /*
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return Translator
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $translator      = $container->get('MvcTranslator');
        $indexController = new \CostAdmin\Controller\IndexController();
        $indexController->setServiceLocator($container);
        $indexController->setTranslator($translator);
        return $indexController;
    }
    
}

