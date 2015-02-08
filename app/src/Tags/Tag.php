<?php

namespace Anax\Tags;

/**
 * Model for Users.
 *
 */
class Tag extends \Anax\MVC\CDatabaseModel {


	public function getAllTags() {
    $sql = "SELECT tag FROM test_tags;";
    $allTags =  $this->db->executeFetchAll($sql);
    return $allTags;
  }
  
  
} 