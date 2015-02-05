<?php

namespace Anax\Questions;

/**
 * Controller for questions
 *
 */
class QuestionsController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable; 

		public $users;
	
	/**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize()
    {
			
		
		$this->users = new \Anax\Users\User();
		$this->users->setDI($this->di);
        $this->questions = new \Anax\Questions\Question();
        $this->questions->setDI($this->di);
		
	}

	/**
	* Save a question
	*/
	public function saveAction ($id) 
	{
		 $values = $this->session->get('form-save');
        
        $question = $this->questions->find($values['id']['value']);
        $question->name = $values['name']['value'];
        $question->web = $values['web']['value'];
        $question->mail = $values['mail']['value'];
        $question->timestamp = time();
        $question->content = $values['content']['value'];
        $url = $this->url->create($values['page']['value'] . "#commentSection");
        $question->save();
        $this->di->session();
        $this->session->set('form-save', null);
        
        
        $this->response->redirect($url);
	
	}
	
	/**
	* Save a question
	*/
	public function saveCommentAction () 
	{
		$values = $this->session->get('form-save');
        
        $comment = $this->comments->find($values['id']['value']);
        $comment->content = $values['content']['value'];
        $comment->userId = $values['userId']['value'];
        $comment->timestamp = time();
        $url = $this->url->create($values['title']['value']);
        $questions->save();
        $this->di->session();
        $this->session->set('form-save', null);
        
        
        $this->response->redirect($url);
	
	}
	
	/**
	* Ask a question
	*/
	public function viewAction () 
	{
		 $this->theme->setTitle("Alla frågor");
			$all = $this->questions->findAll();
			// $all = $this->question->getQuestions();
			$this->views->add('questions/view', [
			'question' => $all,
			]);
	}
	
	/**
     * Add a question.
     *
     * @return void
     */
    public function addAction() {
     if (!isset($_SESSION['userId'])) {
$this->theme->setTitle("Logga in");
$this->views->add('users/notLoggedIn', [
'content' => '<h1>Skapa profil eller logga in</h1>',
]);
return;
}
if (isset($id)) {
$edit_question = $this->question->find($id);
} else {
$edit_question = (object) [
'question' => '',
'name' => '',
'mail' => '',
'title' => '',
];
}
$now = date('Y-m-d H:i:s');
$form = $this->form->create([], [
'title' => [
'type' => 'text',
'label' => 'Titel: ',
'required' => true,
'validation' => ['not_empty'],
'value' => $edit_question->mail,
],
'question' => [
'type' => 'textarea',
'label' => 'Fråga: ',
'required' => true,
'validation' => ['not_empty'],
'value' => $edit_question->question,
],
'name' => [
'type' => 'hidden',
'label' => 'Användare: ',
'required' => true,
'validation' => ['not_empty'],
'value' => $_SESSION['acronym'],
],
'mail' => [
'type' => 'hidden',
'label' => 'Email: ',
'required' => true,
'validation' => ['not_empty', 'email_adress'],
'value' => $_SESSION['email'],
],
'tags' => [
'type' => 'textarea',
'label' => 'Taggar: Separera med ny rad ',
'required' => true,
'validation' => ['not_empty',],
'value' => '',
],
'userID' => [
'type' => 'hidden',
'label' => 'AnvändarID',
'required' => true,
'validation' => ['not_empty',],
'value' => $_SESSION['userId'],
],
'submit' => [
'type' => 'submit',
'callback' => function($form) {
$form->saveInSession = true;
return true;
}
],
]);
$status = $form->check();
if ($status === true) {
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
$this->questions->saveTag($tag);
}
// $this->question->saveTag('niwhede');
// session_unset($_SESSION['form-save']);
unset($_SESSION['form-save']);
$this->questions->save($question);
// Route to prefered controller function
$url = $this->url->create('question/view');
$this->response->redirect($url);
} else if ($status === false) {
// What to do when form could not be processed?
$form->AddOutput("<p><i>Något gick fel.</i></p>");
}
// Prepare the page content
$this->views->add('questions/view-default', [
'title' => "Ställ en fråga",
'main' => $form->getHTML(),
]);
$this->theme->setVariable('title', "Ställ en fråga");
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
	
	
	public function setupAction () 
	{
		$this->theme->setTitle("Reset and setup database.");
		$this->questions->setup();
		$this->questions->cleanDB();
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
				->orderBy('timestamp DESC')
				->limit(3)
				->execute();
		$mostUsed = $this->questions->getMostUsedTags();
			
			$this->views->add('questions/list-all', [
				'questions' => $all,
				'mostUsedTags' => $mostUsed,
			]);
	
	}
	
	/**
	* List the most active users
	*/
	public function activeAction () 
	{
	
	
	}
	
	public function idAction ($id = null) 
	{
	 $question = $this->questions->find($id);
$this->theme->setTitle("Läs fråga");
$answers = $question->getAnswers($id);
$contributors = $question->getContributor();
$commentators = $question->getCommentators();
$comments = $question->getComments($id);
$commentsOnAnswers = $question->getCommentsOnAnswers($id);
if (!empty($answers)) {
$hasAcceptedAnswer = $question->hasAcceptedAnswer($id);
} else {
$hasAcceptedAnswer = array();
}
$this->views->add('questions/question', [
'id' => $id,
'question' => $question,
'answers' => $answers,
'contributors' => $contributors,
'comments' => $comments,
'commentators' => $commentators,
'commentsOnAnswers' => $commentsOnAnswers,
'hasAcceptedAnswer' => $hasAcceptedAnswer,
]);
	
	}
	
	/**
	* List all questions
	*/
	public function listallAction () 
	{
		$content = '';
		$res = $this->question->firstPage();
		$mostUsed = $this->questions->getMostUsedTags();
		$this->views->add('questions/start', [
		'content' => $content,
		'questions' => $res,
		'mostUsedTags' => $mostUsed,
		]);
	}
	
	
	
}