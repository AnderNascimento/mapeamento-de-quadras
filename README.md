# Mapeamento de Quadras PHP API

Este projeto tem como objetivo implementar um CRUD para auxílio no cadastro de quadras. Com estas quadras as pessoas envolvidas conseguem gerar relatórios que as auxiliam na organização de suas rotinas.

Exemplos de uso: Criação de escala de trabalho para profissionais que trabalham de porta-a-porta, como entregadores, fiscais ou agentes de saúde.

## Bibliotecas utilizadas

Este projeto utiliza as bibliotecas:
- [DantSu PHP OSM STATIC API](https://github.com/DantSu/php-osm-static-api)
- barryvdh/laravel-dompdf
- php-open-source-saver/jwt-auth


## Features

Esta API possui métodos:
- Autenticação.
- Criação de usuário padrão (Cadastrador de quadras) e administrador.
- Criação, atualização, exclusão de quadras 
- Exportação de relatórios para PDF.

## Exemplo de relatório

[Relatório PDF](exemplo-relatorio.pdf)


## Instalação

Instale esta biblioteca com o comando
´´´
composer create laravel/laravel mapeamento-de-quadras

cd mapeamento-de-quadras
composer install
cp .env.example .env
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

