<?php
namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UserManagerController extends AbstractActionController{
	public function indexAction(){
		$userTable = $this->getServiceLocator()->get('UserTable');
		$viewModel = new ViewModel(array(
			'users' => $userTable->fetchAll(),
		));
		return $viewModel;
	}
	
	public function editAction(){
		$userTable = $this->getServiceLocator()->get('UserTable');
		$user = $userTable->getUser($this->params()->fromRoute('id'));
		$form = $this->getServiceLocator()->get('UserEditForm');
		$form->bind($user);
		$viewModel = new ViewModel(array(
			'form' => $form,
			'user_id' => $this->params()->fromRoute('id')
		));
		return $viewModel;
	}
	
	public function processAction(){
		//Получаем идентификатор пользователя
		$post = $this->request->getPost();
		$userTable = $this->getServiceLocator()->get('UserTable');
		//Загружаем сущность User
		$user = $userTable->getUser($post->id);
		//Привязка сущности User к Form
		$form = $this->getServiceLocator()->get('UserEditForm');
		$form->bind($user);
		$form->setData($post);
		//Сохраняем юзера
		$this->getServiceLocator()->get('UserTable')->saveUser($user);
		
	}
	
	public function deleteAction(){
		$this->getServiceLocator()->get('UserTable')->deleteUser($this->params()->fromRoute('id'));
	}
	
}