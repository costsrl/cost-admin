<?php
namespace CostAdmin;

use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\ModuleRouteListener;
use Laminas\Console\Adapter\AdapterInterface as Console;
use Laminas\Console\Request as ConsoleRequest;

class Module
{

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }


    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $app = $e->getApplication ();
        $sm = $app->getServiceManager ();
        $eventManager       = $app->getEventManager ();
        $shareEventManager  = $eventManager->getSharedManager (); // The shared event manager


        /**
         * funzione richiamabile per gestire modifiche prima del setting
         */
        $shareEventManager->attach('Zf2datatable\Form\EventsForm', 'pre.setData', function ($event) use ($sm) {
            $target = $event->getTarget ();
            $params = $event->getParams();
            if(isset($params['__PASSWORD_NEW__'])){
                $params['password'] = $params['__PASSWORD_NEW__'];
                $event->stopPropagation();//stopPropagation
            }
            return $params;
        });

        /**
         * funzione richiamabile per gestire modifiche prima dell'update
         */
        $shareEventManager->attach('Zf2datatable\DataSource\AbstractDataSource', 'pre.update', function ($event) use ($sm) {
            $target = $event->getTarget ();
            $params = $event->getParams();

            $static_salt = $sm->get('Config')['static_salt'];
            $moduleManager          = $sm->get('ModuleManager');
            $loadedModules          = $moduleManager->getLoadedModules();

            $contextParams = array();
            if($event instanceof \Zf2datatable\Event\Zf2datatableEvent){
                $contextParams = $event->getContext();
            }


            if ($target instanceof \CostAuthentication\Entity\User && $contextParams['action'] == 'userpasswd') {
                if (array_key_exists('ZfcUser', $loadedModules)) {
                    $zfc_user_option = $sm->get('zfcuser_module_options');
                    $bcrypt = new Bcrypt();
                    $bcrypt->setCost($zfc_user_option->getPasswordCost());
                    $passwordCrypt = $bcrypt->create($target->getPassword());

                } else
                    $passwordCrypt = md5($static_salt . $contextParams["postData"] ["__PASSWORD_NEW__"] . $target->getPasswordSalt());

                $target->setPassword($passwordCrypt);
                $event->stopPropagation();
            }

            return $target;
        });


        $shareEventManager->attach('Zf2datatable\DataSource\AbstractDataSource', 'pre.insert', function ($event) use ($sm) {
            $target = $event->getTarget();
            $params = $event->getParams();

            $static_salt = $sm->get('Config')['static_salt'];
            $moduleManager = $sm->get('ModuleManager');
            $loadedModules = $moduleManager->getLoadedModules();
            $contextParams = array();
            if ($event instanceof \Zf2datatable\Event\Zf2datatableEvent) {
                $contextParams = $event->getContext();
            }


            if ($target instanceof \CostAuthentication\Entity\User && $contextParams['action'] == 'user') {

                if (array_key_exists('ZfcUser', $loadedModules)) {
                    $zfc_user_option = $sm->get('zfcuser_module_options');
                    $bcrypt = new Bcrypt();
                    $bcrypt->setCost($zfc_user_option->getPasswordCost());
                    $passwordCrypt = $bcrypt->create($target->getPassword());
                } else {
                    if (isset($contextParams['postData']['__PASSWORD_NEW__'])) {
                        $target->setPassword($contextParams['postData']['__PASSWORD_NEW__']);
                    }

                    $sRandomSalt = \CostAuthentication\Service\Service\GenerateSalt::generateDynamicSalt();
                    $target->setPasswordSalt($sRandomSalt);
                    $passwordCrypt = md5($static_salt . $target->getPassword() . $sRandomSalt);
                }

                $target->setPassword($passwordCrypt);
                $event->stopPropagation();
            }

            return $target;
        });

    }


}