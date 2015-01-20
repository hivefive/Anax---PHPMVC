<h2>Questions</h2>

<?php if (is_array($questions)) : ?>
<div class='questions'>
<?php foreach ($questions as $id => $question) : ?>

<p class='commentContent'><?=$question->content?></p>

<?php endforeach; ?>
</div>
<?php endif; ?> 