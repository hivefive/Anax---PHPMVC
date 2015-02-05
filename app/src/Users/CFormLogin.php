<?php
namespace Anax\Users;
/**
* Anax base class for wrapping sessions.
*
*/
class CFormLogin extends \Mos\HTMLForm\CForm
{
		use \Anax\DI\TInjectable,
		\Anax\MVC\TRedirectHelpers;
		
		public $users = null;
		/* public function initialize(){
		$this->users = new \Anax\Users\User();
		}*/
		/**
		* Constructor
		*
		*/
		public function __construct($users, $user=null)
		{
		$this->users = $users;
		parent::__construct([], [
			'acronym' => [
			'type' => 'text',
			'label' => 'Username:',
			'required' => true,
			'validation' => ['not_empty'],
			'autofocus' => true,
			'value' => isset($user) ? $user->acronym : '',
			],
			'password' => [
			'type' => 'password',
			'label' => 'Password:',
			'required' => true,
			'validation' => ['not_empty'],
			'value' => '',
			],
			'submit' => [
			'type' => 'submit',
			'callback' => [$this, 'callbackSubmit'],
			],
		]);
		}
/**
* Customise the check() method.
*
* @param callable $callIfSuccess handler to call if function returns true.
* @param callable $callIfFail handler to call if function returns true.
*/
public function check($callIfSuccess = null, $callIfFail = null)
{
return parent::check([$this, 'callbackSuccess'], [$this, 'callbackFail']);
}
/**
* Callback for submit-button.
*
*/
public function callbackSubmit()
	{

		$this->saveInSession = true;
		$user = $this->users->findAcronym($this->Value('acronym'));
		if($user){
			$users = $this->users->findAll();
			$DBpassword = $user->password;
			$password = $this->Value('password');
			if(password_verify($password, $DBpassword)){
				$user = $this->users->find($user->id);
				$this->session->set('userId', $user->id);
				$this->session->set('email', $user->email);
				$this->session->set('acronym', $user->acronym);
				$now = gmdate('Y-m-d H:i:s');
				$count = $user->timesLoggedOn;
				$count += 1;
				$this->users->save([
				'active' => $now,
				'timesLoggedOn' => $count,
				]);
				$this->redirectTo('users/profile/'. $user->id);				//users/profile
			}
			else {
			$this->redirectTo('login');
			;
			}
			return true;
		}
		else{
		$this->redirectTo('login');
		}
	}
/**
* Callback What to do if the form was submitted?
*
*/
public function callbackSuccess()
{
//$url = $this->url->create('users/id/' . $this->users->id);
//$this->response->redirect($url);
$this->redirectTo();
}
/**
* Callback What to do when form could not be processed?
*
*/
public function callbackFail()
{
$this->AddOutput("<p><i>Form was submitted and the Check() method returned false.</i></p>");
$this->redirectTo();
}
} 