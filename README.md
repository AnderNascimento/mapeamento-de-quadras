# Mapeamento de Quadras PHP API

Este projeto utiliza a biblioteca [DantSu PHP OSM STATIC API](https://github.com/DantSu/php-osm-static-api) para manipular dados e gerar imagem do OpenStreetMap.


## Features

Esta API possui métodos de Login/Logout, criação de usuário padrão e administrador. Criação, atualização, exclusão e também exportação de quadras para PDF.

## Instalação

Instale esta biblioteca com o comando
´´´
composer require AnderNascimeno/mapeamento-de-quadras
´´´

## Criando um usuário admin

Execute o comando e informe o nome, email e senha
```
php artisan make:admin
```

## Redefinindo a senha

Execute o comando e informe o email e a nova senha
```
php artisan make:password_reset
```

## OBS

A biblioteca do OSM quebra em localhost devido ao REQUEST_SCHEME inválido, necessário alterar a LIB
```
vendor/dantsu/php-image-editor/src/Image.php linha 311
```
