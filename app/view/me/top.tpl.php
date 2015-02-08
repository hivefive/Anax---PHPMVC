<?php if($this->di->session->get('userId')): ?>
<?php $id = $this->di->session->get('userId');?>
<p class="loginMenu right"><a href="<?=$this->url->create('users/profile/'.$id)?>">Profile | </a>
<a href="<?=$this->url->create('logout')?>">Logout</a>
</p>
<?php endif; ?>
<?php if(!$this->di->session->get('userId')) : ?>
<p class="loginMenu right"><a href="<?=$this->url->create('users/add')?>">Register | </a><a href="<?=$this->url->create('login')?>">Login</a>
</p>
<?php endif; ?>