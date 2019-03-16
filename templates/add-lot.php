<form class="form form--add-lot container<?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($errors)): ?> form--invalid<?php endif; ?>"
      action="/add.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
    <h2>Добавление лота</h2>
    <div class="form__container-two">

        <div class="form__item<?php if (isset($errors['name'])): ?> form__item--invalid<?php endif; ?>">
            <!-- form__item--invalid -->
            <label for="lot-name">Наименование</label>
            <input id="lot-name" type="text" name="lot[name]" placeholder="Введите наименование лота"
                   value="<?php echo htmlspecialchars($values['name'] ?? ''); ?>" required>
            <span class="form__error"><?php echo htmlspecialchars($errors['name'] ?? ''); ?></span>
        </div>

        <div class="form__item<?php if (isset($errors['category'])): ?> form__item--invalid<?php endif; ?>">
            <label for="category">Категория</label>
            <select id="category" name="lot[category]" required>
                <option value="">Выберите категорию</option>
                <?php if (isset($submenu, $values)): ?>
                    <?php foreach ($submenu as $key => $menu): ?>
                        <option <?php if (isset($values['category']) && $menu['id'] === $values['category']): ?>selected<?php endif; ?>
                                value="<?php echo htmlspecialchars($menu['id'] ?? ''); ?>">
                            <?php echo htmlspecialchars($menu['name'] ?? ''); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <span class="form__error"><?php echo htmlspecialchars($errors['category'] ?? ''); ?></span>
        </div>

    </div>

    <div class="form__item form__item--wide<?php if (isset($errors['message'])): ?> form__item--invalid<?php endif; ?>">
        <label for="message">Описание</label>
        <textarea id="message" name="lot[message]" placeholder="Напишите описание лота"
                  required><?php echo htmlspecialchars($values['message'] ?? ''); ?></textarea>
        <span class="form__error"><?php echo htmlspecialchars($errors['message'] ?? ''); ?></span>
    </div>

    <div class="form__item form__item--file<?php if (isset($errors['path'])): ?> form__item--invalid<?php endif; ?>">
        <!-- form__item--uploaded -->
        <label>Изображение</label>
        <div class="preview">
            <button class="preview__remove" type="button">x</button>
            <div class="preview__img">
                <img src="img/avatar.jpg" width="113" height="113" alt="Изображение лота">
            </div>
        </div>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" id="photo2" name="lot[photo]" required>
            <label for="photo2">
                <span>+ Добавить</span>
            </label>
            <span class="form__error"><?php echo htmlspecialchars($errors['path'] ?? ''); ?></span>
        </div>
    </div>

    <div class="form__container-three">

        <div class="form__item form__item--small<?php if (isset($errors['startPrice'])): ?> form__item--invalid<?php endif; ?>">
            <label for="lot-rate">Начальная цена</label>
            <input id="lot-rate" type="number" name="lot[startPrice]" placeholder="0"
                   value="<?php echo htmlspecialchars($values['startPrice'] ?? ''); ?>" required>
            <span class="form__error"><?php echo htmlspecialchars($errors['startPrice'] ?? ''); ?></span>
        </div>

        <div class="form__item form__item--small<?php if (isset($errors['step'])): ?> form__item--invalid<?php endif; ?>">
            <label for="lot-step">Шаг ставки</label>
            <input id="lot-step" type="number" name="lot[step]" placeholder="0"
                   value="<?php echo htmlspecialchars($values['step'] ?? ''); ?>" required>
            <span class="form__error"><?php echo htmlspecialchars($errors['step'] ?? ''); ?></span>
        </div>

        <div class="form__item<?php if (isset($errors['dateEnd'])): ?> form__item--invalid<?php endif; ?>">
            <label for="lot-date">Дата окончания торгов</label>
            <input class="form__input-date" id="lot-date" type="date" name="lot[dateEnd]"
                   value="<?php echo htmlspecialchars($values['dateEnd'] ?? ''); ?>" required>
            <span class="form__error"><?php echo htmlspecialchars($errors['dateEnd'] ?? ''); ?></span>
        </div>

    </div>
    <?php if (!empty($errors)): ?>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <?php endif; ?>
    <button type="submit" class="button">Добавить лот</button>
</form>
