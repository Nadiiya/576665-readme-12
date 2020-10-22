<!-- пост-цитата -->
<div class="post-details__image-wrapper post-quote">
    <div class="post__main">
        <blockquote>
            <p>
                <?= esc($post['content']) ?>
            </p>
            <cite><?= esc($post['author_quote']) ?></cite>
        </blockquote>
    </div>
</div>
