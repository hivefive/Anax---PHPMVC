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
		 $this->theme->setTitle("All questions");
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
			$this->theme->setTitle("Login");
			$this->views->add('users/notLoggedIn', [
			'content' => '<h1>Register or login</h1>',
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
	'label' => 'Title: ',
	'required' => true,
	'validation' => ['not_empty'],
	'value' => $edit_question->mail,
	],
	'question' => [
	'type' => 'textarea',
	'label' => 'Question: ',
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
	'label' => 'Tag: seperated by blankspaces',
	'required' => true,
	'validation' => ['not_empty',],
	'value' => '',
	],
	'userID' => [
	'type' => 'hidden',
	'label' => 'UserID',
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
		$tags = explode("\n", str_replace(' ', '', $question['tags']));
		foreach($tags as $tag) {
		$tag = trim(preg_replace('/\s+/', ' ', $tag));
		$this->questions->saveTag($tag);
	}

	unset($_SESSION['form-save']);
	$this->questions->save($question);
	// Route to prefered controller function
	$url = $this->url->create('questions/view');
	$this->response->redirect($url);
	} else if ($status === false) {
	// What to do when form could not be processed?
		$form->AddOutput("<p><i>Something went wrong</i></p>");
	}
	// Prepare the page content
	$this->views->add('questions/view-default', [
		'title' => "Ask a question",
		'main' => $form->getHTML(),
	]);
	$this->theme->setVariable('title', "Ask a question");
    }
    
	
	/**
	* Post an answer
	*/
	public function answerAction ($id = null)
	{
		if (!isset($_SESSION['userId'])) {
			$this->theme->setTitle("Login");
			$this->views->add('users/notLoggedIn', [
			'content' => '<h1>Register or login</h1>',
			]);
			return;
			}
	
      $thisQuestion = $this->questions->find($id);
      $answer = "";
      $this->theme->setTitle("Answer the question");
      $form = $this->form->create([], [
        'title' => [
          'type'        => 'hidden',
          'label'       => 'Title: ',
          'required'    => false,
          // 'validation'  => ['not_empty'],
          'value'       => $thisQuestion->title,
        ],
        'answer' => [
          'type'        => 'textarea',
          'label'       => 'Answer: ',
          'required'    => true,
          // 'validation'  => [],
          'value'       => "",
        ],
        'question' => [
          'type'        => 'hidden',
          'label'       => 'Question: ',
          'required'    => false,
          // 'validation'  => ['not_empty'],
          'value'       => $thisQuestion->question,
        ],
        'name' => [
          'type'        => 'hidden',
          'label'       => 'User: ',
          'required'    => false,
          // 'validation'  => ['not_empty'],
          'value'       => $thisQuestion->name,
        ],
        'mail' => [
          'type'        => 'hidden',
          'label'       => 'Email: ',
          'required'    => false,
          // 'validation'  => ['not_empty',],
          'value'       => $thisQuestion->mail,
        ],
        'tags' => [
          'type'        => 'hidden',
          'label'       => 'Tags: ',
          'required'    => false,
          // 'validation'  => ['not_empty',],
          'value'       => $thisQuestion->tags,
        ],
        'submit' => [
          'type'      => 'submit',
          'callback'  => function($form) {
            $form->saveInSession = true;
            return true;
          }
        ],
        ]);

        $status = $form->check();

        if ($status === true) {
          $question['question']     = $_SESSION['form-save']['question']['value'];
          $question['question']     = $this->textFilter->doFilter($question['question'], 'shortcode, markdown');
          $question['title']        = $_SESSION['form-save']['title']['value'];
          $answer       = $_SESSION['form-save']['answer']['value'];
          $answer = $this->textFilter->doFilter($answer, 'shortcode, markdown');
          $user = $_SESSION['acronym'];
          $this->questions->answerQuestion($user, $answer, $id, 0);


          // session_unset($_SESSION['form-save']);
          unset($_SESSION['form-save']);

          $this->questions->save($question);
          $url = $this->url->create('questions/id/' . $id);
          $this->response->redirect($url);

        } else if ($status === false) {
          echo "fail";
        }
        $question = $this->questions->find($id);
        $answers = $question->getAnswers($id);
        $contributors = $question->getContributor();
        $question->incrementNbrOfAnswers($id);

      $this->views->add('questions/question', [
        'question' => $thisQuestion,
        'form' => $form->getHTML(),
        'contributors' => $contributors,
        'answers' => $answers,
        ]);
	
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
	$this->theme->setTitle("Question");
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
	
	/**
	* Show questions through selected tag
	*/
	public function tagIdAction($id = null) 
	{
	
		$questions = $this->questions->findAll();
		$list = array();
		foreach($questions as $question) {
		$tags = explode(" ", $question->tags);
		foreach($tags as $tag) {
		$tag = trim(preg_replace('/\s+/', ' ', $tag));
		if ($tag == $id) {
		array_push($list, $question);
		}
		}
		}
		$this->views->add('questions/view', [
		'question' => $list,
		]);
	
	}
	
	 public function allTagsAction() {
		$this->theme->setTitle("All tags");
		$list = array();
		$allTags = $this->questions->getAllTags();
		foreach($allTags as $tag) {
		array_push($list, $tag->tag);
		}
		$this->views->add('tags/list-all', [
		'tags' => $list,
		]);
	}
	
	 public function commentAction($id = null) {
		  if (!isset($_SESSION['userId'])) {
			$this->theme->setTitle("Login");
			$this->views->add('users/notLoggedIn', [
			'content' => '<h1>Register or login</h1>',
			]);
			return;
			}
		$form = $this->form->create([], [
		'comment' => [
		'type' => 'text',
		'label' => 'Comment: ',
		'required' => false,
		'value' => '',
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
		$question['comment'] = $_SESSION['form-save']['comment']['value'];
		$question['comment'] = $this->textFilter->doFilter($question['comment'], 'shortcode, markdown');
		$comment = $question['comment'];
		$user = $_SESSION['acronym'];
		$now = date('Y-m-d H:i:s');
		$this->questions->comment($id, $user, $comment, $now);
		// session_unset($_SESSION['form-save']);
		unset($_SESSION['form-save']);
		// $this->question->save($question);
		$url = $this->url->create('questions/id/' . $id);
		$this->response->redirect($url);
		} else if ($status === false) {
		echo "fail";
		}
		$question = $this->questions->find($id);
		$commentators = $question->getCommentators();
		$comments = $question->getComments($id);
		$commentsOnAnswers = $question->getCommentsOnAnswers($id);
		$this->views->add('questions/comment', [
		'question' => $question,
		'commentators' => $commentators,
		'comments' => $comments,
		'commentsOnAnswers' => $commentsOnAnswers,
		'form' => $form->getHtml(),
		]);
	}
	public function upVoteAction($id = null) 
	{if (!isset($_SESSION['userId'])) {
			$this->theme->setTitle("Login");
			$this->views->add('users/notLoggedIn', [
			'content' => '<h1>Register or login</h1>',
			]);
			return;
			}
		$question = $this->questions->find($id);
		$question->upvote($id);
		$url = $this->url->create('questions/id/' . $id);
		$this->response->redirect($url);
	}
	public function downVoteAction($id = null) 
	{if (!isset($_SESSION['userId'])) {
			$this->theme->setTitle("Login");
			$this->views->add('users/notLoggedIn', [
			'content' => '<h1>Register or login</h1>',
			]);
			return;
			}
		$question = $this->questions->find($id);
		$question->downVote($id);
		$url = $this->url->create('questions/id/' . $id);
		$this->response->redirect($url);
	}
	
	 public function acceptAction($id = null, $questionID = null, $acronym = null) {
		if (!isset($_SESSION['userId'])) {
			$this->theme->setTitle("Login");
			$this->views->add('users/notLoggedIn', [
			'content' => '<h1>Register or login</h1>',
			]);
			return;
			}
		$answer = $this->questions->getAnswerFromId($id);
		$this->questions->acceptAnswer($answer[0]->id, $acronym, $questionID);
		$url = $this->url->create('questions/id/' . $questionID);
		$this->response->redirect($url);
	}
	
	public function upVoteAnswerAction($questionID = null, $id = null) {
		if (!isset($_SESSION['userId'])) {
			$this->theme->setTitle("Login");
			$this->views->add('users/notLoggedIn', [
			'content' => '<h1>Register or login</h1>',
			]);
			return;
			}
		$this->questions->upVoteAnswer($id);
		$url = $this->url->create('questions/id/' . $questionID);
		$this->response->redirect($url);
	}
	public function downVoteAnswerAction($questionID = null, $id = null) {
		if (!isset($_SESSION['userId'])) {
			$this->theme->setTitle("Login");
			$this->views->add('users/notLoggedIn', [
			'content' => '<h1>Register or login</h1>',
			]);
			return;
			}
		$this->questions->downVoteAnswer($id);
		$url = $this->url->create('questions/id/' . $questionID);
		$this->response->redirect($url);
	}
	public function upVoteCommentOnQuestionAction($id = null, $questionID = null) {
		if (!isset($_SESSION['userId'])) {
			$this->theme->setTitle("Login");
			$this->views->add('users/notLoggedIn', [
			'content' => '<h1>Register or login</h1>',
			]);
			return;
			}
		$this->questions->upVoteCommentOnQuestion($id);
		$url = $this->url->create('questions/id/' . $questionID);
		$this->response->redirect($url);
	}
	public function downVoteCommentOnQuestionAction($id = null, $questionID = null) {
		if (!isset($_SESSION['userId'])) {
			$this->theme->setTitle("Login");
			$this->views->add('users/notLoggedIn', [
			'content' => '<h1>Register or login</h1>',
			]);
			return;
			}
		$this->questions->downVoteCommentOnQuestion($id);
		$url = $this->url->create('questions/id/' . $questionID);
		$this->response->redirect($url);
	}
	public function upVoteCommentOnAnswerAction($id = null, $questionID) {
		if (!isset($_SESSION['userId'])) {
			$this->theme->setTitle("Login");
			$this->views->add('users/notLoggedIn', [
			'content' => '<h1>Register or login</h1>',
			]);
			return;
			}
		$this->questions->upVoteCommentOnAnswer($id);
		$url = $this->url->create('questions/id/' . $questionID);
		$this->response->redirect($url);
	}
	public function downVoteCommentOnAnswerAction($id = null, $questionID) {
		if (!isset($_SESSION['userId'])) {
			$this->theme->setTitle("Login");
			$this->views->add('users/notLoggedIn', [
			'content' => '<h1>Register or login</h1>',
			]);
			return;
			}
		$this->questions->downVoteCommentOnAnswer($id);
		$url = $this->url->create('questions/id/' . $questionID);
		$this->response->redirect($url);
	}

	
	public function commentOnAnswerAction($id = null, $questionID = NULL) {
      if (!isset($_SESSION['userId'])) {
			$this->theme->setTitle("Login");
			$this->views->add('users/notLoggedIn', [
			'content' => '<h1>Register or login</h1>',
			]);
			return;
			}
	  $form = $this->form->create([], [
        'comment' => [
          'type'        => 'text',
          'label'       => 'Comment: ',
          'required'    => false,
          // 'validation'  => ['not_empty'],
          'value'       => '',
        ],
        'submit' => [
          'type'      => 'submit',
          'callback'  => function($form) {
            $form->saveInSession = true;
            return true;
          }
        ],
        ]);
        $status = $form->check();

        if ($status === true) {
          $question['comment']     = $_SESSION['form-save']['comment']['value'];
          $question['comment']     = $this->textFilter->doFilter($question['comment'], 'shortcode, markdown');
          $comment       = $question['comment'];
          $user = $_SESSION['acronym'];
          $now = date('Y-m-d H:i:s');
          $this->questions->commentOnAnswer($id, $user, $comment, $now);

          // session_unset($_SESSION['form-save']);
          unset($_SESSION['form-save']);

          $url = $this->url->create('questions/id/' . $questionID);
          $this->response->redirect($url);

        }

          $this->views->add('questions/comment', [
            'form' => $form->getHtml(),
            ]);
    }
	
	 public function sortAction($option) {
		if ($option == "votes") {
		$all = $this->questions->sortByVotes();
		} else if ($option == "nbrOfAnswers") {
		$all = $this->questions->sortbyNbrOfAnswers();
		} else if ($option == "date") {
		$all = $this->questions->sortByDate();
		}
		$this->views->add('questions/view', [
		'question' => $all,
		]);
	}
	public function sortAnswersAction($id = null, $option = null) {
		if ($option == "votes") {
			$all = $this->questions->sortAnswersByVotes($id);
			} else if ($option == "date") {
			$all = $this->questions->sortAnswersByDate($id);
			}
			$question = $this->questions->find($id);
			$this->theme->setTitle("Question");
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
				'answers' => $all,
				'contributors' => $contributors,
				'comments' => $comments,
				'commentators' => $commentators,
				'commentsOnAnswers' => $commentsOnAnswers,
				'hasAcceptedAnswer' => $hasAcceptedAnswer,
		]);
	}
}