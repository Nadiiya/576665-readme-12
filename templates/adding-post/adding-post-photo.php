<div class="adding-post__input-wrapper form__input-wrapper">
    <label class="adding-post__label form__label" for="photo-url">Ссылка из интернета</label>
    <div class="form__input-section <?= !empty($errors['img']) ? 'form__input-section--error' : '' ?>">
        <input class="adding-post__input form__input" id="photo-url" type="text" name="img"
               placeholder="Введите ссылку" value="<?= !empty($new_post['img']) ? esc($new_post['img']) : '' ?>">
        <button class="form__error-button button" type="button">!<span
                    class="visually-hidden">Информация об ошибке</span></button>
        <div class="form__error-text">
            <h3 class="form__error-title">Заголовок сообщения</h3>
            <p class="form__error-desc"><?= !empty($errors['img']) ? $errors['img'] : '' ?></p>
        </div>
    </div>
</div>

<div class="adding-post__input-file-container form__input-container form__input-container--file">
    <div class="adding-post__input-file-wrapper form__input-file-wrapper">
        <div class="adding-post__file-zone adding-post__file-zone--photo form__file-zone dropzone">
            <input class="adding-post__input-file form__input-file" id="upload-photo" type="file"
                   name="upload_photo" title=" ">
            <div class="form__file-zone-text">
                <span>Перетащите фото сюда</span>
            </div>
        </div>
    </div>
    <div class="adding-post__file adding-post__file--photo form__file dropzone-previews">
    </div>
</div>
