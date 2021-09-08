create table users(
	codigo SERIAL not null,
	nome VARCHAR(80) not null,
	nome_de_usuario VARCHAR(15) not null,
	senha VARCHAR(16) not null,
	constraint pk_user primary key (codigo)
);

insert into users (nome, nome_de_usuario, senha) values ('João Vítor Batistella', 'joaovb', 'Adm987321');

CREATE TABLE authorized_tokens (
  id SERIAL NOT NULL,
  token varchar(100) NOT NULL,
  status CHAR(2) NOT NULL,
  CHECK (status IN ('S', 'N')),
  constraint pk_authorized_tokens primary key (id)
);

ALTER TABLE authorized_tokens ALTER COLUMN token TYPE VARCHAR(500);

insert into authorized_tokens(token, status) values ('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJPbmxpbmUgSldUIEJ1aWxkZXIiLCJpYXQiOjE2Mjg1NDA2MzMsImV4cCI6MTY2MDA3NjYzMywiYXVkIjoid3d3LmV4YW1wbGUuY29tIiwic3ViIjoianJvY2tldEBleGFtcGxlLmNvbSIsIkdpdmVuTmFtZSI6IkpvaG5ueSIsIlN1cm5hbWUiOiJSb2NrZXQiLCJFbWFpbCI6Impyb2NrZXRAZXhhbXBsZS5jb20iLCJSb2xlIjpbIk1hbmFnZXIiLCJQcm9qZWN0IEFkbWluaXN0cmF0b3IiXX0.-AeeXJ5UGIndFuRx6-cWchGd-ZRezgPxSl3v40gv_F8', 'S');

CREATE TABLE valor_ref (
	codigo SERIAL NOT NULL,
	valor MONEY NOT NULL,
	inicio TIME,
	fim TIME,
	CONSTRAINT pk_valor_ref PRIMARY KEY (codigo)
);

alter table valor_ref alter column valor type decimal;
alter table valor_ref alter column valor set not null;

CREATE TABLE produto (
	codigo SERIAL NOT NULL,
	nome VARCHAR(60) NOT NULL,
	descricao VARCHAR(200),
	preco MONEY NOT NULL,
	estoque INTEGER NOT NULL,
	CONSTRAINT pk_produto PRIMARY KEY(codigo)
);

alter table produto alter column preco type decimal;
alter table produto alter column preco set not null;

 
CREATE TABLE cidade (
	codigo SERIAL NOT NULL,
	nome VARCHAR(50) NOT NULL,
	uf CHAR(2),
	CONSTRAINT pk_cidade PRIMARY KEY(codigo)
);

CREATE TABLE cliente (
	codigo SERIAL NOT NULL,
	nome VARCHAR(60) NOT NULL,
	dt_nascimento DATE NOT NULL,
	telefone INTEGER NOT NULL,
	email VARCHAR(35) NOT NULL,
	logradouro VARCHAR(70) NOT NULL,
	sexo CHAR(1) not NULL,
	cpf INTEGER NOT NULL,
	cod_cidade INTEGER NOT NULL,
	CONSTRAINT ck_sexo CHECK (sexo in ('M', 'F', 'U')),
	CONSTRAINT pk_cliente PRIMARY KEY(codigo),
	CONSTRAINT fk_cliente_cidade FOREIGN KEY (cod_cidade) REFERENCES cidade(codigo)
);

ALTER TABLE cliente ALTER COLUMN telefone TYPE VARCHAR(30);
ALTER TABLE cliente ALTER COLUMN cpf TYPE VARCHAR(30);
 
CREATE TABLE fornecedor (
	codigo SERIAL NOT NULL,
	nome VARCHAR(70) NOT NULL,
	logradouro VARCHAR(70) NOT NULL,
	telefone INTEGER NOT NULL,
	cod_cidade INTEGER NOT NULL,
	CONSTRAINT pk_fornecedor PRIMARY KEY(codigo),
 	CONSTRAINT fk_fornecedor_cidade FOREIGN KEY(cod_cidade) REFERENCES cidade(codigo)
 );

ALTER TABLE fornecedor ALTER COLUMN telefone TYPE VARCHAR(30);

