USE doingsdone;

INSERT INTO categories (name, user_id) VALUES
	('Входящие', 1),
	('Учеба', 1),
	('Работа', 1),
	('Домашние дела', 2),
	('Авто', 1);

INSERT INTO tasks (name, done_date ,   categories_id, status, user_id) VALUES
	('Собеседование в IT компании', '2019.12.01', 3, 0, 1),
	('Выполнить тестовое задание', '2019.12.25', 3, 0, 1),
	('Сделать задание первого раздела', '2019.12.21', 2, 1, 1),
	('Встреча с другом', '2019.12.22', 1, 0, 1),
	('Купить корм для кота', null, 4, 0, 2),
	('Заказать пиццу', null, 4, 0, 2);

INSERT INTO users (name, email, password) VALUES
	('mr first', 'first@mail.mail', 'p'),
	('mr second', 'second@mail.mail', 'p');


# получить список из всех проектов для одного пользователя

SELECT * FROM categories WHERE user_id = 1;

# получить список из всех задач для одного проекта

SELECT * FROM tasks WHERE categories_id = 1;

# пометить задачу как выполненную
UPDATE tasks SET status = 1 WHERE id = 1;

# обновить название задачи по её идентификатору
UPDATE tasks SET name = 'Сделать задание четвертого раздела' WHERE id = 3;

