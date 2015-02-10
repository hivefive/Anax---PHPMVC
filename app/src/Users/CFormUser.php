<?php
namespace Anax\Users;
/**
* Anax base class for wrapping sessions.
*
*/
class CFormUser extends \Mos\HTMLForm\CForm
{
	use \Anax\DI\TInjectionaware,
	\Anax\MVC\TRedirectHelpers;
	public $users=null;
	/**
	* Constructor
	*
	*/
	public function __construct($users, $user=null)
	{
			$this->users=$users;
			parent::__construct([], [
			'name' => [
				'type' => 'text',
				'label' => 'Namn:',
				'required' => true,
				'validation' => ['not_empty'],
				'value' => isset($user) ? $user->name : '',
			],
			'email' => [
				'type' => 'text',
				'label' => 'E-post:',
				'required' => true,
				'validation' => ['not_empty', 'email_adress'],
				'value' => isset($user) ? $user->email : '',
			],
			'acronym' => [
				'type' => 'text',
				'label' => 'Användarnamn:',
				'required' => true,
				'validation' => ['not_empty'],
				'value' => isset($user) ? $user->acronym : '',
			],
			'password' => [
				'type' => 'password',
				'label' => 'Lösenord:',
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
		$now = gmdate('Y-m-d H:i:s');
		$this->saveInSession = true;
		$this->users->save([
			'email' => $this->Value('email'),
			'name' => $this->Value('name'),
			'acronym' => $this->Value('acronym'),
			'password' => password_hash($this->Value('password'), PASSWORD_DEFAULT),
			'created' => $now,
			'active' => $now,
		]);
	$this->redirectTo('');
	return true;
	}
	/**
	* Callback What to do if the form was submitted?
	*
	*/
	public function callbackSuccess()
	{
		//$url = $this->url->create('users/id/' . $this->users->id);
		//$this->response->redirect($url);
		$this->redirectTo('');
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