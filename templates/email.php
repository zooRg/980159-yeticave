<h1>Поздравляем с победой</h1>
<p>Здравствуйте, <?php echo htmlspecialchars($user_name ?? '')?></p>
<p>Ваша ставка для лота <a href="<?php echo $lot_link ?? ''?>"><?php echo htmlspecialchars($lot_name ?? '')?></a> победила.</p>
<p>Перейдите по ссылке <a href="<?php echo $lot_link ?? ''?>">мои ставки</a>,
    чтобы связаться с автором объявления</p>

<small>Интернет Аукцион "YetiCave"</small>