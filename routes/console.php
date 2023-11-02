<?php

use App\Mail\NewPasswordMail;
use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('make:admin', function () {
    $hasAdmin = User::query()->where('type', 1)->count();

    if ($hasAdmin > 0) {
        return $this->info('Já existe um Administrador');
    }

    $email = $this->ask('Digite um e-mail');
    $name = $this->ask('Digite o nome');
    $password = $this->secret('Digite a senha');

    $user = new User();
    $user->type = 1; //1 = Admin 2 = Padrão
    $user->name = $name;
    $user->email = $email;
    $user->password = $password;
    $user->save();

    $this->info('Usuário criado/atualizado com sucesso');
});

Artisan::command('make:password_reset', function () {
    $email = $this->ask('Digite o e-mail');

    $user = User::query()->where('email', $email)->first();

    if (! $user) {
        return $this->info('Email não encontrado!');
    }

    $password = Str::random(8);
    $user->password = bcrypt($password);
    $user->update();

    Mail::to($user->email)->send(new NewPasswordMail(['password' => $password]));

    $this->info('Senha atualizada com sucesso');
});