CREATE TABLE compra (
	codigo SERIAL NOT NULL,
	quantidade INTEGER NOT NULL,
	cod_fornecedor INTEGER NOT NULL,
	CONSTRAINT pk_compra PRIMARY KEY(codigo),
	CONSTRAINT fk_compra_fornecedor FOREIGN KEY(cod_fornecedor) REFERENCES fornecedor(codigo)
);

alter table compra drop column quantidade;
alter table compra add column data TIMESTAMP not null;

   
CREATE TABLE atendimento (
	codigo SERIAL NOT NULL,
	descricao VARCHAR(200) NOT NULL,
	data_hora TIMESTAMP NOT NULL,
	pago CHAR(1) NOT NULL,
	cod_cliente INTEGER NOT NULL,
	CONSTRAINT ck_pago CHECK (pago in ('Y', 'N')),
	CONSTRAINT pk_atendimento PRIMARY KEY(codigo),
	CONSTRAINT fk_atendimento_cliente FOREIGN KEY(cod_cliente) REFERENCES cliente(codigo)
);

CREATE TABLE produto_atendimento (
	cod_atendimento INTEGER NOT NULL,
	cod_produto INTEGER NOT NULL,
	quantidade INTEGER NOT NULL,
	valor_total MONEY NOT NULL,
	valor_unitario MONEY NOT NULL,
	CONSTRAINT pk_produto_atendimento PRIMARY KEY(cod_atendimento, cod_produto),
	CONSTRAINT fK_cod_atendimento FOREIGN KEY(cod_atendimento) REFERENCES atendimento(codigo),
	CONSTRAINT fk_cod_produto FOREIGN KEY(cod_produto) REFERENCES produto(codigo)
);

ALTER TABLE produto_atendimento
	DROP CONSTRAINT fK_cod_atendimento;

ALTER TABLE produto_atendimento
	DROP CONSTRAINT fk_cod_produto;

ALTER TABLE produto_atendimento 
	ADD CONSTRAINT fK_cod_atendimento 
	FOREIGN KEY (cod_atendimento) 
  	REFERENCES atendimento(codigo) 
  	ON DELETE CASCADE;
  
ALTER TABLE produto_atendimento 
	ADD CONSTRAINT fk_cod_produto 
	FOREIGN KEY (cod_produto) 
  	REFERENCES produto(codigo) 
  	ON DELETE CASCADE;


alter table produto_atendimento alter column valor_total type decimal;
alter table produto_atendimento alter column valor_total set not null;

alter table produto_atendimento alter column valor_unitario type decimal;
alter table produto_atendimento alter column valor_unitario set not null;
 
CREATE TABLE compra_produto (
	cod_compra INTEGER NOT NULL,
	cod_produto INTEGER NOT NULL,
	quantidade INTEGER NOT NULL,
	valor_total MONEY NOT NULL,
	valor_unitario MONEY NOT NULL,
	CONSTRAINT pk_compra_produto PRIMARY KEY (cod_compra, cod_produto),
	CONSTRAINT fk_cod_compra FOREIGN KEY(cod_compra) REFERENCES compra(codigo),
	CONSTRAINT fk_cod_produto FOREIGN KEY (cod_produto) REFERENCES produto(codigo)
);

alter table compra_produto alter column valor_total type decimal;
alter table compra_produto alter column valor_total set not null;

alter table compra_produto alter column valor_unitario type decimal;
alter table compra_produto alter column valor_unitario set not null;
  
CREATE TABLE jogo (
	data_hora TIMESTAMP NOT NULL,
	valor MONEY NOT NULL,
	descricao VARCHAR(200),
	desconto MONEY NOT NULL,
	cod_atendimento INTEGER NOT NULL,
	CONSTRAINT pk_jogo PRIMARY KEY(data_hora),
	CONSTRAINT fK_jogo_atendimento FOREIGN KEY(cod_atendimento) REFERENCES atendimento(codigo)
);

alter table jogo alter column valor type decimal;
alter table jogo alter column valor set not null;

alter table jogo alter column desconto type decimal;
alter table jogo alter column desconto set not null;

alter table valor_ref drop column inicio;
alter table valor_ref drop column fim;

alter table valor_ref add column inicio date;
alter table valor_ref add column fim date;




