<section class="lot-item container">
    <h2><?php echo htmlspecialchars($title ?? ''); ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?php echo htmlspecialchars($lot_img ?? ''); ?>" width="730" height="548" alt="Сноуборд">
            </div>
            <p class="lot-item__category">Категория: <span><?php echo htmlspecialchars($category_name ?? ''); ?></span></p>
            <p class="lot-item__description"><?php echo htmlspecialchars($category_desc ?? ''); ?></p>
        </div>
        <div class="lot-item__right">
            <?php if (isset($is_auth) && $timeLaps !== 'Закончен'): ?>
                <div class="lot-item__state">
                    <div class="lot-item__timer timer">
                        <?php echo $timeLaps ?? ''; ?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?php echo formatPrice($start_price ?? ''); ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?php echo formatPrice($step) ?? ''; ?></span>
                        </div>
                    </div>
                    <form class="lot-item__form" action="/lot.php?lot_id=<?php echo $lotID ?? '' ?>" method="post">
                        <p class="lot-item__form-item form__item form__item--invalid">
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="text" name="cost" placeholder="12 000" value="<?php echo htmlspecialchars($cost ?? ''); ?>">
                            <span class="form__error"><?php echo htmlspecialchars($error_cost ?? ''); ?></span>
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                </div>
            <?php elseif($timeLaps === 'Закончен'): ?>
                <div class="lot-item__state">
                    <div class="lot-item__timer timer">
                        <?php echo htmlspecialchars($timeLaps ?? ''); ?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Выйграла ставка</span>
                            <span class="lot-item__cost"><?php echo formatPrice($start_price ?? ''); ?></span>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="lot-item__state form__item--invalid">
                    <p class="form__error">Что бы сделать ставку пожалуйста авторизуйтесь</p>
                </div>
            <?php endif; ?>
            <?php if($timeLaps !== 'Закончен'): ?>
                <div class="history">
                    <h3>История ставок (<span><?php echo count($users) ?? '' ?></span>)</h3>
                    <table class="history__list">
                        <?php if (isset($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <tr class="history__item">
                                    <td class="history__name"><?php echo htmlspecialchars($user['name'] ?? ''); ?></td>
                                    <td class="history__price"><?php echo formatPrice($user['sum_price'] ?? ''); ?></td>
                                    <td class="history__time"><?php echo time_format_laps(strtotime($user['dt_add'] ?? '')); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
