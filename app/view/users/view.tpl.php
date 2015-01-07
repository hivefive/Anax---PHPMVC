<div class="row">
  <h5>New user</h5>
    <?=$user->name?> 
    <h3><?=$user->acronym?></h3> 
    <h3><?=$user->email?></h3> 
    <h4>Created: <?=$user->created?></h4> 
    <?php if (isset($user->active)): ?> 
        <h5>Active since: <?=$user->active?></h5> 
    <?php endif ?> 
    <?php if (isset($user->deleted)): ?> 
        <h5>In trash since: <?=$user->deleted?></h5> 
    <?php endif ?> 

    <p><a href='<?=$this->url->create('users/list')?>'>List All</a></p> 
</div>