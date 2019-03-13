<div class="container">
    <section class="lots">
        <?php if (isset($lots)): ?>
            <h2>Результатов «<?php echo $count; ?>» по запросу «<span><?php echo $search ?? ''; ?></span>»</h2>
            <ul class="lots__list">
                <?php foreach ($lots as $lot): ?>
                    <li class="lots__item lot">
                        <div class="lot__image">
                            <img src="<?php echo $lot['img'] ?? ''; ?>" width="350" height="260"
                                 alt="<?php echo $lot['name'] ?? ''; ?>">
                        </div>
                        <div class="lot__info">
                            <span class="lot__category"><?php echo $lot['category_name']; ?></span>
                            <h3 class="lot__title"><a class="text-link" href="lot.php?lot_id=<?php echo $lot['id'] ?? ''; ?>"><?php echo $lot['name'] ?? ''; ?></a>
                            </h3>
                            <div class="lot__state">
                                <div class="lot__rate">
                                    <span class="lot__amount">Стартовая цена</span>
                                    <span class="lot__cost"><?php echo formatPrice($lot['start_price']) ?? ''; ?></span>
                                </div>
                                <div class="lot__timer timer">
                                    <?php echo time_lot_laps($lot['dt_end']) ?? ''; ?>
                                </div>
                            </div>
                        </div>
                    </li>
                <? endforeach; ?>
            </ul>
        <?php else: ?>
            <h2>Ничего не найдено по вашему запросу</h2>
        <?php endif; ?>
    </section>
    <?php if ($pages_count > 1): ?>
        <ul class="pagination-list">
            <li class="pagination-item pagination-item-prev">
                <a href="/search.php?search=<?php echo $search ?? ''; ?>&find=Найти&page=<?php echo $cur_page <= 1 ? count($pages) : $cur_page - 1; ?>">Назад</a>
            </li>
            <?php foreach ($pages as $page): ?>
                <li class="pagination-item<?php if ($page === $cur_page): ?> pagination-item-active<?php endif; ?>">
                    <a href="/search.php?search=<?php echo $search ?? ''; ?>&find=Найти&page=<?php echo $page ?? ''; ?>"><?php echo $page ?? ''; ?></a>
                </li>
            <?php endforeach; ?>
            <li class="pagination-item pagination-item-next">
                <a href="/search.php?search=<?php echo $search ?? ''; ?>&find=Найти&page=<?php echo $cur_page >= count($pages) ? 1 : $cur_page + 1; ?>">Вперед</a>
            </li>
        </ul>
    <?php endif; ?>
</div>