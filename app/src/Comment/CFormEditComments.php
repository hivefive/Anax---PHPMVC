<?php

namespace Anax\Comment;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormEditComments extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

    /**
     * Constructor
     *
     */
    public function __construct($commentValues,$id,$page)
    {
        parent::__construct([], [
            'content' => [
                'type'        => 'textarea',
                'label'       => 'Kommentera:',
                'required'    => true,
                'validation'  => ['not_empty'],
                'value'          => $commentValues->content,
            ],
            'name' => [
                'type'        => 'text',
                'required'    => true,
                'validation'  => ['not_empty'],
                'value'          => $commentValues->name,
            ],
            'mail' => [
                'type'        => 'text',
                'required'    => true,
                'validation'  => ['not_empty', 'email_adress'],
                'value'          => $commentValues->mail,
            ],
            'web' => [
                'type'        => 'text',
                'required'    => false,
                'value'          => $commentValues->web,
            ],
            'id' => [
                'type'           => 'hidden',
                'value'          => $id,
            ],
            'page' => [
                'type'           => 'hidden',
                'value'          => $page,
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
        $this->AddOutput("<p><i>Comment have been saved.</i></p>");
        $this->redirectTo('comment/save');
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