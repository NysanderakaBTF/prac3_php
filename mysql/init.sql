CREATE DATABASE IF NOT EXISTS appDB;
CREATE USER IF NOT EXISTS 'user'@'%' IDENTIFIED BY 'password';
GRANT SELECT,UPDATE,INSERT,DELETE ON appDB.* TO 'user'@'%';
FLUSH PRIVILEGES;

USE appDB;
CREATE TABLE users
(
    id       INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50)  NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role     VARCHAR(255) NOT NULL default 'user'
);
INSERT INTO users (username, password, role)
VALUES ('admin', '$2y$10$zg8.a61TAaVe.IbijfV/9OcCK2mqWruVU9ZPDCt3LaV0kyfjIgj4K', 'admin'),
       ('admin1', '$2y$10$zg8.a61TAaVe.IbijfV/9OcCK2mqWruVU9ZPDCt3LaV0kyfjIgj4K', 'user'),
       ('admin3', '$2y$10$zg8.a61TAaVe.IbijfV/9OcCK2mqWruVU9ZPDCt3LaV0kyfjIgj4K', 'user'),
       ('admin5', '$2y$10$zg8.a61TAaVe.IbijfV/9OcCK2mqWruVU9ZPDCt3LaV0kyfjIgj4K', 'user');

CREATE TABLE product
(
    id          INT AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(255) NOT NULL,
    description TEXT,
    image_url   VARCHAR(255) NOT NULL,
    user_id     INT,
    CONSTRAINT FK_user FOREIGN KEY (user_id) references users(id) ON DELETE CASCADE
);


INSERT INTO product (title, description, image_url, user_id)
VALUES ('Product1', 'Image 1', '10', 1),
       ('Product2', 'Image 2', '964',1),
       ('Product3', 'Image 3', '456y', 2);
