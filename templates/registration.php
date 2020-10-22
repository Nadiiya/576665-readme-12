<main class="page__main page__main--registration">
    <div class="container">
        <h1 class="page__title page__title--registration">Регистрация</h1>
    </div>
    <section class="registration container">
        <h2 class="visually-hidden">Форма регистрации</h2>
        <form class="registration__form form" action="/registration.php" method="post" enctype="multipart/form-data">
            <div class="form__text-inputs-wrapper">
                <div class="form__text-inputs">
                    <div class="registration__input-wrapper form__input-wrapper">
                        <label class="registration__label form__label" for="registration-email">Электронная почта <span
                                    class="form__input-required">*</span></label>
                        <div class="form__input-section <?= !empty($errors['email']) ? 'form__input-section--error' : '' ?>">
                            <input class="registration__input form__input" id="registration-email" type="email"
                                   name="email" placeholder="Укажите эл.почту"
                                   value="<?= !empty($registration_data['email']) ? esc($registration_data['email']) : '' ?>">
                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span>
                            </button>
                            <div class="form__error-text">
                                <h3 class="form__error-title"><?= !empty($errors['email']) ? $error_titles['email'] : '' ?></h3>
                                <p class="form__error-desc"><?= !empty($errors['email']) ? $errors['email'] : '' ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="registration__input-wrapper form__input-wrapper">
                        <label class="registration__label form__label" for="registration-login">Логин <span
                                    class="form__input-required">*</span></label>
                        <div class="form__input-section <?= !empty($errors['login']) ? 'form__input-section--error' : '' ?>">
                            <input class="registration__input form__input" id="registration-login" type="text"
                                   name="login" placeholder="Укажите логин"
                                   value="<?= !empty($registration_data['login']) ? esc($registration_data['login']) : '' ?>">
                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span>
                            </button>
                            <div class="form__error-text">
                                <h3 class="form__error-title"><?= !empty($errors['login']) ? $error_titles['login'] : '' ?></h3>
                                <p class="form__error-desc"><?= !empty($errors['login']) ? $errors['login'] : '' ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="registration__input-wrapper form__input-wrapper">
                        <label class="registration__label form__label" for="registration-password">Пароль<span
                                    class="form__input-required">*</span></label>
                        <div class="form__input-section <?= !empty($errors['password']) ? 'form__input-section--error' : '' ?>">
                            <input class="registration__input form__input" id="registration-password" type="password"
                                   name="password" placeholder="Придумайте пароль" ">
                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span>
                            </button>
                            <div class="form__error-text">
                                <h3 class="form__error-title"><?= !empty($errors['password']) ? $error_titles['password'] : '' ?></h3>
                                <p class="form__error-desc"><?= !empty($errors['password']) ? $errors['password'] : '' ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="registration__input-wrapper form__input-wrapper">
                        <label class="registration__label form__label" for="registration-password-repeat">Повтор
                            пароля<span class="form__input-required">*</span></label>
                        <div class="form__input-section <?= !empty($errors['password_repeat']) ? 'form__input-section--error' : '' ?>">
                            <input class="registration__input form__input" id="registration-password-repeat"
                                   type="password" name="password_repeat" placeholder="Повторите пароль">
                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span>
                            </button>
                            <div class="form__error-text">
                                <h3 class="form__error-title"><?= !empty($errors['password_repeat']) ? $error_titles['password_repeat'] : '' ?></h3>
                                <p class="form__error-desc"><?= !empty($errors['password_repeat']) ? $errors['password_repeat'] : '' ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (!empty($errors)): ?>
                    <div class="form__invalid-block">
                        <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                        <ul class="form__invalid-list">
                            <?php foreach ($errors as $key => $value): ?>
                                <li class="form__invalid-item">
                                    <?= "{$error_titles[$key]}. " ?>
                                    <?= $value ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>

            <div class="registration__input-file-container form__input-container form__input-container--file">
                <div class="registration__input-file-wrapper form__input-file-wrapper">
                    <div class="registration__file-zone form__file-zone dropzone">
                        <input class="registration__input-file form__input-file" id="userpic-file" type="file"
                               name="userpic-file" title=" ">
                        <div class="form__file-zone-text">
                            <span>Перетащите фото сюда</span>
                        </div>
                    </div>
                </div>
                <div class="registration__file form__file dropzone-previews">

                </div>
            </div>
            <button class="registration__submit button button--main" type="submit">Отправить</button>
        </form>
    </section>
</main>