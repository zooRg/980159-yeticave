<section class="lot-item container">
    <h2><?php echo $title; ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?php echo $lot_img; ?>" width="730" height="548" alt="Сноуборд">
            </div>
            <p class="lot-item__category">Категория: <span><?php echo $category_name; ?></span></p>
            <p class="lot-item__description"><?php echo $category_desc; ?></p>
        </div>
        <div class="lot-item__right">
            <?php if ($is_auth): ?>
                <div class="lot-item__state">
                    <div class="lot-item__timer timer">
                        <?php echo $timeLaps; ?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?php echo $start_price; ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?php echo htmlspecialchars(formatPrice($step)); ?></span>
                        </div>
                    </div>
                    <form class="lot-item__form" action="/lot.php?lot_id=<?php echo $_GET['lot_id'] ?>" method="post">
                        <p class="lot-item__form-item form__item form__item--invalid">
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="text" name="cost" placeholder="12 000" value="<?php echo $cost; ?>">
                            <span class="form__error"><?php echo $error_cost; ?></span>
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="lot-item__state form__item--invalid">
                    <p class="form__error">Что бы сделать ставку пожалуйста авторизуйтесь</p>
                </div>
            <?php endif; ?>
            <div class="history">
                <h3>История ставок (<span><?php echo count($users) ?></span>)</h3>
                <table class="history__list">
                    <?php foreach ($users as $user): ?>
                        <tr class="history__item">
                            <td class="history__name"><?php echo htmlspecialchars($user['name']); ?></td>
                            <td class="history__price"><?php echo htmlspecialchars(formatPrice($user['sum_price'])); ?></td>
                            <td class="history__time"><?php echo htmlspecialchars(time_format_laps(strtotime($user['dt_add']))); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</section>
