___

# Projeto de sistema de controle de caixa em quadras de futebol society: Projeto realizado para trabalho da disciplina de Banco de Dados

___

##### by: Alex Jean Lemes, Henrique Comin, João Vítor Batistella e José Gomes

___

## Tecnologias usadas

* PHP nativo
* Vue.js
* Nuxt.js
* Vuetify.js

___

## Requisitos para instalar

* PHP 7.4
* Postgres
* NPM - Gerenciador de pacote

___

## Passo a passo

* Clone o repositório para a pasta dos seus projetos apache;
* Você deverá configurar o servidor virtual do apache direcionando para a pasta 'api/' do projeto
###### Veja o exemplo: 

```txt
<VirtualHost football-society:80>
  DocumentRoot "/var/www/football-society/api/"
  ServerName football-society
  <Directory "/var/www/football-society/api/">
    AllowOverride All
    Require all granted
  </Directory>
</VirtualHost>
```

* Na pasta 'api/', devemos renomear o arquivo 'env_example.php' para 'env.php' e completar com os dados do seu BD e seu SECRET, este para a criptografia JWT;
* Acesse seu terminal ou PowerShell e siga as instruções abaixo

```bash
npm install
```

* Na pasta 'front/', procure pelo arquivo 'nuxt.config.js', nele você terá de especificar o endereço da sua api, ex:

```js
publicRuntimeConfig: {
    axios: {
      baseURL: 'http://football-society/'
    }
  },
```

* Então basta rodar o servidor de desenvolvimento:

```bash
npm run dev
```

* Este último comando irá iniciar o servidor do Nuxt.js localmente, usando a porta 3000
* Acesse usando: http://127.0.0.1:3000


* Por fim você deve executar o 'script.sql' contido na pasta 'api/', em seu banco Postgres
* Nesta versão, somente é possível fazer a inserção de novos usuários via BD. Por comodidade, o usuário 'joaovb' na qual senha é 'Adm987321', já está disponível para uso.

### Bom proveito e bons estudos!!! 

___


#### Para a próxima versão

* Criptografia de senha
* Trigger para expirar tokens após 1 dia
* CRUD para a tabela compra_produto
___

## To-Do: Rest API

#### Usuários

* Requisitos:
  * [x] __GET:__ Mostrar todos os usuários __" /users/list "__
  * [x] __GET:__ Mostrar usuários filtrando pelo nome de usuário
  * [x] __GET:__ Mostrar usuário por código
  * [x] __POST:__ Inserir um novo usuário
  * [x] __PUT:__ Editar um produto, passando seu ID
  * [x] __DELETE:__ Excluir um produto passando seu ID
  ___

#### Produto

* Requisitos:
  * [x] __GET:__ Mostrar todos os produtos
  * [x] __GET:__ Mostrar produtos filtrando pelo nome ou codigo
  * [x] __POST:__ Inserir um novo produto
  * [x] __PUT:__ Editar um produto, passando seu ID
  * [x] __DELETE:__ Excluir um produto passando seu ID
  ___

#### Cliente

* Requisitos:
  * [x] __GET:__ Mostrar todos os clientes, __(JOIN na tabela cidade)__
  * [x] __GET:__ Mostrar clientes filtrando pelo nome, cpf ou email
  * [X] __POST:__ Inserir um novo cliente
  * [x] __PUT:__ Editar um cliente, passando seu ID
  * [x] __DELETE:__ Excluir um cliente passando seu ID
  ___

#### Fornecedor

* Requisitos:
  * [x] __GET:__ Mostrar todos os fornecedores, __(JOIN na tabela cidade)__
  * [x] __GET:__ Mostrar fornecedores filtrando pelo nome
  * [x] __POST:__ Inserir um novo fornecedor
  * [x] __PUT:__ Editar um fornecedor, passando seu ID
  * [x] __DELETE:__ Excluir um fornecedor passando seu ID
  ___

#### Atendimento

* Requisitos:
  * [x] __GET:__ Mostrar todos os atendimentos, __(JOIN na tabela cidade)__
  * [x] __GET:__ Mostrar atendimentos filtrando pela data
  * [x] __POST:__ Inserir um novo fornecedor
  * [x] __PUT:__ Editar um fornecedor, passando seu ID
  * [x] __DELETE:__ Excluir um fornecedor passando seu ID
  ___

#### Jogo

* Requisitos:
  * [x] __GET:__ Mostrar todos os jogos, __(JOIN na tabela atendimento)__
  * [x] __GET:__ Mostrar jogos filtrando pelo horário
  * [x] __POST:__ Inserir um novo jogo
  * [x] __PUT:__ Editar um jogo, passando seu ID
  * [x] __DELETE:__ Excluir um jogo passando seu ID
  ___
