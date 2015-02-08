<h2 class="frontheading"><a href="<?=$this->url->create('questions/answer/' . $question->id) ?>">Answer the question</a></h2>
<?php if (isset($question)): ?>
<h1>Fråga: <?=$question->title ?></h1>
<div class="question">
<div class="profile right">
<?php $gravatar = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($question->mail))) . '.jpg?s=50'; ?>
<p class="right"><?=$question->name?></p>
<img class="gravatar" src=<?=$gravatar ?>>
</div>
<p><?=$question->question ?></p>
<div class="votes">
<?php
if($question->votes >= 0) {
$color = "#5dff00";
} else {
$color = "red";
}
?>
<p><a class="left" href="<?=$this->url->create('questions/upVote/' . $id) ?>"><i style="color:#5dff00;" class="fa fa-chevron-up "></i></a></p>
<p style="color:<?=$color?>" class="left"><?=$question->votes?></p>
<p><a class="left" href="<?=$this->url->create('questions/downVote/' . $id) ?>"><i style="color: red;" class="fa fa-chevron-down red"></i></a></p>
</div>
<?php
$tags = explode("\n", str_replace(' ', '', $question->tags));
?>
<div class="tags">
<?php foreach($tags as $tag): ?>
<div class="tag">
<a href="<?=$this->url->create('questions/tagId/' . $tag) ?>"><?=$tag ?></a>
</div>
<?php endforeach; ?>
</div>
<br>
<br>
<br>
<a class="right" href="<?=$this->url->create('questions/comment/' . $id) ?>"><h3 class="frontheading">Comment</h3></a>
</div>
<!-- -------------------------------------------------- COMMENTS ---------------------------------------------------- -->
<div class="comments">
<?php if(isset($comments)): ?>
<?php foreach($comments as $comment): ?>
<?php
$date1 = $comment->posted;
$date2 = date('Y-m-d H:i:s');
$ts1 = strtotime($date1);
$ts2 = strtotime($date2);
$seconds_diff = $ts2 - $ts1;
if ($seconds_diff < 43200) {
$date = ' today';
} else {
$date = $seconds_diff / 86400;
$date = round($date, 0);
$date .= "days ago";
}
?>
<?php
foreach($commentators as $commentator) {
if ($commentator->user == $comment->user) {
$commentatorMail = $commentator->email;
}
}
?>
<div class="votes right">
<?php
if($comment->votes >= 0) {
$color = "#5dff00";
} else {
$color = "red";
}
?>
<p style="color:<?=$color?>" class="left padd"><?=$comment->votes?>
<a class="left" href="<?=$this->url->create('questions/upVoteCommentOnQuestion/' . $comment->commentID . '/' . $comment->id) ?>"><i style="color:#5dff00;" class="fa fa-chevron-up "></i></a>
<a class="left" href="<?=$this->url->create('questions/downVoteCommentOnQuestion/' . $comment->commentID . '/' . $comment->id) ?>"><i style="color: red;" class="fa fa-chevron-down red"></i></a></p>
</div>
<?php $realComment = strip_tags($comment->comment, '<a><i><b><strong><em>'); ?>
<?php $gravatar = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($commentatorMail))) . 'jpg?s=17'; ?>
<div class="comment">
<p><img class="gravatar" src=<?=$gravatar ?>> <?=$realComment; ?> -<span class="timestamp"><?=$date ?></span><p>- <?=$comment->user?></p></p>
</div>
<?php endforeach; ?>
<?php endif; ?>
</div>
<?php endif; ?>
<!-- -------------------------------------------------- ANSWERS ---------------------------------------------------- -->
<div class="answers">
<?php if (isset($answers)): ?>
<h5>Answers:</h5>
<div class="sort">
Sort:
<a href="<?=$this->url->create('questions/sortAnswers/' . $id . '/votes') ?>">Votes</a>
<a href="<?=$this->url->create('questions/sortAnswers/' .$id .'/date') ?>">Date</a>
</div>
<?php foreach($answers as $answer): ?>
<!-- <?php
$date1 = $answer->timestamp;
$date2 = date('Y-m-d H:i:s');
$ts1 = strtotime($date1);
$ts2 = strtotime($date2);
$seconds_diff = $ts2 - $ts1;
if ($seconds_diff < 43200) {
$date = ' today';
} else {
$date = $seconds_diff / 86400;
$date = round($date, 0);
$date .= " days ago";
}
?> -->
<?php
$thisUser = $answer->user;
$thisAnswer = $answer->answer;
$thisId = $answer->id;
?>
<?php
foreach($contributors as $contributor) {
if ($contributor->user == $thisUser) {
$contributorName = $contributor->user;
$contributorId = $contributor->id;
$contributorMail = $contributor->email;
}
}
?>
<div class="answer">
<div class="profile right">
<?php $gravatar = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($contributorMail))) . '.jpg?s=50'; ?>
<p class="right"><a href="<?=$this->url->create('users/profile/' . $contributorId) ?>"><?=$contributorName?></a>
</p>
<img class="gravatar" src=<?=$gravatar ?>>
</div>
<?php if(isset($hasAcceptedAnswer) && $hasAcceptedAnswer[1] == $thisId): ?>
<i style="color:#5dff00;" class="fa fa-check fa-2x left"></i>
<?php endif; ?>
<p>Svar: <?=$thisAnswer ?></p><br>
<div class="votes1">
<?php
if($answer->votes >= 0) {
$color = "#5dff00";
} else {
$color = "red";
}
?>
<p><a class="left" href="<?=$this->url->create('questions/upVoteAnswer/' . $answer->questionID . '/' . $thisId) ?>"><i style="color:#5dff00;" class="fa fa-chevron-up "></i></a></p>
<p style="color:<?=$color?>" class="left"><?=$answer->votes?></p>
<p><a class="left" href="<?=$this->url->create('questions/downVoteAnswer/' . $answer->questionID . '/' . $thisId) ?>"><i style="color: red;" class="fa fa-chevron-down red"></i></a></p>
<a class="right" href="<?=$this->url->create('questions/commentOnAnswer/' . $thisId . '/' . $question->id) ?>">Comment</a><br>
<a class="right" href="<?=$this->url->create('questions/accept/' . $thisId . '/' . $question->id . '/' . $question->name) ?>">Accept</a>
</div>
<?php if(isset($commentsOnAnswers)): ?>
<div class="commentsOnAnswers">
<?php foreach($commentsOnAnswers as $comment): ?>
<?php if($comment->id == $answer->id): ?>
<?php $gravatar = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($contributorMail))) . '.jpg?s=17'; ?>
<div class="comment">
<div class="votes right">
<?php
if($comment->votes >= 0) {
$color = "#5dff00";
} else {
$color = "red";
}
?>
<p style="color:<?=$color?>" class="left "><?=$comment->votes?>
<a class="left" href="<?=$this->url->create('questions/upVoteCommentOnAnswer/' . $comment->commentID . '/' . $question->id) ?>"><i style="color:#5dff00;" class="fa fa-chevron-up "></i></a>
<a class="left" href="<?=$this->url->create('questions/downVoteCommentOnAnswer/' . $comment->commentID . '/' . $question->id) ?>"><i style="color: red;" class="fa fa-chevron-down red"></i></a></p>
</div>
<?php $realComment = strip_tags($comment->comment, '<a><i><b><strong><em>'); ?>
<p><img class="gravatar" src=<?=$gravatar ?>> <?=$realComment ?> -<span class="timestamp"><?=$date ?></span></p>
</div>
<?php endif; ?>
<?php endforeach; ?>
</div>
<?php endif; ?>
</div>
<?php endforeach; ?>
<?php endif; ?>
<?php if (isset($form)): ?>
<div class="answerForm">
<p><?=$form ?></p>
</div>
<?php endif; ?>
</div>