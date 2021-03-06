<h2 class="frontheading"><a href='<?=$this->url->create(); ?>/ask'>Latest questions</a></h2>
<?php foreach($questions as $question): ?>
<?php
$date1 = $question->timestamp;
$date2 = date('Y-m-d H:i:s');
$ts1 = strtotime($date1);
$ts2 = strtotime($date2);
$seconds_diff = $ts2 - $ts1;
if ($seconds_diff < 86400) {
$date = ' today';
} else {
$date = $seconds_diff / 86400;
$date = round($date, 1);
$date .= " days ago";
}
?>
<?php $gravatar = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($question->mail))) . '.jpg?s=17'; ?>
<div class="firstPageQuestions">
<h3><a href="<?=$this->url->create('questions/id/' . $question->id) ?>"><?=$question->title?></a>
<span class="timestamp">- <?=$date ?></span></h3>
<p><img class="gravatar" src=<?=$gravatar ?>>
<?=$question->name?></p>
</div>
<hr>
<?php endforeach; ?>
<div class="mostUsedTags">
<h2>Most used tags</h2>
<?php foreach($mostUsedTags as $tag): ?>
<div class="tag">
<a href="<?=$this->url->create('questions/tagId/' . $tag->tag) ?>"><?=$tag->tag ?></a>
</div>
<?php endforeach; ?>
</div>