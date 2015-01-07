<?php

namespace Anax\Users;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormAddUser extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        parent::__construct([], [
            'acronym' => [
                'type'        => 'text',
                'label'       => 'acronym',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
			'password' => [
                'type'        => 'password',
                'label'       => 'password',
                'required'    => true,
                'validation'  => ['not_empty'],
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
        return true;
    }

    
    /**
     * Callback What to do if the form was submitted?
     *
     */
    public function callbackSuccess()
    {
        $this->redirectTo('users/add/' . $this->Value('acronym'));
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