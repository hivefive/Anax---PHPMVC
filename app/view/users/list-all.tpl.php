<?php
$currentUrl = $this->di->request->getBaseUrl() . "/" . $this->di->request->getScriptName() . "/" . $this->di->request->getRoute();
$this->session->set("listUrl",$currentUrl);
?>
<h1><?=$title?></h1>
 
<table class="userTable">
<tr class='resetUsers'>
	<td><a href='<?=$this->url->create('users/reset')?>'>Återställ</a></td>
</tr>
<tr class='options'>
	<td><a href='<?=$this->url->create('user')?>'>Visa alla användare</a></td>
	<td><a href='<?=$this->url->create('users/listActive')?>'>Visa aktiva användare</a></td>
	<td><a href='<?=$this->url->create('users/listInActive')?>'>Visa inaktiva användare</a></td>
	<td><a href='<?=$this->url->create('users/listSoftDeleted')?>'>Visa soft-raderade</a></td>
	<td><a href='<?=$this->url->create('users/listNotSoftDeleted')?>'>Visa icke soft-raderade</a></td>
	<td><a href='<?=$this->url->create('users/showCreateForm')?>'>Lägg till ny användare</a></td>
</tr>
<tr>
	<th>Username</th>
	<th>Email</th>
	<th>Name</th>
	<th>Created</th>
	<th>Active since</th>
	<th>Change status</th>
	<th>Soft delete</th>
	<th>Delete</th>
	<th>Update</th>
</tr>
<?php foreach ($users as $user) : ?>

<?$user->getProperties()?>
<tr>
	<td><a href='<?=$this->url->create('users/Update')?>/<?=$user->id?>'><?=htmlentities($user->acronym)?></a></td>
	<td><?=htmlentities($user->email)?></td>
	<td><?=htmlentities($user->name)?></td>
	<td><?=htmlentities($user->created)?></td>
	<td><?=htmlentities($user->active)?></td>
	<td><?php $url = $this->url->create('users/active/' . $user->id);
				if(isset($user->active)) {
					echo "<p><a href='$url'>Ändra till inaktiv</a></p>";
					}
				else {
					echo "<p><a href='$url'>Ändra till aktiv</a></p>";
					} 
	?></td>
	<td>
        <?php
            $url = $this->url->create('users/softDelete/' . $user->id);
            if(isset($user->deleted)) {
                echo "<p><a href='$url'>undo-soft-radering</a></p>";
            }
            else {
                echo "<p><a href='$url'>soft-radera</a></p>";
            } 
        ?>    
    </td>
    <td>
        <?php
            $url = $this->url->create('users/delete/' . $user->id);
            echo "<p><a href='$url'>ta bort</a></p>";
        ?>
    </td>
    <td>
        <?php
            $url = $this->url->create('users/update/' . $user->id);
            echo "<p><a href='$url'>updatera</a></p>";
        ?>
    </td>
</tr>
<?php endforeach; ?>
</table>
 
<p><a href='<?=$this->url->create('')?>'>Home</a></p>