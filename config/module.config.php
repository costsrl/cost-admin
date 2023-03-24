<?php
namespace CostAdmin;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;


return array(
    'controllers' => array(
        'invokables' => array(
            //'CostAdmin\Controller\Index' => 'CostAdmin\Controller\IndexController'
        ),
        'factories'=>[
            Controller\IndexController::class => 'CostAdmin\Controller\Factory\IndexControllerFactory'
        ]
    ),
    'router' => array(
        'routes' => array(
            'admin-application' => array(
                'type' => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route' => '/admin',
                    'defaults' => array(

                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'CostAdmin\Controller',
                        'controller' =>  Controller\IndexController::class,
                        'action' => 'index'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(

                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:xxxx[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                            ),
                            'defaults' => array()
                        )
                    )
                )
            ),
            'admin-resource' => array(
                'type' => Literal::class,
                'options' => array(
                    'route' => '/admin-resource',
                    'defaults' => array(
                        '__NAMESPACE__' => 'CostAdmin\Controller',
                        'controller' => Controller\IndexController::class,
                        'action' => 'resource',
                    ),
                ),
            ),
            'admin-role' => array(
                'type' => Literal::class,
                'options' => array(
                    'route' => '/admin-role',
                    'defaults' => array(
                        '__NAMESPACE__' => 'CostAdmin\Controller',
                        'controller' => Controller\IndexController::class,
                        'action' => 'role',
                    ),
                ),
            ),
            'admin-user' => array(
                'type' => Literal::class,
                'options' => array(
                    'route' => '/admin-user',
                    'defaults' => array(
                        '__NAMESPACE__' => 'CostAdmin\Controller',
                        'controller' => Controller\IndexController::class,
                        'action' => 'user',
                    ),
                ),
            ),
            'admin-password' => array(
                'type' => Literal::class,
                'options' => array(
                    'route' => '/userpasswd',
                    'defaults' => array(
                        '__NAMESPACE__' => 'CostAdmin\Controller',
                        'controller' => Controller\IndexController::class,
                        'action' => 'userpasswd',
                    ),
                ),
            ),
            'admin-permission' => array(
                'type' => Literal::class,
                'options' => array(
                    'route' => '/admin-permission',
                    'defaults' => array(
                        '__NAMESPACE__' => 'CostAdmin\Controller',
                        'controller' => Controller\IndexController::class,
                        'action' => 'permission',
                    ),
                ),
            ),
            'admin-translation' => array(
                'type' => Literal::class,
                'options' => array(
                    'route' => '/admin-translation',
                    'defaults' => array(
                        '__NAMESPACE__' => 'CostAdmin\Controller',
                        'controller' => Controller\IndexController::class,
                        'action' => 'translation',
                    ),
                ),
            ),
            'admin-menu' => array(
                'type' => Literal::class,
                'options' => array(
                    'route' => '/admin-menu',
                    'defaults' => array(
                        '__NAMESPACE__' => 'CostAdmin\Controller',
                        'controller' => Controller\IndexController::class,
                        'action' => 'menu',
                    ),
                ),
            ),
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'CostAdmin' => __DIR__ . '/../view'
        )
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__.'_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Model/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__.'\Model\Entity' =>  __NAMESPACE__.'_driver'
                ),
            ),
        ),
    ),
);
