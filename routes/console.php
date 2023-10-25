<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Artisan::command('make:admin', function () {
    $hasAdmin = User::query()->where('type', 1)->count();

    if($hasAdmin > 0){
        return $this->info('Já existe um Administrador');
    }

    $email    = $this->ask('Digite um e-mail');
    $name     = $this->ask('Digite o nome');
    $password = $this->secret('Digite a senha');

    $user = new User();
    $user->type = 1; //1 = Admin 2 = Padrão
    $user->name = $name;
    $user->email = $email;
    $user->password = $password;
    $user->save();

    $this->info('Usuário criado/atualizado com sucesso');
});
