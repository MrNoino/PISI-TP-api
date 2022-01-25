CREATE DATABASE books;

USE books;

CREATE TABLE books
(

id INT NOT NULL PRIMARY KEY auto_increment,
ISBN varchar(128) NOT NULL UNIQUE,
title varchar(256) NOT NULL,
edtion varchar(16),
launch_year int

);

CREATE TABLE authors
(

id INT NOT NULL PRIMARY KEY auto_increment,
name varchar(256) NOT NULL

);

CREATE TABLE categories
(

id INT NOT NULL PRIMARY KEY auto_increment,
category varchar(256) NOT NULL

);

CREATE TABLE booksauthors
(

book_id INT NOT NULL,
author_id INT NOT NULL,
primary key (book_id, author_id),
foreign key (book_id) references books(id),
foreign key (author_id) references authors(id)

);

CREATE TABLE bookscategories
(

book_id INT NOT NULL,
category_id INT NOT NULL,
primary key (book_id, category_id),
foreign key (book_id) references books(id),
foreign key (category_id) references categories(id)

);