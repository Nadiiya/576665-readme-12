<div class="adding-post__input-wrapper form__textarea-wrapper">
    <label class="adding-post__label form__label" for="cite-text">Текст цитаты <span
                class="form__input-required">*</span></label>
    <div class="form__input-section <?= !empty($errors['content']) ? 'form__input-section--error' : '' ?>">
        <textarea class="adding-post__textarea adding-post__textarea--quote form__textarea form__input" id="cite-text"
                  name="content"
                  placeholder="Текст цитаты"><?= !empty($new_post['content']) ? esc($new_post['content']) : '' ?></textarea>
        <button class="form__error-button button" type="button">!<span
                    class="visually-hidden">Информация об ошибке</span></button>
        <div class="form__error-text">
            <h3 class="form__error-title">Заголовок сообщения</h3>
            <p class="form__error-desc"><?= !empty($errors['content']) ? $errors['content'] : '' ?>></p>
        </div>
    </div>
</div>
<div class="adding-post__textarea-wrapper form__input-wrapper">
    <label class="adding-post__label form__label" for="quote-author">Автор <span
                class="form__input-required">*</span></label>
    <div class="form__input-section <?= !empty($errors['author_quote']) ? 'form__input-section--error' : '' ?>">
        <input class="adding-post__input form__input" id="quote-author" type="text" name="author_quote"
               value="<?= !empty($new_post['author_quote']) ? esc($new_post['author_quote']) : '' ?>">
        <button class="form__error-button button" type="button">!<span
                    class="visually-hidden">Информация об ошибке</span></button>
        <div class="form__error-text">
            <h3 class="form__error-title">Заголовок сообщения</h3>
            <p class="form__error-desc"><?= !empty($errors['author_quote']) ? $errors['author_quote'] : '' ?></p>
        </div>
    </div>
</div>
