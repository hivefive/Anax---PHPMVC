<?php
$currentUrl = $this->di->request->getBaseUrl() . "/" . $this->di->request->getScriptName() . "/" . $this->di->request->getRoute();
$this->session->set("listUrl",$currentUrl);
?>
<h1><?=$title?></h1>
 
<table class="userTable">
<tr class='options'>
	<td><a href='<?=$this->url->create('user')?>'>Visa alla användare</a></td>
	<td><a href='<?=$this->url->create('users/listActive')?>'>Visa aktiva användare</a></td>
	<td><a href='<?=$this->url->create('users/listInActive')?>'>Visa inaktiva användare</a></td>
	<td><a href='<?=$this->url->create('users/add')?>'>Lägg till ny användare</a></td>
</tr>
<tr>
	<th>Username</th>
	<th>Email</th>
	<th>Name</th>
	<th>Created</th>
	<th>Active since</th>
</tr>
<?php foreach ($users as $user) : ?>

<?$user->getProperties()?>
<tr>
	<td><a href="<?=$this->url->create('users/profile/' . $user->id) ?>"><?=$user->acronym?></a></td>
	<td><?=htmlentities($user->email)?></td>
	<td><?=htmlentities($user->name)?></td>
	<td><?=htmlentities($user->created)?></td>
	<td><?=htmlentities($user->active)?></td>

</tr>
<?php endforeach; ?>
</table>
 
