<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{
    public function index();
    public function getById($id);
    public function getByemail($email);
    public function store(array $data);
    public function update(array $data, $id);
    public function delete($id);
    public function authUser(string $email, string $password);
}
