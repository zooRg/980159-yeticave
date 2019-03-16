<section class="lots">
    <h2>Все лоты в категории <span>«<?php if (isset($category_name)) echo $category_name; ?>»</span></h2>
    <ul class="lots__list">
        <?php if (isset($lots)): ?>
            <?php foreach ($lots as $lot): ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?php echo htmlspecialchars($lot['PICTURE'] ?? ''); ?>" width="350" height="260" alt="">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?php echo htmlspecialchars($lot['CATEGORY'] ?? ''); ?></span>
                        <h3 class="lot__title">
                            <a class="text-link" href="/lot.php?lot_id=<?php echo htmlspecialchars($lot['ID'] ?? ''); ?>">
                                <?php echo htmlspecialchars($lot['NAME'] ?? ''); ?>
                            </a>
                        </h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost">
                                <?php echo formatPrice($lot['PRICE'] ?? ''); ?>
                            </span>
                            </div>
                            <div class="lot__timer timer">
                                <?php echo time_lot_laps($lot['dt_end'] ?? ''); ?>
                            </div>
                        </div>
                    </div>
                </li>
            <? endforeach; ?>
        <? endif; ?>
    </ul>
</section>
<?php if (isset($pages_count, $category_id, $cur_page, $pages) && $pages_count > 1): ?>
    <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev">
            <a href="/all-lots.php?category_id=<?php echo $category_id ?? 1; ?>&page=<?php echo $cur_page <= 1 ? count($pages) : $cur_page - 1; ?>">Назад</a>
        </li>
        <?php foreach ($pages as $page): ?>
            <li class="pagination-item<?php if ((int)$page === (int)$cur_page): ?> pagination-item-active<?php endif; ?>">
                <a href="/all-lots.php?category_id=<?php echo $category_id ?? 1; ?>&page=<?php echo $page ?? ''; ?>"><?php echo $page ?? ''; ?></a>
            </li>
        <?php endforeach; ?>
        <li class="pagination-item pagination-item-next">
            <a href="/all-lots.php?category_id=<?php echo $category_id ?? 1; ?>&page=<?php echo $cur_page >= count($pages) ? 1 : $cur_page + 1; ?>">Вперед</a>
        </li>
    </ul>
<?php endif; ?>