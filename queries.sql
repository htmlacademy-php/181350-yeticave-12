--Заполнение таблицы category
INSERT INTO category (name, symbol_code) VALUES ('Доски и лыжи','boards');
INSERT INTO category (name, symbol_code) VALUES ('Крепления','attachment');
INSERT INTO category (name, symbol_code) VALUES ('Ботинки','boots');
INSERT INTO category (name, symbol_code) VALUES ('Одежда','clothing');
INSERT INTO category (name, symbol_code) VALUES ('Инструменты','tools');
INSERT INTO category (name, symbol_code) VALUES ('Разное','other');

--Заполнение таблицы пользователей
insert INTO user (register_date, email, name, password, contact) VALUES
('2020-10-27 12:00:00','user1@gmail.com','user1','qwerty1','+79991112233');
insert INTO user (register_date, email, name, password, contact) VALUES
('2020-10-26 12:00:00','user2@gmail.com','user2','qwerty2','+79991112233');

--Заполнение таблицы Лотов
insert INTO lot (create_date, name, description, picture, start_price,
 end_date, bid_step, author, winner, category_id) VALUES
 ('2020-10-10 12:00:00', '2014 Rossignol District Snowboard', 'Шаблон описания',
 'img/lot-1.jpg', 10999, '2020-10-23', 10, 1, 2, 1);

insert INTO lot (create_date, name, description, picture, start_price,
 end_date, bid_step, author, winner, category_id) VALUES
 ('2020-10-11 12:00:00', 'DC Ply Mens 2016/2017 Snowboard', 'Шаблон описания',
 'img/lot-2.jpg', 159999, '2020-10-24', 10, 1, 2, 1);

 insert INTO lot (create_date, name, description, picture, start_price,
 end_date, bid_step, author, winner, category_id) VALUES
 ('2020-10-12 12:00:00', 'Крепления Union Contact Pro 2015 года размер L/XL', 'Шаблон описания',
 'img/lot-3.jpg', 8000, '2020-10-25', 10, 1, 2, 2);

 insert INTO lot (create_date, name, description, picture, start_price,
 end_date, bid_step, author, winner, category_id) VALUES
 ('2020-10-13 12:00:00', 'Ботинки для сноуборда DC Mutiny Charocal', 'Шаблон описания',
 'img/lot-4.jpg', 10999, '2020-10-26', 10, 1, 2, 3);

 insert INTO lot (create_date, name, description, picture, start_price,
 end_date, bid_step, author, winner, category_id) VALUES
 ('2020-10-14 12:00:00', 'Куртка для сноуборда DC Mutiny Charocal', 'Шаблон описания',
 'img/lot-5.jpg', 7500, '2020-10-27', 10, 1, 2, 4);

 insert INTO lot (create_date, name, description, picture, start_price,
 end_date, bid_step, author, winner, category_id) VALUES
 ('2020-10-15 12:00:00', 'Маска Oakley Canopy', 'Шаблон описания',
 'img/lot-6.jpg', 5400, '2020-10-28', 10, 1, 2, 6);

--Заполнение таблицы со ставками
insert INTO bid (date, amount, user_id, lot_id) VALUES ('2020-10-10 14:00:00',
11009, 2, 6);
insert INTO bid (date, amount, user_id, lot_id) VALUES ('2020-10-10 15:00:00',
11019, 1, 6);

--Получение всех категорий
select * from category;

--Получение новых лотов
SELECT name, start_price, picture, IF(max(amount) IS NULL, start_price, max(amount)) AS 'current_price', category
FROM  (SELECT l.*, b.amount, c.name as 'category' from lot l
        LEFT JOIN category c on l.category_id = c.id
        LEFT JOIN bid b on l.id = b.lot_id
        WHERE l.end_date > SYSDATE()
ORDER BY l.create_date DESC, b.date DESC) tbl
GROUP BY id;

--Показать лот по его id
select l.*, c.name from lot l left join category c on c.id = l.category_id
where id = 1;

--Обновить название лота по его идентификатора
update lot
set name = 'New name'
where id = 1;

--Получить список ставок для лота
select * from bid
where lot_id = 1
order by date;

