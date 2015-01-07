<?php

namespace Anax\Comment;

/**
 * To attach comments-flow to a page or some content.
 *
 */
class CommentController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;


	
	/**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize()
    {
        $this->comments = new \Anax\Comment\Comment();
        $this->comments->setDI($this->di);
	}
	/*
	* Setup for comments.
	*@return void
	*
	*/
	
	public function setupCommentsAction($page) {
		// sets up the comment form.
        $this->di->session();
        $form = new \Anax\Comment\CFormComments($page);
        $form->setDI($this->di);
        $form->check();
        
        // view of the comment form
        $this->di->views->add('comment/form', [
            'title' => "Try out a form using CForm",
            'content' => $form->getHTML()
        ]);
    }
	
	
    /**
     * View all comments.
     *
     * @return void
     */
    public function viewAction($page=null)
    {
      $all = $this->comments->query()
            ->where('page = ?')
            ->execute([$page]);
            
        $this->views->add('comment/comments', [
            'comments' => $all,
            'page'       => $page,
        ]);
    } 

    /**
     * Add a comment.
     *
     * @return void
     */
    public function addAction()
    {
		
		// Gets the values
        $values = $this->session->get('form-save');
        $comment = [
            'content'   => $values['content']['value'],
            'name'      => $values['name']['value'],
            'web'       => $values['web']['value'],
            'mail'      => $values['mail']['value'],
            'page'      => $values['page']['value'],
            'timestamp' => time(),
            'ip'        => $this->request->getServer('REMOTE_ADDR'),
        ];
        $this->comments->save($comment);
        $url = $this->url->create($values['page']['value'] . "#commentSection");
        $this->di->session();
        $this->session->set('form-save', null);
        $this->response->redirect($url); 
        
		/*
        $isPosted = $this->request->getPost('doCreate');
        
        if (!$isPosted) {
            $this->response->redirect($this->request->getPost('redirect'));
        }

        $comment = [
            'content'   => $this->request->getPost('content'),
            'name'      => $this->request->getPost('name'),
            'web'       => $this->request->getPost('web'),
            'mail'      => $this->request->getPost('mail'),
            'timestamp' => time(),
            'ip'        => $this->request->getServer('REMOTE_ADDR'),
        ];*/
    }
    
    /**
     * removes comment at specifik id
     * $param int $id of the comment to be removed.
     * @return void
     */
    public function deleteAction($id = null,$page = null) {
        if (!isset($id)) {
            die("Missing id");
        }
     
        $res = $this->comments->delete($id);
        $url = $this->url->create($page . "#commentSection");
        $this->response->redirect($url);
    } 
    
    public function saveAction() {
        $values = $this->session->get('form-save');
        
        $comment = $this->comments->find($values['id']['value']);
        $comment->name = $values['name']['value'];
        $comment->web = $values['web']['value'];
        $comment->mail = $values['mail']['value'];
        $comment->timestamp = time();
        $comment->content = $values['content']['value'];
        $url = $this->url->create($values['page']['value'] . "#commentSection");
        $comment->save();
        $this->di->session();
        $this->session->set('form-save', null);
        
        
        $this->response->redirect($url);
    } 
    
    public function editAction($id,$page=null) {
        if (!isset($id)) {
            die("Missing id");
        }
        $comment = $this->comments->find($id);
 
        // sets up the comment form.
        $this->di->session();
        $form = new \Anax\Comment\CFormEditComments($comment,$id,$page);
        $form->setDI($this->di);
        $form->check();
        
        // view of the comment form
        $this->di->views->add('comment/form', [
            'title' => "Try out a form using CForm",
            'content' => $form->getHTML()
        ]);
        
        $this->theme->setTitle('Editera Kommentar');
    } 


    /**
     * Remove all comments.
     *
     * @return void
     */
    public function removeAllAction($page='')
    {
        $comments = $this->comments->query()
            ->where('page = ?')
            ->execute([$page]);
        
        foreach ($comments as $comment)
        {
            $this->comments->delete($comment->id);
        }
        $url = $this->url->create($page);
        $this->response->redirect($url);
    } 
} 