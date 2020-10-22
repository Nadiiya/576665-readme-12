<?php

switch ($post['class']):
    case TEXT: ?>
        <?php $post_content = esc($post['content']) ?>
        <p>
            <?= cut_text($post_content) ?>
        </p>
        <?php if ($post_content !== cut_text($post_content)) : ?>
            <a class="post-text__more-link"
               href="/post.php?post_id=<?= $post['post_id'] ?>">Читать далее</a>
        <?php endif; ?>
        <?php break; ?>
    <?php case VIDEO: ?>
    <div class="post-video__block">
        <div class="post-video__preview">
            <?= embed_youtube_video(esc($post['video'])); ?>
        </div>
    </div>
    <?php break; ?>
<?php case QUOTE: ?>
    <blockquote>
        <p>
            <?= esc($post['content']) ?>
        </p>
        <cite><?= esc($post['author_quote']) ?></cite>
    </blockquote>
    <?php break; ?>
<?php case LINK: ?>
    <div class="post-link__wrapper">
        <a class="post-link__external" href="<?= esc($post['link']) ?>"
           title="Перейти по ссылке">
            <div class="post-link__icon-wrapper">
                <img
                        src="https://www.google.com/s2/favicons?domain=<?= esc($post['link']) ?>"
                        alt="Иконка">
            </div>
            <div class="post-link__info">
                <h3><?= esc($post['title']) ?></h3>
                <span><?= esc($post['link']) ?></span>
            </div>
            <svg class="post-link__arrow" width="11" height="16">
                <use xlink:href="#icon-arrow-right-ad"></use>
            </svg>
        </a>
    </div>
    <?php break; ?>
<?php case PHOTO: ?>
    <h2>
        <a href="/post.php?post_id=<?= $post['post_id'] ?>"><?= esc($post['title']) ?></a>
    </h2>
    <div class="post-photo__image-wrapper">
        <img src="<?= esc($post['img']) ?>" alt="Фото от пользователя" width="760"
             height="396">
    </div>
    <?php break; ?>
<?php endswitch; ?>
