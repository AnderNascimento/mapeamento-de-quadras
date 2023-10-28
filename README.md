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
