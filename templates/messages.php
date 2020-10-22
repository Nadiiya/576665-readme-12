<main class="page__main page__main--messages">
    <h1 class="visually-hidden">Личные сообщения</h1>
    <section class="messages tabs">
        <h2 class="visually-hidden">Сообщения</h2>
        <div class="messages__contacts">
            <?php if (!empty($contacts)): ?>
                <ul class="messages__contacts-list tabs__list">
                    <?php foreach ($contacts as $contact_id => $contact): ?>
                        <li class="messages__contacts-item">
                            <a class="messages__contacts-tab <?= $current_contact === $contact_id ? 'messages__contacts-tab--active tabs__item--active' : '' ?> tabs__item "
                               href="/messages.php?contact_id=<?= $contact_id ?>">
                                <div class="messages__avatar-wrapper">
                                    <img class="messages__avatar" src="<?= esc($contact['picture']) ?>"
                                         alt="Аватар пользователя">
                                    <?php if (isset($contact['unread_count']) && $contact['unread_count'] !== 0): ?>
                                        <i class="messages__indicator"><?= $contact['unread_count'] ?></i>
                                    <?php endif; ?>
                                </div>
                                <div class="messages__info">
                                    <span class="messages__contact-name"><?= esc($contact['login']) ?></span>
                                    <div class="messages__preview">
                                        <p class="messages__preview-text">
                                            <?= !empty($contact['last_message']) ? esc(cut_text($contact['last_message']['content'],
                                                20)) : '' ?>
                                        </p>
                                        <time class="messages__preview-time"
                                              datetime="<?= !empty($contact['last_message']) ? $contact['last_message']['date'] : '' ?>">
                                            <?= !empty($contact['last_message']) ? get_relative_date($contact['last_message']['date']) : '' ?>
                                        </time>
                                    </div>
                                </div>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <div class="messages__chat">
            <div class="messages__chat-wrapper">
                <?php if (!empty($conversations)): ?>
                <?php foreach ($conversations as $contact_id => $conversation): ?>
                    <ul class="messages__list tabs__content <?= $current_contact === $contact_id ? 'tabs__content--active' : '' ?>">
                        <?php foreach ($conversation as $key => $message): ?>
                            <li class="messages__item <?= $message['user_sender_id'] === $current_user['id'] ? 'messages__item--my' : '' ?>">
                                <div class="messages__info-wrapper">
                                    <div class="messages__item-avatar">
                                        <a class="messages__author-link"
                                           href="/profile.php?user_id=<?= $message['user_sender_id'] ?>">
                                            <img class="messages__avatar" src="<?= esc($message['sender_picture']) ?>"
                                                 alt="Аватар пользователя">
                                        </a>
                                    </div>
                                    <div class="messages__item-info">
                                        <a class="messages__author"
                                           href="/profile.php?user_id=<?= $message['user_sender_id'] ?>">
                                            <?= esc($message['sender_name']) ?>
                                        </a>
                                        <time class="messages__time" datetime="<?= $message['date'] ?>">
                                            <?= get_relative_date($message['date']) ?>
                                        </time>
                                    </div>
                                </div>
                                <p class="messages__text">
                                    <?= esc($message['content']) ?>
                                </p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <?php if (!empty($contacts)): ?>
                <div class="comments">
                    <form class="comments__form form" action="/messages.php" method="post">
                        <div class="comments__my-avatar">
                            <img class="comments__picture" src="<?= esc($current_user['picture']) ?>"
                                 alt="Аватар пользователя">
                        </div>
                        <div class="form__input-section <?= !empty($errors) ? 'form__input-section--error' : '' ?> ">
                            <textarea class="comments__textarea form__textarea form__input" id="comment"
                                      placeholder="Ваше сообщение"
                                      name="content"><?= !empty($new_message['content']) ? esc($new_message['content']) : '' ?>
                            </textarea>
                            <label for="comment" class="visually-hidden">Ваше сообщение</label>
                            <input type="hidden" name="recipient_id" value="<?= $current_contact ?? '' ?>">
                            <button class="form__error-button button" type="button">!</button>
                            <?php if (!empty($errors)): ?>
                                <div class="form__error-text">
                                    <h3 class="form__error-title">Ошибка валидации</h3>
                                    <?php foreach ($errors as $error): ?>
                                        <p class="form__error-desc"><?= $error ?></p>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <button class="comments__submit button button--green" type="submit">Отправить</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>
