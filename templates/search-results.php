<main class="page__main page__main--search-results">
    <h1 class="visually-hidden">Страница результатов поиска<?= empty($posts) ? ': (нет результатов)' : '' ?></h1>
    <section class="search">
        <h2 class="visually-hidden">Результаты поиска</h2>
        <div class="search__query-wrapper">
            <div class="search__query container">
                <span>Вы искали:</span>
                <span class="search__query-text"><?= esc($search_query) ?></span>
            </div>
        </div>
        <div class="search__results-wrapper">
            <div class="container">
                <div class="search__content">
                    <?php if (!empty($posts)): ?>
                        <?php foreach ($posts as $post): ?>
                            <article class="search__post post post-<?= $post['class'] ?>">
                                <header class="post__header post__author">
                                    <a class="post__author-link" href="/profile.php?user_id=<?= $post['user_id'] ?>"
                                       title="Автор">
                                        <div class="post__avatar-wrapper">
                                            <img class="post__author-avatar" src="<?= $post['picture'] ?>"
                                                 alt="Аватар пользователя" width="60" height="60">
                                        </div>
                                        <div class="post__info">
                                            <b class="post__author-name"><?= $post['login'] ?></b>
                                            <span class="post__time"><?= get_relative_date($post['date']) ?> назад</span>
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
                                                <svg class="post__indicator-icon <?= $post['is_liked'] ? 'post__indicator-icon--like-active' : '' ?>"
                                                     width="20" height="17">
                                                    <use xlink:href="<?= $post['is_liked'] ? '#icon-heart-active' : '#icon-heart' ?>"></use>
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
                    <?php else: ?>
                        <div class="search__results-wrapper">
                            <div class="search__no-results container">
                                <p class="search__no-results-info">К сожалению, ничего не найдено.</p>
                                <p class="search__no-results-desc">
                                    Попробуйте изменить поисковый запрос или просто зайти в раздел &laquo;Популярное&raquo;,
                                    там живет самый крутой контент.
                                </p>
                                <div class="search__links">
                                    <a class="search__popular-link button button--main"
                                       href="/popular.php">Популярное</a>
                                    <a class="search__back-link" href="#">Вернуться назад</a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</main>
