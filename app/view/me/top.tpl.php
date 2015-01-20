<?php if($this->di->session->get('userId')): ?>
<a href="<?=$this->url->create('logout')?>">Logout |</a>
<?php $id = $this->di->session->get('userId');?>
<a href="<?=$this->url->create('users/profile/'.$id)?>">Profile </a>
<?php endif; ?>
<?php if(!$this->di->session->get('userId')) : ?>
<a href="<?=$this->url->create('login')?>">Login |</a>
<a href="<?=$this->url->create('users/add')?>">Register</a>
<?php endif; ?>