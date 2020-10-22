INSERT INTO post_types (name, class)
VALUES ('Текст', 'text'),
       ('Фото', 'photo'),
       ('Видео', 'video'),
       ('Цитата', 'quote'),
       ('Ссылка', 'link');

INSERT INTO users (email, login, password, picture)
VALUES ('lara@mail.com', 'Лариса', 'lara', 'img/userpic-larisa-small.jpg'),
       ('vlad@mail.com', 'Владик', 'vlad', 'img/userpic.jpg'),
       ('victor@mail.com', 'Виктор', 'victor', 'img/userpic-mark.jpg');

INSERT INTO comments (content, user_id, post_id)
VALUES ('Magnificent', 1, 1),
       ('Trash!!!', 1, 2);

INSERT INTO posts (date, title, content, author_quote, img, video, link, view_count, user_id, post_type_id)
VALUES ('2020-06-23 20:47', 'Цитата', 'Мы в жизни любим только раз, а после ищем лишь похожих', 'Неизвестный Автор', '',
        '', '', 1, 1, 4),
       ('2020-06-23 15:11', 'Игра престолов', 'Не могу дождаться начала финального сезона своего любимого сериала!', '',
        '', '', '', 5, 2, 1),
       ('2020-06-19 21:11', 'Наконец, обработал фотки!', '', '', 'img/rock-medium.jpg', '', '', 1, 3, 2),
       ('2020-06-09 21:11', 'Моя мечта', '', '', 'img/coast-medium.jpg', '', '', 1, 1, 2),
       ('2020-02-23 21:11', 'Лучшие курсы', '', '', '', '', 'www.htmlacademy.ru', 1, 2, 5),
       ('2020-06-23 21:00', 'Видео', '', '', '', 'https://www.youtube.com/watch?v=giYeaKsXnsI', '', '3', '2', '3');

INSERT INTO subscriptions (author_id, follower_id)
VALUES (1, 2),
       (1, 3);

SELECT p.*, u.login, pt.name
FROM posts p
         JOIN users u ON p.user_id = u.id
         JOIN post_types pt ON p.post_type_id = pt.id
ORDER BY view_count DESC;

SELECT *
FROM posts
WHERE user_id = '2';

SELECT c.*, u.login
FROM comments c
         JOIN users u ON c.user_id = u.id
WHERE post_id = 1;

INSERT INTO likes (user_id, post_id)
VALUES (1, 2);

INSERT INTO subscriptions (author_id, follower_id)
VALUES (1, 2);

SELECT posts.*,
       post_types.name,
       post_types.class,
       users.id,
       users.login,
       users.picture,
       users.registered,
       GROUP_CONCAT(t.tag)
FROM posts
         JOIN users ON posts.user_id = users.id
         JOIN post_types ON posts.post_type_id = post_types.id
         LEFT JOIN post_tag pt on posts.post_id = pt.post_id
         LEFT JOIN tags t on pt.tag_id = t.id
WHERE t.tag = 'test'
GROUP BY posts.post_id;

SELECT posts.*,
       users.login,
       users.picture,
       users.registered,
       post_types.class,
       GROUP_CONCAT(tags.tag)
FROM (SELECT posts.*
      FROM posts
      WHERE MATCH(posts.title, posts.content) AGAINST('Игра2')) as posts
         JOIN post_types ON posts.post_type_id = post_types.id
         JOIN users ON posts.user_id = users.id
         JOIN post_tag ON posts.post_id = post_tag.post_id
         JOIN tags ON post_tag.tag_id = tags.id
GROUP BY posts.post_id;
