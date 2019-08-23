create database doingsdone
    default character set utf8
    default collate utf8_general_ci;

use doingsdone;

create table categories (
                            id       int(11) auto_increment primary key,
                            name     char(255) unique not null,
                            user_id  int(11) not null
);

create table tasks (
                       id             int(11) auto_increment primary key,
                       add_date       timestamp not null default current_timestamp,
                       status         tinyint not null default 0,
                       name           char(255) unique not null,
                       user_file      char(255) default null,
                       done_date      date default null,
                       user_id        int(11) not null,
                       categories_id  int(11) not null
);

create table users (
                       id             int(11) auto_increment primary key,
                       add_date       timestamp not null default current_timestamp,
                       email          char(255) not null unique,
                       name           char(255) unique not null,
                       password       char(255) not null
);
