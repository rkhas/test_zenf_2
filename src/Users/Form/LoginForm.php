<?php
namespace Users\Form;
use Zend\Form\Form;
class LoginForm extends Form{
	public function __construct($name = null){
		parent::__construct('Login');
		$this->setAttribute('method', 'post');
		$this->setAttribute('enctype', 'multipart/form-data');

		$this->add(array(
			'name' => 'email',
			'attributes' => array(
				'type' => 'email',
				'required' => 'required',
			),
			'options' => array(
				'label' => 'Email',
			),
			'filters' => array(
				array('name' => 'StringTrim'),
			),
			'validators' => array(
				array(
					'name' => 'EmailAddres',
					'options' => array(
						'messages' => array(
							\Zend\Validator\EmailAddress::INVALID_FORMAT => 'Email addres is inOBvalid',
						),
					),
				),
			),

		));
		$this->add(array(
			'name' => 'password',
			'attributes' => array(
				'type' => 'password',
				'required' => 'required',
			),
			'options' => array(
				'label' => 'Пароль',
			),
		));
		$this->add(array(
			'name' => 'submit',
			'attributes' => array(
				'type' => 'submit',
				'value' => 'Войти',
			),
		));
	}
	
}
