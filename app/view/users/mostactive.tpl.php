<h2 class="frontheading">Mest aktiva användare</h2>
<?php foreach ($mostActive as $user) : ?>
<div class="firstPageProfile">
<?php $gravatar = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($user->email))) . '.jpg?s=50'; ?>
<a href="<?=$this->url->create('users/id/' . $user->id) ?>"><?=$user->acronym?></a>
<img class="gravatar right" src=<?=$gravatar?>>
</div>
<?php endforeach; ?>