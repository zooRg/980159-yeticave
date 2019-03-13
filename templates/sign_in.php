<form class="form container<?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($errors)): ?> form--invalid<? endif; ?>"
      action="/sign_in.php" method="post"> <!-- form--invalid -->
    <h2>Вход</h2>

    <div class="form__item<?php if (isset($errors['email'])): ?> form__item--invalid<? endif; ?>">
        <!-- form__item--invalid -->
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail"
               value="<?php echo $sign['email'] ?? ''; ?>" required>
        <span class="form__error"><?php echo $errors['email'] ?? ''; ?></span>
    </div>

    <div class="form__item<?php if (isset($errors['password'])): ?> form__item--invalid<? endif; ?> form__item--last">
        <label for="password">Пароль*</label>
        <input id="password" type="password" name="password" placeholder="Введите пароль"
               value="" autocomplete="off" required>
        <span class="form__error"><?php echo $errors['password'] ?? ''; ?></span>
    </div>

    <button type="submit" class="button">Войти</button>

</form>