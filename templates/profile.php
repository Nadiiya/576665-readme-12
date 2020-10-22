<main class="page__main page__main--profile">
    <h1 class="visually-hidden">Профиль</h1>
    <div class="profile profile--default">
        <div class="profile__user-wrapper">
            <div class="profile__user user container">
                <div class="profile__user-info user__info">
                    <div class="profile__avatar user__avatar">
                        <img class="profile__picture user__picture"
                             src="<?= $profile_data['picture'] ? $profile_data['picture'] : '/img/icon-input-user.svg' ?>"
                             alt="Аватар пользователя">
                    </div>
                    <div class="profile__name-wrapper user__name-wrapper">
                        <span class="profile__name user__name"><?= $profile_data['login'] ?></span>
                        <time class="profile__user-time user__time"
                              datetime="<?= $profile_data['registered'] ?>"><?= get_relative_date($profile_data['registered']) ?>
                            на сайте
                        </time>
                    </div>
                </div>
                <div class="profile__rating user__rating">
                    <p class="profile__rating-item user__rating-item user__rating-item--publications">
                        <span class="user__rating-amount"><?= $profile_data['posts_count'] ?></span>
                        <span class="profile__rating-text user__rating-text"><?= get_noun_plural_form($profile_data['posts_count'],
                                'публикация', 'публикации', 'публикаций') ?></span>
                    </p>
                    <p class="profile__rating-item user__rating-item user__rating-item--subscribers">
                        <span class="user__rating-amount"><?= $profile_data['followers_count'] ?></span>
                        <span class="profile__rating-text user__rating-text"><?= get_noun_plural_form($profile_data['followers_count'],
                                'подписчик', 'подписчика', 'подписчиков') ?></span>
                    </p>
                </div>
                <div class="profile__user-buttons user__buttons">
                    <?php if (!$profile_data['is_current_user']): ?>
                        <a class="profile__user-button user__button user__button--subscription button <?= ($profile_data['is_following']) ? 'button--quartz' : 'button--main' ?>"
                           href="/subscription.php?author_id=<?= $profile_data['id'] ?>">
                            <?= ($profile_data['is_following']) ? 'Отписаться' : 'Подписаться' ?>
                        </a>
                        <?php if ($profile_data['is_following']): ?>
                            <a class="profile__user-button user__button user__button--writing button button--green"
                               href="/messages.php?contact_id=<?= $profile_data['id'] ?>">Сообщение</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="profile__tabs-wrapper tabs">
            <div class="container">
                <div class="profile__tabs filters">
                    <b class="profile__tabs-caption filters__caption">Показать:</b>
                    <ul class="profile__tabs-list filters__list tabs__list">
                        <li class="profile__tabs-item filters__item">
                            <a class="profile__tabs-link filters__button  tabs__item button <?= $current_tab === 'posts' ? 'filters__button--active tabs__item--active' : '' ?> "
                               href="/profile.php?<?= get_query_string($_GET, ['tab' => 'posts']) ?>">Посты</a>
                        </li>
                        <li class="profile__tabs-item filters__item">
                            <a class="profile__tabs-link filters__button tabs__item button <?= $current_tab === 'likes' ? 'filters__button--active tabs__item--active' : '' ?>"
                               href="/profile.php?<?= get_query_string($_GET, ['tab' => 'likes']) ?>">Лайки</a>
                        </li>
                        <li class="profile__tabs-item filters__item">
                            <a class="profile__tabs-link filters__button tabs__item button <?= $current_tab === 'subscriptions' ? 'filters__button--active tabs__item--active' : '' ?>"
                               href="/profile.php?<?= get_query_string($_GET,
                                   ['tab' => 'subscriptions']) ?>">Подписки</a>
                        </li>
                    </ul>
                </div>
                <div class="profile__tab-content">
                    <section class="profile__<?= $current_tab ?> tabs__content tabs__content--active">
                        <h2 class="visually-hidden">Публикации</h2>
                        <?= $tabs_content ?>
                    </section>
                </div>
            </div>
        </div>
    </div>
</main>
