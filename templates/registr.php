<form class="form container<?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($errors)): ?> form--invalid<?php endif; ?>"
      action="/sign_up.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->

    <h2>Регистрация нового аккаунта</h2>

    <div class="form__item<?php if (isset($errors['email']) || isset($errors['users'])): ?> form__item--invalid<?php endif; ?>">
        <!-- form__item--invalid -->
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="registr[email]" placeholder="Введите e-mail"
               value="<?php echo htmlspecialchars($values['email']) ?? ''; ?>" pattern="^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"
               required>
        <span class="form__error"><?php echo isset($errors['users']) ? htmlspecialchars($errors['users']) : htmlspecialchars($errors['email']); ?></span>
    </div>

    <div class="form__item<?php if (isset($errors['password'])): ?> form__item--invalid<?php endif; ?>">
        <label for="password">Пароль*</label>
        <input id="password" type="text" name="registr[password]" placeholder="Введите пароль"
               value="<?php echo htmlspecialchars($values['password']) ?? ''; ?>" required>
        <span class="form__error"><?php echo htmlspecialchars($errors['password']) ?? ''; ?></span>
    </div>

    <div class="form__item<?php if (isset($errors['name'])): ?> form__item--invalid<?php endif; ?>">
        <label for="name">Имя*</label>
        <input id="name" type="text" name="registr[name]" placeholder="Введите имя"
               value="<?php echo htmlspecialchars($values['name']) ?? ''; ?>" required>
        <span class="form__error"><?php echo htmlspecialchars($errors['name']) ?? ''; ?></span>
    </div>

    <div class="form__item<?php if (isset($errors['message'])): ?> form__item--invalid<?php endif; ?>">
        <label for="message">Контактные данные*</label>
        <textarea id="message" name="registr[message]"
                  placeholder="Напишите как с вами связаться"
                  required><?php echo htmlspecialchars($values['message']) ?? ''; ?></textarea>
        <span class="form__error"><?php echo htmlspecialchars($errors['message']) ?? ''; ?></span>
    </div>

    <div class="form__item form__item--file form__item--last">
        <label>Аватар</label>
        <div class="preview">
            <button class="preview__remove" type="button">x</button>
            <div class="preview__img">
                <img src="img/avatar.jpg" width="113" height="113" alt="Ваш аватар">
            </div>
        </div>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" id="photo2" value="" name="registr[photo]">
            <label for="photo2">
                <span>+ Добавить</span>
            </label>
        </div>
    </div>

    <?php if (isset($errors)): ?>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <?php endif; ?>

    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="/sign_in.php">Уже есть аккаунт</a>

</form>