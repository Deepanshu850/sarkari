<?php

namespace App\Models;

use App\Core\Model;

class User extends Model {
    protected string $table = 'users';

    public function findByEmail(string $email): ?array {
        return $this->whereFirst('email', $email);
    }

    public function findByResetToken(string $token): ?array {
        return $this->rawOne(
            "SELECT * FROM users WHERE reset_token = ? AND reset_expires > NOW()",
            [$token]
        );
    }
}
