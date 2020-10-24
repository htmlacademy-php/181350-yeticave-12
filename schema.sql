create database yeticave
default character set utf8
default collate utf8_general_ci;

use yeticave;

create table category (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(64),
  symbol_code VARCHAR(64),
  INDEX (id)
);

create table lot (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  create_date TIMESTAMP,
  name VARCHAR(64),
  description VARCHAR(256),
  picture TEXT(65536),
  start_price INT,
  end_date DATE,
  bid_step INT,
  author BIGINT,
  winner BIGINT,
  category BIGINT,
  INDEX (author),
  INDEX (winner),
  INDEX (category),
  UNIQUE INDEX (id),
  INDEX (name)
);

create table bid (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  date TIMESTAMP,
  amount INT,
  user BIGINT,
  lot BIGINT,
  UNIQUE INDEX (id),
  INDEX (user),
  INDEX (lot)
);

create table user (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  register_date TIMESTAMP,
  email VARCHAR(128) UNIQUE,
  name VARCHAR(64) UNIQUE,
  password CHAR(64),
  contact VARCHAR(64),
  bid BIGINT,
  lot BIGINT,
  UNIQUE INDEX (id),
  UNIQUE INDEX (email),
  INDEX (bid),
  INDEX (lot)
);

ALTER TABLE lot ADD FOREIGN KEY (author) REFERENCES user (id) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE lot ADD FOREIGN KEY (winner) REFERENCES user (id) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE lot ADD FOREIGN KEY (category) REFERENCES category (id) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE bid ADD FOREIGN KEY (user) REFERENCES user (id) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE bid ADD FOREIGN KEY (lot) REFERENCES lot (id) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE user ADD FOREIGN KEY (lot) REFERENCES lot (id) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE user ADD FOREIGN KEY (bid) REFERENCES bid (id) ON DELETE RESTRICT ON UPDATE RESTRICT;



