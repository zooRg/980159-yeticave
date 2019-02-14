CREATE DATABASE yeticave2
  character set utf8
  collate utf8_general_ci;

USE yeticave2;

CREATE TABLE category (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  name char(255)
);

CREATE TABLE lot (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  data_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  name CHAR(128) NOT NULL UNIQUE,
  description TEXT(10000),
  img CHAR(255),
  start_price INT(255),
  date_end TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  step INT(255),
  autor_id INT(11),
  vinner_id INT(11),
  category_id INT(11)
);

CREATE TABLE bets (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  data_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  sum_price INT(11),
  autor_id INT(11),
  lot_id INT(11)
);

CREATE TABLE users (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  data_registr TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  email CHAR(128) NOT NULL UNIQUE,
  name CHAR(50),
  password CHAR(32) NOT NULL,
  avatar CHAR(70),
  contacts VARCHAR(30),
  create_lot_id INT(11),
  bets_id INT(11)
);