<?php
namespace App\Services;

interface AuthServiceInterface
{
    public function doLogin(string $email, string $password);
    public function doRegister(string $name,string $email, string $password);
}