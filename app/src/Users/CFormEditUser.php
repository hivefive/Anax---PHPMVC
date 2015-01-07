<?php

namespace Anax\Users;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormEditUser extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

    /**
     * Constructor
     *
     */
    public function __construct($acronym,$name,$email,$password,$id)
    {
        parent::__construct([], [
            'acronym' => [
                'type'        => 'text',
                'required'    => true,
                'validation'  => ['not_empty'],
                'value'          => $acronym,
            ],
            'name' => [
                'type'        => 'text',
                'required'    => true,
                'value'          => $name,
            ],
            'email' => [
                'type'        => 'text',
                'required'    => true,
                'validation'  => ['not_empty', 'email_adress'],
                'value'          => $email,
            ],
			 'password' => [
                'type'        => 'password',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'id' => [
                'type'           => 'hidden',
                'value'          => $id,
            ],
            'save' => [
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
        $this->redirectTo('users/save');
    }

    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail()
    {
        $this->AddOutput("<p><i>You need to fill something in.</i></p>");
    }
}