# TruckPad Api Documentation


## Instale a última versão do [PHP](https://www.php.net/downloads.php), do [MySql](https://dev.mysql.com/downloads) e do [Composer](https://getcomposer.org/download) e siga os passos abaixo

### `Passo 1`
Crie os esquemas de banco de dados executando as queries abaixo:
```bash
CREATE SCHEMA `truckpad` DEFAULT CHARACTER SET utf8 ;
CREATE SCHEMA `truckpad_test` DEFAULT CHARACTER SET utf8 ;
```

### `Passo 2`
Insira os dados dos tipos de caminhões no schema **_`truckpad`_** executando a query abaixo:
```bash
INSERT INTO `truckpad`.`types` (`name`, `created_at`, `updated_at`) VALUES ('Caminhão 3/4', '2020-03-04 10:51:18', '2020-03-04 10:51:18');
INSERT INTO `truckpad`.`types` (`name`, `created_at`, `updated_at`) VALUES ('Caminhão Toco', '2020-03-04 10:51:18', '2020-03-04 10:51:18');
INSERT INTO `truckpad`.`types` (`name`, `created_at`, `updated_at`) VALUES ('Caminhão Truck', '2020-03-04 10:51:18', '2020-03-04 10:51:18');
INSERT INTO `truckpad`.`types` (`name`, `created_at`, `updated_at`) VALUES ('Carreta Simples', '2020-03-04 10:51:18', '2020-03-04 10:51:18');
INSERT INTO `truckpad`.`types` (`name`, `created_at`, `updated_at`) VALUES ('Carreta Eixo Extendido', '2020-03-04 10:51:18', '2020-03-04 10:51:18');
```

### `Passo 3`
Altere a configuração do banco de dados no arquivo **_`.env`_**

Example:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=truckpad
DB_USERNAME=root
DB_PASSWORD=123456
```
e também no banco de dados de teste no arquivo **_`phpunit.xml`_** na raiz do projeto.
```bash
<env name="DB_CONNECTION" value="mysql"/>
<env name="DB_HOST" value="127.0.0.1"/>
<env name="DB_PORT" value="3306"/>
<env name="DB_DATABASE" value="truckpad_test"/>
<env name="QUEUE_CONNECTION" value="root"/>
<env name="QUEUE_CONNECTION" value="123456"/>
```

### `Passo 4`
Pelo terminal acesse a página no projeto e execute o comando abaixo:

4.1 Para installar as dependências:
```bash
composer update
```
4.2 Para criar as tabelas no banco de dados
```bash
php artisan migrate
```

4.3 Para fazer rollback das migrações execute o comando abaixo:
```bash
php artisan migrate:rollback
```

### `Passo 5`
Pelo terminal execute o comando abaixo se quiser vê-lo rodando no navegador.
```bash
php -S localhost:8000 -t public
```

5.1 Acesse a url [http://localhost:8000] para ver o projeto ou você pode apenas fazer as chamadas para os endpoints, leia a documentação(swagger) da api caso precise.

### `Passo 6`
Para executar os testes execute o comando abaixo direto no terminal.
```bash
./vendor/bin/phpunit
```

6.1 Para ver o test coverage abra o arquivo index.html no navegador, o arquivo está localizado na pasta: **_`/tests/build/coverage/index.html`_**

Obs. é necessário habilitar o [xdebug](https://xdebug.org) para o test coverage.

## License

[![License](https://img.shields.io/badge/License-Apache%202.0-blue.svg)](https://opensource.org/licenses/Apache-2.0)
