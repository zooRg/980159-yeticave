/* Переименовываем Поле в таблице для четкого понимания что это */
ALTER TABLE yeticave.lot
CHANGE COLUMN user_id vinner_id INT(11);

/* Добавляем категории в таблицу */
INSERT INTO yeticave.category
  (name)
VALUES
  ('Доски и лыжи'),
  ('Крепления'),
  ('Ботинки'),
  ('Одежда'),
  ('Инструменты'),
  ('Разное');



/* Добавляем пользователей */
INSERT INTO yeticave.users
  (data_registr, email, name, password, avatar, contacts, create_lot_id, bets_id)
VALUES
  (CURRENT_TIMESTAMP, 'turbo@mail.ru', 'turbo', '123123', '', '+7(999) 999-99-99', '1', '1'),
  (CURRENT_TIMESTAMP, 'switty@mail.ru', 'switty', '123123', '', '+7(111) 111-11-11', '2', '2');


/* Добавляем лоты */
INSERT INTO yeticave.lot
	(data_add, name, description, img, start_price, date_end, step, autor_id, user_id, category_id)
VALUES
    (
        CURRENT_TIMESTAMP - INTERVAL 1 hour,
		'2014 Rossignol District Snowboard',
	    'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчкоми четкими дугами. Стекловолокно Bi-Ax, уложенное в двух направлениях, наделяет этот снаряд отличной гибкостью и отзывчивостью, а симметричная геометрия в сочетании с классическим прогибом кэмбер позволит уверенно держать высокие скорости. А если к концу катального дня сил совсем не останется, просто посмотрите на Вашу доску и улыбнитесь, крутая графика от Шона Кливера еще никого не оставляла равнодушным.',
	    'img/lot-1.jpg',
	    '10000',
	    NOW() + INTERVAL 2 MONTH,
	    '1000',
	    '1',
	    '1',
	    '1'
    ),(
        CURRENT_TIMESTAMP - INTERVAL 2 hour,
		'DC Ply Mens 2016/2017 Snowboard',
	    'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчкоми четкими дугами. Стекловолокно Bi-Ax, уложенное в двух направлениях, наделяет этот снаряд отличной гибкостью и отзывчивостью, а симметричная геометрия в сочетании с классическим прогибом кэмбер позволит уверенно держать высокие скорости. А если к концу катального дня сил совсем не останется, просто посмотрите на Вашу доску и улыбнитесь, крутая графика от Шона Кливера еще никого не оставляла равнодушным.',
	    'img/lot-2.jpg',
	    '159900',
	    NOW() + INTERVAL 2 MONTH,
	    '10000',
	    '1',
	    '1',
	    '1'
    ),(
        CURRENT_TIMESTAMP - INTERVAL 3 hour,
		'Крепления Union Contact Pro 2015 года размер L/XL',
	    'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчкоми четкими дугами. Стекловолокно Bi-Ax, уложенное в двух направлениях, наделяет этот снаряд отличной гибкостью и отзывчивостью, а симметричная геометрия в сочетании с классическим прогибом кэмбер позволит уверенно держать высокие скорости. А если к концу катального дня сил совсем не останется, просто посмотрите на Вашу доску и улыбнитесь, крутая графика от Шона Кливера еще никого не оставляла равнодушным.',
	    'img/lot-3.jpg',
	    '8000',
	    NOW() + INTERVAL 2 MONTH,
	    '500',
	    '1',
	    '1',
	    '2'
    ),(
        CURRENT_TIMESTAMP - INTERVAL 4 hour,
		'Ботинки для сноуборда DC Mutiny Charocal',
	    'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчкоми четкими дугами. Стекловолокно Bi-Ax, уложенное в двух направлениях, наделяет этот снаряд отличной гибкостью и отзывчивостью, а симметричная геометрия в сочетании с классическим прогибом кэмбер позволит уверенно держать высокие скорости. А если к концу катального дня сил совсем не останется, просто посмотрите на Вашу доску и улыбнитесь, крутая графика от Шона Кливера еще никого не оставляла равнодушным.',
	    'img/lot-4.jpg',
	    '10900',
	    NOW() + INTERVAL 2 MONTH,
	    '500',
	    '2',
	    '2',
	    '3'
    ),(
        CURRENT_TIMESTAMP - INTERVAL 5 hour,
		'Куртка для сноуборда DC Mutiny Charocal',
	    'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчкоми четкими дугами. Стекловолокно Bi-Ax, уложенное в двух направлениях, наделяет этот снаряд отличной гибкостью и отзывчивостью, а симметричная геометрия в сочетании с классическим прогибом кэмбер позволит уверенно держать высокие скорости. А если к концу катального дня сил совсем не останется, просто посмотрите на Вашу доску и улыбнитесь, крутая графика от Шона Кливера еще никого не оставляла равнодушным.',
	    'img/lot-5.jpg',
	    '7500',
	    NOW() + INTERVAL 2 MONTH,
	    '500',
	    '2',
	    '2',
	    '4'
    ),(
        CURRENT_TIMESTAMP - INTERVAL 6 hour,
		'Маска Oakley Canopy',
	    'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчкоми четкими дугами. Стекловолокно Bi-Ax, уложенное в двух направлениях, наделяет этот снаряд отличной гибкостью и отзывчивостью, а симметричная геометрия в сочетании с классическим прогибом кэмбер позволит уверенно держать высокие скорости. А если к концу катального дня сил совсем не останется, просто посмотрите на Вашу доску и улыбнитесь, крутая графика от Шона Кливера еще никого не оставляла равнодушным.',
	    'img/lot-5.jpg',
	    '7500',
	    NOW() + INTERVAL 2 MONTH,
	    '500',
	    '2',
	    '2',
	    '6'
    );


/* Добавляем ставки */
INSERT INTO yeticave.bets
	(data_add, sum_price, autor_id, lot_id)
VALUES
    (CURRENT_TIMESTAMP - INTERVAL 4 HOUR, '2000', 1, 1),
    (CURRENT_TIMESTAMP - INTERVAL 4 HOUR, '10000', 2, 2),
    (CURRENT_TIMESTAMP - INTERVAL 1 HOUR, '500', 2, 3),
    (CURRENT_TIMESTAMP - INTERVAL 2 HOUR, '500', 2, 3);

SELECT * from yeticave.category;

/* Получаем самы */
SELECT y.name, y.start_price, y.img, y.step, c.name AS cat_name
FROM yeticave.lot y
JOIN category c
ON y.category_id = c.id
WHERE y.data_add < y.date_end
ORDER BY y.data_add DESC
LIMIT 3;

/* Получаем лот по его айди и выводим название раздела к какому привязан лот */
SELECT c.name
FROM yeticave.lot y
JOIN category c
ON y.category_id = c.id
WHERE y.category_id = 2;

/* Получаем лот по айди и меняем название */
UPDATE yeticave.lot
SET name = '2014 Rossignol District Snowboard'
WHERE id = 2;

/* Получем ставки по айди лота и отсортировав по дате добавления */
SELECT b.sum_price, name
FROM yeticave.bets b
JOIN yeticave.lot y
ON b.lot_id = y.id
WHERE b.lot_id = 3
ORDER BY b.data_add DESC;

SELECT SUM(b.sum_price), name
FROM yeticave.bets b
JOIN yeticave.lot y
ON b.lot_id = y.id
WHERE b.lot_id = 3
ORDER BY b.data_add DESC;