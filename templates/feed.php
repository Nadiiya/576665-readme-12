<main class="page__main page__main--feed">
    <div class="container">
        <h1 class="page__title page__title--feed">Моя лента</h1>
    </div>
    <div class="page__main-wrapper container">
        <section class="feed">
            <h2 class="visually-hidden">Лента</h2>
            <div class="feed__main-wrapper">
                <div class="feed__wrapper">
                    <?php if ($posts): ?>
                        <?php foreach ($posts as $post): ?>
                            <article class="feed__post post post-<?= $post['class'] ?>">
                                <header class="post__header post__author">
                                    <a class="post__author-link" href="/profile.php?user_id=<?= $post['user_id'] ?>"
                                       title="Автор">
                                        <div class="post__avatar-wrapper">
                                            <img class="post__author-avatar" src="<?= $post['picture'] ?>"
                                                 alt="Аватар пользователя" width="60" height="60">
                                        </div>
                                        <div class="post__info">
                                            <b class="post__author-name"><?= $post['login'] ?></b>
                                            <span
                                                    class="post__time"><?= get_relative_date($post['date']) ?> назад</span>
                                        </div>
                                    </a>
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
                                                <svg
                                                        class="post__indicator-icon <?= $post['is_liked'] ? 'post__indicator-icon--like-active' : '' ?>"
                                                        width="20" height="17">
                                                    <use
                                                            xlink:href="<?= $post['is_liked'] ? '#icon-heart-active' : '#icon-heart' ?>"></use>
                                                </svg>
                                                <span><?= $post['likes_count'] ?></span>
                                                <span class="visually-hidden">количество лайков</span>
                                            </a>
                                            <a class="post__indicator post__indicator--comments button"
                                               href="/post.php?post_id=<?= $post['post_id'] ?>"
                                               title="Комментарии">
                                                <svg class="post__indicator-icon" width="19" height="17">
                                                    <use xlink:href="#icon-comment"></use>
                                                </svg>
                                                <span><?= $post['comments_count'] ?></span>
                                                <span class="visually-hidden">количество комментариев</span>
                                            </a>
                                            <a class="post__indicator post__indicator--repost button"
                                               href="/repost.php?post_id=<?= $post['post_id'] ?>"
                                               title="Репост">
                                                <svg class="post__indicator-icon" width="19" height="17">
                                                    <use xlink:href="#icon-repost"></use>
                                                </svg>
                                                <span><?= $post['reposts_count'] ?></span>
                                                <span class="visually-hidden">количество репостов</span>
                                            </a>
                                        </div>
                                    </div>
                                    <ul class="post__tags">
                                        <?php if (!empty($post['tags'])) : ?>
                                            <?php foreach ($post['tags'] as $tag): ?>
                                                <li><a href="/search.php?q=%23<?= $tag ?>">#<?= $tag ?></a></li>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </ul>
                                </footer>
                            </article>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <ul class="feed__filters filters">
                <li class="feed__filters-item filters__item">
                    <a class="filters__button <?= empty($active_post_type) ? 'filters__button--active' : '' ?>"
                       href="/">
                        <span>Все</span>
                    </a>
                </li>
                <?php foreach ($post_types as $post_type): ?>
                    <li class="feed__filters-item filters__item">
                        <a class="filters__button filters__button--<?= $post_type['class'] ?> <?= $active_post_type === $post_type['id'] ? 'filters__button--active' : '' ?> button"
                           href="/feed.php?post_type=<?= $post_type['id'] ?>">
                            <span class="visually-hidden"><?= $post_type['name'] ?></span>
                            <svg class="filters__icon" width="22" height="18">
                                <use xlink:href="#icon-filter-<?= $post_type['class'] ?>"></use>
                            </svg>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
        <aside class="promo">
            <article class="promo__block promo__block--barbershop">
                <h2 class="visually-hidden">Рекламный блок</h2>
                <p class="promo__text">
                    Все еще сидишь на окладе в офисе? Открой свой барбершоп по нашей франшизе!
                </p>
                <a class="promo__link" href="#">
                    Подробнее
                </a>
            </article>
            <article class="promo__block promo__block--technomart">
                <h2 class="visually-hidden">Рекламный блок</h2>
                <p class="promo__text">
                    Товары будущего уже сегодня в онлайн-сторе Техномарт!
                </p>
                <a class="promo__link" href="#">
                    Перейти в магазин
                </a>
            </article>
            <article class="promo__block">
                <h2 class="visually-hidden">Рекламный блок</h2>
                <p class="promo__text">
                    Здесь<br> могла быть<br> ваша реклама
                </p>
                <a class="promo__link" href="#">
                    Разместить
                </a>
            </article>
        </aside>
    </div>
</main>
