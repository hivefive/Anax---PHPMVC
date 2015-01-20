<?php

namespace Anax\Users;
 
/**
 * A controller for users and admin related events.
 *
 */
class UsersController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable,
		\Anax\MVC\TRedirectHelpers;
    
	
		public $users;
		public $session;
    
    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize()
    {
        $this->session = new \Anax\Session\CSession();
		$this->users = new \Anax\Users\User();
		$this->users->setDI($this->di); 
    }
    
    /**
     * Resets the database.
     * @return void
     */
     public function resetAction() 
     {
        $this->db->dropTableIfExists('user')->execute();
 
        $this->db->createTable(
            'user',
            [
                'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
                'acronym' => ['varchar(20)', 'unique', 'not null'],
                'email' => ['varchar(80)'],
                'name' => ['varchar(80)'],
                'password' => ['varchar(255)'],
                'created' => ['datetime'],
                'updated' => ['datetime'],
                'deleted' => ['datetime'],
                'active' => ['datetime'],
				'count_loggedin' => ['integer'],
            ]
        )->execute();
        
            $this->db->insert(
            'user',
            ['acronym', 'email', 'name', 'password', 'created', 'active']
        );
 
        $now = date(DATE_RFC2822);
 
        $this->db->execute([
            'admin',
            'admin@dbwebb.se',
            'Administrator',
            password_hash('admin', PASSWORD_DEFAULT),
            $now,
            $now
        ]);
 
        $this->db->execute([
            'doe',
            'doe@dbwebb.se',
            'John/Jane Doe',
            password_hash('doe', PASSWORD_DEFAULT),
            $now,
            $now
        ]);
        
        $url = $this->url->create('user');
        $this->response->redirect($url); 
     }
    
    /**
     * Creates the add a new user form.
     * shows the add a new user form.
     * @return void
     */
    public function showCreateFormAction() {
        $this->theme->setTitle("Add user");
        $this->di->session();
        $form = new \Anax\Users\CFormAddUser();
        $form->setDI($this->di);
        $form->check();
        
        // view of the comment form
        $this->di->views->add('comment/form', [
            'title' => "Add a new user",
            'content' => "<h1>Add a new user</h1>" . $form->getHTML()
        ]);
    }
    
    /**
     * Add new user.
     *
     * @param string $acronym of user to add.
     *
     * @return void
     */
    public function addAction($acronym = null, $password)
    {
        if (!isset($acronym)) {
            die("Missing acronym");
        }
     
        $now = date(DATE_RFC2822);
		$password = password_hash($password, PASSWORD_DEFAULT);
        $this->users->save([
            'acronym' => $acronym,
            'email' => $acronym . '@mail.se',
            'name' => 'Mr/Mrs ' . $acronym,
            'password' => $password,
            'created' => $now,
            'active' => $now,
        ]);
        
        $url = $this->url->create('user');
        $this->response->redirect($url);
    }
    
    /**
     * List all users
     * 
     * @return void
     */
    public function listAction() {
        $this->di->session();
        $all = $this->users->findAll();
     
        $this->theme->setTitle("List all users");
        $this->views->add('users/list-all', [
            'users' => $all,
            'title' => "Alla användare",
        ]);
    }
    
    /**
     * List user with id.
     *
     * @param int $id of user to display
     *
     * @return void
     */
    public function idAction($id = null)
	{
		$this->users = new \Anax\Users\User();
		$this->users->setDI($this->di);
		$user = $this->users->find($id);
		$this->theme->setTitle("View user with id");
		$this->views->add('users/profile', [
		'user' => $user,
		]);
	}
    /**
     * Delete user.
     *
     * @param integer $id of user to delete.
     *
     * @return void
     */
    public function deleteAction($id = null)
    {
        if (!isset($id)) {
            die("Missing id");
        }
     
        $res = $this->users->delete($id);
     
        $url = $this->session->get("listUrl");
        $this->response->redirect($url);
    }
    

    
    /**
     * Delete (soft) user.
     *
     * @param integer $id of user to delete.
     *
     * @return void
     */
    public function softDeleteAction($id = null)
    {
        if (!isset($id)) {
            die("Missing id");
        }
        $user = $this->users->find($id);
        if(isset($user->deleted)) {
            $user->deleted = null;
        }
        else {
            $now = date(DATE_RFC2822);
            $user->deleted = $now;
        }
        $user->save();
     
        $url = $this->session->get("listUrl");
        $this->response->redirect($url);
    }
    
    /**
     * List all active users
     *
     * @return void
     */
    public function listActiveAction()
    {
         $this->di->session();
        $this->theme->addStylesheet('css/user.css');
        $all = $this->users->query()
            ->where('active IS NOT NULL')
            ->execute();
     
        $this->theme->setTitle("Användare som är aktiva");
        $this->views->add('users/list-all', [
            'users' => $all,
            'title' => "Användare som är aktiva",
        ]);
    }
    
    /**
     * List all non active users.
     *
     * @return void
     */
    public function listInActiveAction()
    {
         $this->di->session();
        $this->theme->addStylesheet('css/user.css');
        $all = $this->users->query()
            ->where('active is NULL')
            ->execute();
     
        $this->theme->setTitle("Användare som är inaktiva");
        $this->views->add('users/list-all', [
            'users' => $all,
            'title' => "Användare som är inaktiva",
        ]);
    }
    
    /**
     * List all soft-deleted users and not active users.
     *
     * @return void
     */
    public function listSoftDeletedAction()
    {
        $this->di->session();
        $this->theme->addStylesheet('css/user.css');
        $all = $this->users->query()
            ->where('deleted IS NOT NULL')
            ->execute();
     
        $this->theme->setTitle("Users that are soft-deleted");
        $this->views->add('users/list-all', [
            'users' => $all,
            'title' => "Användare som är soft-deleted",
        
        
        ]);
    }
    
    /**
     * List all not soft-deleted users and not active users.
     *
     * @return void
     */
    public function listNotSoftDeletedAction()
    {
         $this->di->session();
        $this->theme->addStylesheet('css/user.css');
        $all = $this->users->query()
            ->where('deleted is NULL')
            ->execute();
        $this->theme->setTitle("Users that are not soft-deleted");
        $this->views->add('users/list-all', [
            'users' => $all,
            'title' => "Användare som inte är soft-deleted",
        ]);
    }
    
    /**
     * Show user information and create update form
     *
     * @return void
     */
    public function updateAction($id = null)
    {
        if (!isset($id)) {
            die("Missing id");
        }
        /*$this->users = new \Anax\Users\User();
        $this->users->setDI($this->di);*/
        $user = $this->users->find($id);
        
        $this->di->session();
        $form = new \Anax\Users\CFormEditUser($user->acronym,$user->name,$user->email,$user->password,$user->id);
        $form->setDI($this->di);
        $form->check();
     
        
        $this->theme->setTitle("Update user");
        $this->views->add('users/update', [
            'title' => 'Updatering av ' . $user->acronym,
            'content' => $form->getHTML(),
        ]);
    }
    
    /**
     * Update the user
     */
     public function saveAction() 
     {
        $values = $this->session->get('form-save');
         
        $user = $this->users->find($values['id']['value']);
        $user->name = $values['name']['value'];
        $user->acronym = $values['acronym']['value'];
        $user->email = $values['email']['value'];
		$user->password = password_hash($values['password']['value'], PASSWORD_DEFAULT);
        $now = date(DATE_RFC2822);
        $user->updated = $now;
        
        $user->save();
        $this->di->session();
        $this->session->set('form-save', null);
        
        $url = $this->url->create("user");
        $this->response->redirect($url);
     }
    
    
    
    /**
     * List all active and not deleted users.
     *
     * @return void
     */
    public function activeAction($id = null)
    {
        if (!isset($id)) {
            die("Missing id");
        }
        $user = $this->users->find($id);
        if(isset($user->active)) {
            $user->active = null;
        }
        else {
            $now = date(DATE_RFC2822);
            $user->active = $now;
        }
        $user->save();
     
        $url = $this->session->get("listUrl");
        $this->response->redirect($url);
    }
	
	public function mostactiveAction () 
	{
	
	
	}
	
	/**
	* Authenticate and login user
	*
	* return void
	*/
	public function loginAction () 
	{
		$this->di->theme->setTitle('Login');

		
		$form = new \Anax\Users\CFormLogin($this->users);
		$form->setDI($this->di);
		$form->check(); 
		
		
        // Prepare the page content
        $this->theme->setTitle("Login");
        $this->views->add('users/login', [
            'content' => $form->getHTML(),
        ]);
		
		

			
		
	}
	
	/**
	* Logout user
	*
	* return void
	*/
	public function logoutAction () 
	{
		if($this->di->session->get('userId') != null) {
            $this->di->session->set('userId', null);
        }
        $this->redirectTo(''); 
	}
	
	 public function profileAction($id)
	 {
		if (!isset($id)) {
			die("Missing id");
			}
				$user = $this->users->find($id);
				
				if($id == $this->di->session->get('userId')){
					$form = new \Anax\Users\CFormUser($this->users, $user);
					$form->setDI($this->di);
					$form->check();

					$this->di->theme->setTitle("Profil");
					$this->di->views->add('users/profile', [
					'title' => "Profile",
					'user' => $user,
					'content' => $form->getHTML(),
					'questions' => $questions,
					'answers' => $answers,
					'comments' => $comments,
					'pageId' => "users/profile/". $id,
			]);
		}
		else{
		header("Location: ../login");
		}
	}
}
