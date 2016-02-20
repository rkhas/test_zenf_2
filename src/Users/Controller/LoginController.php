<?php
namespace Users\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
//use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
//use Users\Form\LoginForm;
use Users\Form\LoginFilter;
class LoginController extends AbstractActionController{
	protected $authservice;
	public function indexAction(){
		//$form = new LoginForm();
		$form = $this->getServiceLocator()->get('LoginForm');
		$viewModel = new ViewModel(array('form' => $form));
		return $viewModel;
	}
	
	public function getAuthService()
	{
		if (! $this->authservice) {
			$dbAdapter = $this->getServiceLocator()->get(
			'Zend\Db\Adapter\Adapter');
			$dbTableAuthAdapter = new DbTableAuthAdapter(
			$dbAdapter,'user','email','password', 'MD5(?)');
			//$authService = new AuthenticationService();
			$authService = $this->getServiceLocator('AuthentificationService');
			$authService->setAdapter($dbTableAuthAdapter);
			$this->authservice = $authService;
		}
		return $this->authservice;
	}
	
	public function processAction(){
		if(!$this->request->isPost()){
			return $this->redirect()->toRoute(NULL,
				array(
					'controller' => 'login',
					'action' => 'index'
				)
			);
		}
		
		
		$this->getAuthService()->getAdapter()->setIdentity(
		$this->request->getPost('email'))->setCredential(
			$this->request->getPost('password'));
		$result = $this->getAuthService()->authenticate();
					
		if($result->isValid()){
			$this->getAuthService()->getStorage()->write(
				$this->request->getPost('email'));
			
			return $this->redirect()->toRoute(NULL, array(
				'controller' => 'login',
				'action' => 'confirm'
			));
		}		
	}
	

	public function confirmAction(){
		$user_email = $this->getAuthService()->getStorage()->read();
		$viewModel = new ViewModel(array(
			'user_email' => $user_email
		));
		return $viewModel;
	}
}
