<?php

namespace Anax\Users;
 
/**
 * Model for Users.
 */
class User extends \Anax\MVC\CDatabaseModel
{
	public function getMostLoggedOn() {
		$sql = "SELECT * FROM test_user
		ORDER BY timesLoggedOn DESC LIMIT 3;";
		$mostLoggedOn = $this->db->executeFetchAll($sql);
		return $mostLoggedOn;
	}
	
	public function findIdByAcronym($acronym) {
    $this->db->select()
    ->from($this->getSource())
    ->where("acronym = ?");

    $this->db->execute([$acronym]);

    $this->db->fetchInto($this);
    return $this->id;
  }
  public function getQuestions($acronym) {
    $sql = "SELECT * FROM test_user
              JOIN test_question
                  ON test_question.name=test_user.acronym";
    $questions =  $this->db->executeFetchAll($sql);
    $res = array();
    foreach($questions as $question) {
      if ($question->acronym == $acronym) {
        array_push($res, $question);
      }
    }
    return $res;
  }
  public function getAnswers($acronym) {
    $sql = "SELECT * FROM test_answer WHERE user = ?";
    $this->db->execute([$acronym]);
	$answers = $this->db->fetchInto($this);
    
    return $answers;
  }
  public function linkAnswerToQuestion($acronym) {
    $sql = "SELECT * FROM test_question
              JOIN test_answer
                ON test_answer.questionID=test_question.id";
    $answers =  $this->db->executeFetchAll($sql);
    $res = array();
    foreach($answers as $answer) {
      if ($answer->user == $acronym) {
        array_push($res, $answer);
      }
    }
    return $res;
  }
  public function isAuthenticated($user) {
      if(isset($_SESSION['acronym'])) {
	  if($_SESSION['acronym'] == $user->acronym){
        return true;
      }else{
        return false;
      }
	}

  }
}