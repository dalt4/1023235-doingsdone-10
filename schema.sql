CREATE DATABASE IF NOT EXISTS doingsdone
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

USE doingsdone;

CREATE TABLE IF NOT EXISTS categories (
	id       INT(11) AUTO_INCREMENT PRIMARY KEY,
	name     CHAR(255) NOT NULL,
	user_id  INT(11) NOT NULL
);

CREATE TABLE IF NOT EXISTS tasks (
   id             INT(11) AUTO_INCREMENT PRIMARY KEY,
   add_date       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
   status         TINYINT NOT NULL DEFAULT 0,
   name           CHAR(255) UNIQUE NOT NULL,
   user_file      CHAR(255) DEFAULT NULL,
   done_date      DATE DEFAULT NULL,
   user_id        INT(11) NOT NULL,
   categories_id  INT(11) NOT NULL
);

CREATE TABLE IF NOT EXISTS users (
   id             INT(11) AUTO_INCREMENT PRIMARY KEY,
   add_date       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
   email          CHAR(255) NOT NULL UNIQUE,
   name           CHAR(255) UNIQUE NOT NULL,
   password       CHAR(255) NOT NULL
);

CREATE FULLTEXT INDEX ft ON tasks( name );
