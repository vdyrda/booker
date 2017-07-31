<?php
class Password {
    public static function encrypt($password) {
        $options = [
            'cost' => SALT_GENERATE_COST,
            'salt' => (function_exists('random_bytes')) ? random_bytes(SALT_LENGTH) : mcrypt_create_iv(SALT_LENGTH, MCRYPT_DEV_URANDOM)
        ];
        return password_hash($password, PASSWORD_BCRYPT, $options);
    }
    
    public static function verify($password, $hash) {
        return password_verify($password, $hash);
    }
}
