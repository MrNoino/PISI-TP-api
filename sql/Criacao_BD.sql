CREATE DATABASE livros;

USE livros;

CREATE TABLE livros
(
id INT NOT NULL PRIMARY KEY auto_increment,
ISBN varchar(128) NOT NULL UNIQUE,
titulo varchar(256) NOT NULL,
edicao varchar(16),
ano_lancamento date
);

CREATE TABLE autores
(
id INT NOT NULL PRIMARY KEY auto_increment,
nome varchar(256) NOT NULL
);

CREATE TABLE categorias
(
id INT NOT NULL PRIMARY KEY auto_increment,
designacao varchar(256) NOT NULL
);

CREATE TABLE livrosautores
(
livro_id INT NOT NULL,
autor_id INT NOT NULL,
primary key (livro_id, autor_id),
foreign key (livro_id) references livros(id),
foreign key (autor_id) references autores(id)
);

CREATE TABLE livroscategorias
(
livro_id INT NOT NULL,
categoria_id INT NOT NULL,
primary key (livro_id, categoria_id),
foreign key (livro_id) references livros(id),
foreign key (categoria_id) references categorias(id)
);