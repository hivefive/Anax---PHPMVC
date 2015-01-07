<hr>

<h2>Comments</h2>


<?php if (is_array($comments)) : ?>
<div class='comments'>
<p><a href=<?=$this->url->create('comment/removeAll/' . $page)?>>Remove all comments</a></p> 
<hr />
<?php foreach ($comments as $id => $comment) : ?>

<img src= "http://www.gravatar.com/avatar/' . md5(strtolower(trim($email))) . '.jpg?s=80">
<p class='commentContent'><?=$comment->content?></p>
<p><span class='nameComment right'><?=$comment->name?></span>
<span class='dateComment right'><?php echo date("Y-m-d ",$comment->timestamp);?></span></p>
<form method='post' class='buttonsComment right'>
    <input type='hidden' name="redirect" value="<?=$this->url->create($redirect)?>"> 
    <input type='hidden' name="key" value="<?=$key ?>">
    <input type='submit' name='doEdit' value='Edit Post' onClick="this.form.action = '<?=$this->url->create('comment/edit') .'/' . $comment->id . '/' . $page?>'" />
    <input type='submit' name='doRemove' value='Delete Post' onClick="this.form.action = '<?=$this->url->create('comment/delete') .'/' . $comment->id . '/' . $page?>'" />
</form>
<p><span class='mailComment clear'>Mail: <?=$comment->mail?></span> <span class='webComment'>Webb: <?=$comment->web?></span></p>


<hr />
<?php endforeach; ?>
</div>
<?php endif; ?> 