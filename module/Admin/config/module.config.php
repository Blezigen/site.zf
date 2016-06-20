<?php

namespace Admin;

return array(
    'doctrine' => array(
        'driver' => array(
            // defines an annotation driver with two paths, and names it `my_annotation_driver`
            'admin_entity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__.'/../src/Admin/Entity',
                ),
            ),
            'orm_default' => array(
                'drivers' => array(
                    // register `my_annotation_driver` for any entity under namespace `My\Namespace`
                    'Admin\Entity' => 'admin_entity'
                )
            )
        )
    ),
    'router' => array(
        'routes' => array(
            'admin' => array(
                'type' => 'segment', // отключает подстановки
                'options' => array(
                    'route'    => '/admin/',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
//                    'category' => array(
//                        'type'    => 'segment',
//                        'options' => array(
//                            'route'    => 'category/[:action/][:id/]',
//                            'defaults' => array(
//                                'controller' => \Admin\Controller\CategoryController::class,
//                                'action'     => 'index',
//                            ),
//                        ),
//                    ),
                    'parser' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => 'parser/[:action]',
                            'defaults' => array(
                                'controller' => \Admin\Controller\ParserController::class,
                                'action'     => 'index',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),

    'controllers' => array(
        'factories'    => array(
            \Admin\Controller\CategoryController::class    => \Admin\Controller\Factory\CategoryControllerFactory::class,
            \Admin\Controller\ParserController::class  => \Admin\Controller\Factory\ParserControllerFactory::class,
        ),
        'invokables' => array(
            'Admin\Controller\Index' => 'Admin\Controller\IndexController',
            //'Admin\Controller\Category' => \Admin\Controller\CategoryController::class,
            //'ParserController' => \Admin\Controller\ParserController::class,
        ),
    ),

    'view_manager' => array(
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'parser/getFirms'           => __DIR__ . '/../view/parser/getfirms.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
