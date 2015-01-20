<?php

namespace Anax\Tags;

/**
 * Controller for questions
 *
 */
class TagsController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;


	
	/**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize()
    {
        $this->comments = new \Anax\Tags\Tag();
        $this->comments->setDI($this->di);
	}
	
}