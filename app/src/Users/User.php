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
}