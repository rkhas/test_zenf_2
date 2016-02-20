<?php
return array(
	'controllers' => array(
		'invokables' => array(
			'Users\Controller\Index' => 'Users\Controller\IndexController',
			'Users\Controller\Register' => 'Users\Controller\RegisterController',
			'Users\Controller\Login' => 'Users\Controller\LoginController',
			'Users\Controller\UserManager' => 'Users\Controller\UserManagerController',
		)
	),
	'view_manager' => array(
		'template_path_stack' => array(
			'users' => __DIR__.'/../view',
		),
	),
	'router' => array(
		'routes' => array(
			'users' => array(
				'type' => 'Literal',
				'options' => array(
					//Указать в соответствии с Вашим модулем
					'route' => '/users',
					'defaults' => array(
						//Задать это значение в соответствии с пространством имен 
						//в котором находятся Ваши контроллеры
						'__NAMESPACE__' => 'Users\Controller',
						'controller' => 'Index',
						'action' => 'index',
					),
				),
				'may_terminate' => true,
				'child_routes' => array(
					// Это маршрут, предлагаемый по умолчанию. Его разумно
					// использовать при разработке модуля;
					// с появлением определенности в отношении
					// маршрутов для модуля, возможно, появится
					// смысл указать здесь более точные пути.
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/[:controller[/:action]]',
							'constraints' => array(
							'controller' =>	'[a-zA-Z][a-zA-Z0-9_-]*',
							'action' =>	'[a-zA-Z][a-zA-Z0-9_-]*',
							),
						'defaults' => array(),
						),
					),
				),
			),
			'user-manager' => array(
				'type' => 'Segment',
				'options' => array(
					'route' => '/user-manager[/:action[/:id]]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'User\Controller\UserManager',
						'action' => 'index',
					),
				),
			),
		),
	),
	
	
	
);
