CREATE DATABASE industria_alimenticia;
USE industria_alimenticia;

CREATE TABLE usuario(
    id INT PRIMARY KEY NOT NULL,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL
);
  
CREATE TABLE tarefa(
    id INT PRIMARY KEY NOT NULL,
    id_usuario INT,
    descricao VARCHAR(200) NOT NULL,
    nome_setor VARCHAR(100) NOT NULL,
    prioridade enum ("baixa","média","alta") NOT NULL,
    data_cadastro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status_tarefa enum("a fazer","fazendo","pronto") DEFAULT "a fazer",
    FOREIGN KEY (id_usuario) REFERENCES usuario(id)
);