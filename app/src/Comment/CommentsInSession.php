<?php 
namespace Phpmvc\Comment;

/**
 * To attach comments-flow to a page or some content.
 *
 */
class CommentsInSession implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;
    
    private $key = null;

    public function __construct($key=null) {
        $this->key = 'comments' . $key;
    }
    /**
     * Add a new comment.
     *
     * @param array $comment with all details.
     * 
     * @return void
     */
    public function add($comment)
    {
        $comments = $this->session->get($this->key, []);
        $comments[] = $comment;
        $this->session->set($this->key, $comments);
    }
    
    /**
     * Delete comment at IDs
     * 
     * @param int $id of the comment to be deleted
     * @return void
     */
    public function delete($id) 
    {
        $comments = $this->session->get($this->key, []);
        unset($comments[$id]);
        $this->session->set($this->key, $comments);
    }
    
    /**
     * Find comment at IDs
     * 
     * @param int $id of the comment
     * @return comment
     */
    public function find($id) 
    {
        $comments = $this->session->get($this->key, []);
        return $comments[$id];
    }

    /**
     * Saves a comment
     * $param $comment to be saved.
     * $param int $id the id of the comment to be saved.s
     * $return void
     */
    public function save($comment,$id) {
        $comments = $this->session->get($this->key, []);
        $comments[$id] = $comment;
        $this->session->set($this->key, $comments);
    }


    /**
     * Find and return all comments.
     *
     * @return array with all comments.
     */
    public function findAll()
    {
        return $this->session->get($this->key, []);
    }



    /**
     * Delete all comments.
     *
     * @return void
     */
    public function deleteAll()
    {
        $this->session->set($this->key, []);
    }
}
