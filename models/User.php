<?php

namespace app\models;

use app\src\helpers\Logger;
use app\src\JsonModel;
use app\src\Model;

class User extends JsonModel
{
    public string $id;
    public string $firstname = '';
    public string $lastname = '';
    public string $email = '';
    public string $password = '';
    public string $passwordConfirm = '';

    public static function fileName(): string
    {
        return 'users';
    }

    public function attributes(): array
    {
        return ['firstname', 'lastname', 'email', 'password'];
    }

    public function labels(): array
    {
        return [
            'firstname' => 'First name',
            'lastname' => 'Last name',
            'email' => 'Email',
            'password' => 'Password',
            'passwordConfirm' => 'Password Confirm'
        ];
    }
    public function rules(): array
    {
        return [
            'firstname' => [self::RULE_REQUIRED],
            'lastname' => [self::RULE_REQUIRED],
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL, [
                self::RULE_UNIQUE, 'class' => self::class
            ]],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8]],
            'passwordConfirm' => [[self::RULE_MATCH, 'match' => 'password']],

        ];
    }

    public function save(): bool
    {
        $this->id = uniqid();
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return parent::save();
    }

    public function validate(): bool
    {
        $status = parent::validate();

        $this->getLogger()->log(
            $status ? 'SUCCESS' : 'ERROR',
            $status ? "User {$this->email} registration successful" : "$this->email - " . json_encode($this->errors)
        );

        return $status;
    }
}