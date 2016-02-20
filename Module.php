<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Users;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Users\Model\User;
use Users\Model\UserTable;

use Users\Form\RegisterFilter;

use Users\Controller\UserManagerController;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

//служба аутентификации
use Zend\Authentication\AuthenticationService;

class Module implements AutoloaderProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }
	
	// добавляем новый метод, загружающий
	// конфигурационные данные менеджера служб
	public function getServiceConfig(){
		return array(
			'abstract_factories' => array(),
			'aliases' => array(),
			'factories' => array(
				//база данных
				'UserTable' => function($sm){
					$tableGateway = $sm->get('UserTableGateway');
					$table = new UserTable($tableGateway);
					return $table;
				},
				'UserTableGateway' => function($sm){
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new User());
					return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
				},
				//формы
				'LoginForm' => function($sm){
					$form = new \Users\Form\LoginForm();
					$form->setInputFilter($sm->get('LoginFilter'));
					return $form;
				},
				'RegisterForm' => function($sm){
					$form = new \Users\Form\RegisterForm();
					$form->setInputFilter($sm->get('RegisterFilter'));
					return $form;
				},
				//Фильтры
				'LoginFilter' => function($sm){
					return new \Users\Form\LoginFilter();
				},
				'RegisterFilter' => function($sm){
					return new \Users\Form\RegisterFilter();
				},
				//Служба аутентификации
				'AuthentificationService' => function($sm){
					return new AuthenticationService();
				},
			),
			'invokables' => array(),
			'services' => array(),
			'shared' => array(),
		);
	}
}
