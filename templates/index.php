<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и
        горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <!--заполните этот список из массива категорий-->
        <?php foreach ($submenu as $menu): ?>
            <li class="promo__item promo__item--boards">
                <a class="promo__link" href="pages/all-lots.html"><?php echo htmlspecialchars($menu['name']) ?? ''; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <!--заполните этот список из массива с товарами-->
        <?php foreach ($adds as $lot): ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?php echo htmlspecialchars($lot['PICTURE']); ?>" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?php echo htmlspecialchars($lot['CATEGORY']) ?? ''; ?></span>
                    <h3 class="lot__title">
                        <a class="text-link"
                           href="<?php echo $url . '?lot_id=' . $lot['ID']; ?>"><?php echo htmlspecialchars($lot['NAME']) ?? ''; ?></a>
                    </h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost">
                                <?php echo htmlspecialchars(formatPrice($lot['PRICE'])) ?? ''; ?>
                            </span>
                        </div>
                        <div class="lot__timer timer">
                            <?php echo time_lot_laps($lot['dt_end']) ?? ''; ?>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</section>