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
        $this->tags = new \Anax\Tags\Tag();
        $this->tags->setDI($this->di);
	}
	
	public function listAction() {
        $this->theme->setTitle("All tags");
        $list = array();
        $allTags = $this->tags->getAllTags();
        foreach($allTags as $tag) {
          array_push($list, $tag->tag);
        }

        $this->views->add('tags/list-all', [
          'tags' => $list,
        ]);
      }
	
}