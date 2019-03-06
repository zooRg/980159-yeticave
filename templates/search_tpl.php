<div class="container">
    <?php if(count($lots)): ?>
        <section class="lots">
            <h2>Результатов «<?php echo count($lots); ?>» по запросу «<span><?php echo $search; ?></span>»</h2>
            <ul class="lots__list">
                <?php foreach ($lots as $lot): ?>
                    <li class="lots__item lot">
                        <div class="lot__image">
                            <img src="<?php echo $lot['img']; ?>" width="350" height="260" alt="<?php echo $lot['name']; ?>">
                        </div>
                        <div class="lot__info">
                            <span class="lot__category"><?php echo $lot['category_name']; ?></span>
                            <h3 class="lot__title"><a class="text-link" href="lot.html"><?php echo $lot['name']; ?></a></h3>
                            <div class="lot__state">
                                <div class="lot__rate">
                                    <span class="lot__amount">Стартовая цена</span>
                                    <span class="lot__cost"><?php echo formatPrice($lot['start_price']); ?></span>
                                </div>
                                <div class="lot__timer timer">
                                    <?php echo time_lot_laps($lot['dt_end']); ?>
                                </div>
                            </div>
                        </div>
                    </li>
                <? endforeach; ?>
            </ul>
        </section>
        <ul class="pagination-list">
            <li class="pagination-item pagination-item-prev"><a>Назад</a></li>
            <li class="pagination-item pagination-item-active"><a>1</a></li>
            <li class="pagination-item"><a href="#">2</a></li>
            <li class="pagination-item"><a href="#">3</a></li>
            <li class="pagination-item"><a href="#">4</a></li>
            <li class="pagination-item pagination-item-next"><a href="#">Вперед</a></li>
        </ul>
    <?php else: ?>
        <section class="lots">
            <h2>Ничего не найдено по вашему запросу</h2>
        </section>
    <?php endif; ?>
</div>