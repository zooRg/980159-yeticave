<section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
        <?php if (isset($bets)): ?>
            <?php foreach($bets as $bet): ?>
                <tr
                    class="rates__item<?php if (isset($bet['vinner_id']) && !isset($bet['other_user'])): ?> rates__item--win<?php endif; ?><?php if (
                    isset($bet['other_user']) && time_lot_laps($bet['dt_end'] ?? '') === 'Закончен'): ?> rates__item--end<?php endif; ?>">
                    <td class="rates__info">
                        <div class="rates__img">
                            <img src="<?php echo htmlspecialchars($bet['img'] ?? ''); ?>" width="54" height="40" alt="Сноуборд">
                        </div>
                        <?php if (isset($bet['vinner_id']) && !isset($bet['other_user'])): ?>
                            <div>
                                <h3 class="rates__title">
                                    <a href="/lot.php?lot_id=<?php echo htmlspecialchars($bet['id'] ?? ''); ?>">
                                        <?php echo htmlspecialchars($bet['lot_name'] ?? ''); ?>
                                    </a>
                                </h3>
                                <p><?php echo htmlspecialchars($bet['contacts'] ?? ''); ?></p>
                            </div>
                        <?php else: ?>
                            <h3 class="rates__title">
                                <a href="/lot.php?lot_id=<?php echo htmlspecialchars($bet['id'] ?? ''); ?>">
                                    <?php echo htmlspecialchars($bet['lot_name'] ?? ''); ?>
                                </a>
                            </h3>
                        <?php endif; ?>
                    </td>
                    <td class="rates__category">
                        <?php echo htmlspecialchars($bet['category_name'] ?? ''); ?>
                    </td>
                    <td class="rates__timer">
                        <?php if (isset($bet['vinner_id']) && !isset($bet['other_user'])): ?>
                            <div class="timer timer--win">Ставка выиграла</div>
                        <?php elseif ((isset($bet['vinner_id']) || !isset($bet['other_user'])) && time_lot_laps($bet['dt_end'] ?? '') === 'Закончен'): ?>
                            <div class="timer timer--end">Торги окончены</div>
                        <?php else: ?>
                            <div class="timer timer--finishing">
                                <?php echo time_lot_laps($bet['dt_end'] ?? '', 1); ?>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td class="rates__price">
                        <?php echo formatPrice($bet['sum_price'] ?? ''); ?>
                    </td>
                    <td class="rates__time">
                        <?php echo time_format_laps(strtotime($bet['dt_add'] ?? '')); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</section>