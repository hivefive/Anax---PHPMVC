<h2 class="frontheading"><a href="<?=$this->url->create('questions/add') ?>">Ask a question</a></h2>
</aside>
<h1>Questions</h1>
<?php if (is_array($question)) : ?>
<div class="sort">
Sort by:
<a href="<?=$this->url->create('questions/sort/votes') ?>">Votes</a>
<a href="<?=$this->url->create('questions/sort/nbrOfAnswers') ?>">Answers</a>
<a href="<?=$this->url->create('questions/sort/date') ?>">Date</a>
</div>
<?php $question = array_reverse($question); ?>
<?php foreach ($question as $tmp) : ?>
<?php $gravatar = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($tmp->mail))) . '.jpg?s=50'; ?>
<div class='question'>
<div class="profile right">
<p class="right"><?=$tmp->name?>
</p>
<img class="gravatar" src=<?=$gravatar ?>>
</div>
<h3><a href="<?=$this->url->create('questions/id/' . $tmp->id) ?>"><?=$tmp->title?></a></h3>
<p><span style="color: gray;"><?=$tmp->nbrOfAnswers?> replies </span> |
<?php
if ($tmp->votes >= 0) {
$color = "#5dff00";
$icon = 'fa fa-chevron-up';
} else {
$color = "red";
$icon = 'fa fa-chevron-down';
}
?>
<i style="color: <?=$color?>" class="<?=$icon?>"></i>
<span style="color:<?=$color?>"><?=$tmp->votes?> </span> |
<?php if($tmp->acceptedAnswer == 1): ?>
<i style="color:#5dff00;" class="fa fa-check"></i> |
<?php endif; ?>
</p>
<?php $res = explode(" ", $tmp->tags); ?>
<div class="tags">
<?php foreach ($res as $tag) : ?>
<div class="tag">
<a href="<?=$this->url->create('questions/tagId/' . $tag) ?>"><?=$tag ?></a>
</div>
<?php endforeach; ?>
</div>
</div>
<hr>
<?php endforeach; ?>
<?php endif; ?>