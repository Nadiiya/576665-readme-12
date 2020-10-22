<h2 class="visually-hidden">Подписки</h2>
<?php if (!empty($followers)): ?>
    <ul class="profile__subscriptions-list">
        <?php foreach ($followers as $key => $follower): ?>
            <li class="post-mini post-mini--photo post user">
                <div class="post-mini__user-info user__info">
                    <div class="post-mini__avatar user__avatar">
                        <a class="user__avatar-link" href="/profile.php?user_id=<?= $follower['id'] ?>">
                            <img class="post-mini__picture user__picture" src="<?= $follower['picture'] ?>"
                                 alt="Аватар пользователя">
                        </a>
                    </div>
                    <div class="post-mini__name-wrapper user__name-wrapper">
                        <a class="post-mini__name user__name" href="/profile.php?user_id=<?= $follower['id'] ?>">
                            <span><?= $follower['login'] ?></span>
                        </a>
                        <time class="post-mini__time user__additional"
                              datetime="<?= $follower['registered'] ?>"><?= get_relative_date($follower['registered']) ?>
                            на сайте
                        </time>
                    </div>
                </div>
                <div class="post-mini__rating user__rating">
                    <p class="post-mini__rating-item user__rating-item user__rating-item--publications">
                        <span class="post-mini__rating-amount user__rating-amount"><?= $follower['posts_count'] ?></span>
                        <span class="post-mini__rating-text user__rating-text"><?= get_noun_plural_form($follower['posts_count'],
                                'публикация', 'публикации', 'публикаций') ?></span>
                    </p>
                    <p class="post-mini__rating-item user__rating-item user__rating-item--subscribers">
                        <span class="post-mini__rating-amount user__rating-amount"><?= $follower['followers_count'] ?></span>
                        <span class="post-mini__rating-text user__rating-text"><?= get_noun_plural_form($follower['followers_count'],
                                'подписчик', 'подписчика', 'подписчиков') ?></span>
                    </p>
                </div>

                <div class="post-mini__user-buttons user__buttons">
                    <?php if (!$follower['is_current_user']): ?>
                        <a class="post-mini__user-button user__button user__button--subscription button <?= $follower['is_following'] ? 'button--quartz' : 'button--main' ?>"
                           href="/subscription.php?author_id=<?= $follower['id'] ?>">
                            <?= $follower['is_following'] ? 'Отписаться' : 'Подписаться' ?>
                        </a>
                    <?php endif; ?>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>





