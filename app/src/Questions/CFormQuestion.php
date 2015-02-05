<?php

namespace Anax\Questions;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormQuestion extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

		public $users = null;
		public $user = null;
    /**
     * Constructor
     *
     */
    public function __construct($userId = null, $user = null)
    {
		$this->users = $userId;
		$this->user = $user;
	
        parent::__construct([], [
			'title' => [
                'type'        => 'text',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
			'question' => [
                'type'        => 'textarea',
                'label'       => 'Ask a question:',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
			 'name' => [
				'type' => 'hidden',
				'label' => 'Username: ',
				'required' => true,
				'validation' => ['not_empty'],
				'value' =>  isset($user) ? $user->name : '',
			],
			'mail' => [
				'type' => 'hidden',
				'label' => 'Email: ',
				'required' => true,
				'validation' => ['not_empty', 'email_adress'],
				'value' => isset($user) ? $user->email : '',
			],
			'tags' => [
				'type' => 'textarea',
				'label' => 'Tags: Seperate tags with whitespace ',
				'required' => true,
				'validation' => ['not_empty',],
				'value' => '',
			],
			 'userId' => [
                'type'        => 'hidden',
                'required'    => true,
                'validation'  => ['not_empty'],
				'value' => $userId,
            ],
            'submit' => [
                'type'      => 'submit',
                'callback'  => [$this, 'callbackSubmit'],
            ],
            'reset' => [
                'type'      => 'reset',
            ],
        ]);
    }



    /**
     * Customise the check() method.
     *
     * @param callable $callIfSuccess handler to call if function returns true.
     * @param callable $callIfFail    handler to call if function returns true.
     */
    public function check($callIfSuccess = null, $callIfFail = null)
    {
        $status = parent::check();
        
        if ($status === true) {
 
            $this->callbackSuccess();
    
        } 
        else if ($status === false) {
    
            $this->callbackFail();
        }
    }



    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmit()
    {
        $this->saveInSession = true;

        return true;
    }

    
    /**
     * Callback What to do if the form was submitted?
     *
     */
    public function callbackSuccess()
    {
        $this->AddOutput("<p><i>Question have been added.</i></p>");
		
        $question['question'] = $_SESSION['form-save']['question']['value'];
		$question['question'] = $this->textFilter->doFilter($question['question'], 'shortcode, markdown');
		$question['name'] = $_SESSION['form-save']['name']['value'];
		$question['mail'] = $_SESSION['form-save']['mail']['value'];
		$question['tags'] = $_SESSION['form-save']['tags']['value'];
		$question['timestamp'] = $now;
		$question['ip'] = $this->request->getServer('REMOTE_ADDR');
		$question['title'] = $_SESSION['form-save']['title']['value'];
		$question['userID'] = $_SESSION['form-save']['userID']['value'];
		// $question['nbrOfAnswers'] = $_SESSION['form-save']['nbrOfAnswers']['value'];
		$tags = explode("\n", str_replace(' ', '', $question['tags']));
		foreach($tags as $tag) {
		$tag = trim(preg_replace('/\s+/', ' ', $tag));
		$this->question->saveTag($tag);
		}
		// $this->question->saveTag('niwhede');
		// session_unset($_SESSION['form-save']);
		unset($_SESSION['form-save']);
		$this->question->save($question);
		// Route to prefered controller function
		$url = $this->url->create('questions/view');
		$this->response->redirect($url);
    }

    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail()
    {
        $this->AddOutput("<p><i>You need to fill something in.</i></p>");
        $this->redirectTo('');
    }
} 