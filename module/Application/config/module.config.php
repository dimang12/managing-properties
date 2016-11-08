<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'detail' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/detail',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'detail',
                    ),
                ),
            ),
            'addnew' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/addnew',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'addnew',
                    ),
                ),
            ),
            'uploadimg' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/uploadimg',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'uploadimg',
                    ),
                ),
            ),
            'removeimage' => array (
                'type' => 'segment',
                'options' => array (
                    'route' => '/removeimage[/:id]',
                    'defaults' => array (
                        'controller' => 'Application\Controller\Index',
                        'action' => 'removeimage'
                    )
                )
            ),
            'edit' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/edit',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'edit',
                    ),
                ),
            ),
            'login' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/login',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'login',
                    ),
                ),
            ),
            'logout' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/logout',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'logout',
                    ),
                ),
            ),
            'all-property' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/all-property',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'viewall',
                    ),
                ),
            ),
            'advance-search' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/advance-search',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'advance',
                    ),
                ),
            ),
            'user-management' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/user-management[/:id]',
                    'constraints' => array(
                        'id' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\UserManagement',
                        'action'     => 'index',
                    ),
                ),
            ),
            'user-add' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/user-add',
                    'defaults' => array(
                        'controller' => 'Application\Controller\UserManagement',
                        'action'     => 'add',
                    ),
                ),
            ),
            'user-edit' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/user-edit[/:id]',
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\UserManagement',
                        'action'     => 'edit',
                    ),
                ),
            ),
            'property' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/property[/:id]',
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Property',
                    ),
                ),
            ),

            'user' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/user[/:id]',
                    'constraints' => array(
                        'id' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\User'
                    )
                )
            ),

            'deny' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/deny[/:ms]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'deny'
                    )
                )
            ),

            'index' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/index[/][:action][/][:id]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'index'
                    )
                )
            ),

            'report' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/report[/:action[/:option]]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Report',
                        'action' => 'index'
                    )
                )
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Property' => 'Application\Controller\PropertyController',
            'Application\Controller\User' => 'Application\Controller\UserController',
            'Application\Controller\UserManagement' => 'Application\Controller\UserManagementController',
            'Application\Controller\Permission' => 'Application\Controller\Permission',
            'Application\Controller\Share' => 'Application\Controller\ShareController',
            'Application\Controller\Report' => 'Application\Controller\ReportController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array('ViewJsonStrategy')
    ),
);
