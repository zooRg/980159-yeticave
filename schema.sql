CREATE DATABASE yeticave
  character set utf8
  collate utf8_general_ci;

CREATE TABLE category (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  name char(255)
);

CREATE TABLE lot (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  name CHAR(50) NOT NULL UNIQUE,
  description TEXT,
  img CHAR(255),
  start_price FLOAT(11),
  dt_end TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  step FLOAT(11),
  autor_id INT(11),
  vinner_id INT(11),
  category_id INT(11)
);

CREATE FULLTEXT INDEX lot_ft_search ON lot (name, description);

CREATE TABLE bets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  sum_price FLOAT(11),
  autor_id INT(11),
  lot_id INT(11)
);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dt_registr TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  email CHAR(128) NOT NULL UNIQUE,
  name CHAR(50),
  password VARCHAR(255) NOT NULL,
  avatar CHAR(70),
  contacts VARCHAR(30),
  create_lot_id INT(11),
  bets_id INT(11)
);