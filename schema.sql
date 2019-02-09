CREATE DATABASE yeticave
  character set utf8
  collate utf8_general_ci;

USE yeticave;

CREATE TABLE category (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name char(255)
);

CREATE TABLE lot (
  id INT AUTO_INCREMENT PRIMARY KEY,
  data_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  name CHAR(128) NOT NULL UNIQUE,
  description CHAR(255),
  img CHAR,
  start_price CHAR,
  date_end TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  step CHAR,
  autor_id INT,
  user_id INT,
  category_id INT
);

CREATE TABLE bets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  data_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  sum_price CHAR,
  autor_id INT,
  lot_id INT
);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  data_registr TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  email CHAR(128) NOT NULL UNIQUE,
  name CHAR(50),
  password CHAR(32) NOT NULL,
  avatar CHAR(70),
  contacts VARCHAR(30),
  create_lot_id INT,
  bets_id INT
);