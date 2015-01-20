<?php

namespace Anax\Questions;

/**
 * Controller for questions
 *
 */
class QuestionsController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable,
		\Anax\MVC\TRedirectHelpers; 


	
	/**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize()
    {
			if(!$this->session->get('userId')){
				$this->redirectTo('login');
				}

		$this->users = new \Anax\Users\User();
		$this->users->setDI($this->di);
        $this->questions = new \Anax\Questions\Question();
        $this->questions->setDI($this->di);
		
	}

	/**
	* Comment on a question
	*/
	public function commentAction () 
	{
		if(!isset($_SESSION['user']))  
		// Gets the values
        $values = $this->session->get('form-save');
		$comment = [
            'content'   => $values['content']['value'],
			'name'		=> $values['name']['value'],
			'email'		=> $values['email']['value'],
			'timestamp' => time(),
		];
		
		$this->questions->save($comment);
        $url = $this->url->create($values['page']['value'] . "#commentSection");
        $this->di->session();
        $this->session->set('form-save', null);
        $this->response->redirect($url); 
			
	
	}
	
	/**
	* Ask a question
	*/
	public function questionAction () 
	{
	
	
	}
	
	/**
	* Post an answer
	*/
	public function answerAction ()
	{
	
	
	}
	
	/**
	* Delete a question or comment
	*/
	public function deleteAction () 
	{
	
	
	}
	
	/**
	* Edit a question or comment
	*/
	public function editAction () {
	
	
	}
	
	
	public function setupQuestionAction () 
	{
		$this->db->dropTableIfExists('question')->execute();
	 
			$this->db->createTable(
				'question',
				[
					'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
					'userId' => ['integer', 'not null'], 
					'created' => ['datetime'],
					'updated' => ['datetime'],
					'title' => ['varchar(300)'],
					'type' => ['varchar(80)', 'not null'], 
					'content' => ['text'],
				]
			)->execute();
			
				
	 
			$now = date(DATE_RFC2822);
	
		$url = $this->url->create('question');
        $this->response->redirect($url); 
	}
	
	public function setupAnswerAction () 
	{
		$this->db->dropTableIfExists('answer')->execute();
	 
			$this->db->createTable(
				'answer',
				[
					'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
					'userId' => ['integer', 'not null'], 
					'created' => ['datetime'],
					'updated' => ['datetime'],
					'title' => ['varchar(300)'],
					'type' => ['varchar(80)', 'not null'], 
					'content' => ['text'],
				]
			)->execute();
			
				
	 
			$now = date(DATE_RFC2822);
	
		$url = $this->url->create('answer');
        $this->response->redirect($url); 
	}
	
	/**
	* Show the most recent questions
	*/
	public function latestAction () 
	{
		$all = $this->questions->query()
				->where('type = ?')
				->orderBy('id DESC')
				->limit(3)
				->execute();
			
			$this->views->add('questions/list-all', [
				'questions' => $all,
			]);
	
	}
	
	/**
	* List the most active users
	*/
	public function activeAction () 
	{
	
	
	}
	
	public function idAction () 
	{
	
	
	}
	
	/**
	* List all questions
	*/
	public function getQuestions () 
	{
	
	
	}
	
	
	
}