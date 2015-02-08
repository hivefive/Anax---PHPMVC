<h2 class="frontheading"><a href='<?=$this->url->create(); ?>/ask'>Ask a question</a></h2>
<?php if (is_array($tags)) : ?>
<h2>All tags</h2>
<div class="allTags">
<?php foreach ($tags as $tag) : ?>
<div class="tag">
<a href="<?=$this->url->create('questions/tagId/' . $tag) ?>"><?=$tag ?></a>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>