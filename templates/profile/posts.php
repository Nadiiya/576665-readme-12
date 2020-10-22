<section class="profile__posts tabs__content tabs__content--active">
    <h2 class="visually-hidden">Публикации</h2>
    <?php if (!empty($posts)) : ?>
        <?php foreach ($posts as $key => $post) : ?>
            <article class="profile__post post post-<?= $post['class'] ?>">
                <header class="post__header">
                    <div class="post__author">
                        <?php if (!empty($post['original_id'])): ?>
                            <a class="post__author-link"
                               href="/profile.php?user_id=<?= $post['original_post_data']['user_id'] ?>" title="Автор">
                                <div class="post__avatar-wrapper post__avatar-wrapper--repost">
                                    <img class="post__author-avatar" src="<?= $post['original_post_data']['picture'] ?>"
                                         alt="Аватар пользователя">
                                </div>
                                <div class="post__info">
                                    <b class="post__author-name">Репост: <?= esc($post['original_post_data']['login']) ?></b>
                                    <time class="post__time"
                                          datetime="<?= $post['date'] ?>"><?= get_relative_date($post['date']) ?></time>
                                </div>
                            </a>
                        <?php elseif (empty($post['original_id']) && $post['class'] === 'text'): ?>
                        <h2>
                            <a href="/post.php?post_id=<?= $post['post_id'] ?>"><?= esc($post['title']) ?></a>
                        </h2>
                    </div>
                    <?php endif; ?>
                </header>
                <div class="post__main">
                    <?= include_template('post-card-main.php', [
                        'post' => $post,
                    ]) ?>
                </div>
                <footer class="post__footer">
                    <div class="post__indicators">
                        <div class="post__buttons">
                            <a class="post__indicator post__indicator--likes <?= $post['is_liked'] ? 'post__indicator--likes-active' : '' ?> button"
                               href="/like.php?post_id=<?= $post['post_id'] ?>" title="Лайк">
                                <svg class="post__indicator-icon <?= $post['is_liked'] ? 'post__indicator-icon--like-active' : '' ?>"
                                     width="20" height="17">
                                    <use xlink:href="<?= $post['is_liked'] ? '#icon-heart-active' : '#icon-heart' ?>"></use>
                                </svg>
                                <span><?= $post['likes_count'] ?></span>
                                <span class="visually-hidden">количество лайков</span>
                            </a>
                            <a class="post__indicator post__indicator--repost button"
                               href="/repost.php?post_id=<?= $post['post_id'] ?>" title="Репост">
                                <svg class="post__indicator-icon" width="19" height="17">
                                    <use xlink:href="#icon-repost"></use>
                                </svg>
                                <span><?= $post['reposts_count'] ?></span>
                                <span class="visually-hidden">количество репостов</span>
                            </a>
                        </div>
                        <time class="post__time"
                              datetime="<?= $post['date'] ?>"><?= get_relative_date($post['date']) ?></time>
                    </div>
                    <ul class="post__tags">
                        <?php if (!empty($post['tags'])) : ?>
                            <?php foreach ($post['tags'] as $tag): ?>
                                <li><a href="/search.php?q=%23<?= $tag ?>">#<?= $tag ?></a></li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </footer>
                <div class="comments">
                    <a class="comments__button button" href="/post.php?post_id=<?= $post['post_id'] ?>">Показать
                        комментарии</a>
                </div>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
