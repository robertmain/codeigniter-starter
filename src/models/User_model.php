<?php

use Core\ABM;

class User_model extends ABM
{
    public function save($data, $id = null)
    {
        if (array_key_exists('password', $data) && strlen($data['password']) > 0) {
            $data['password'] = self::password_hash($data['password']);
        } else {
            unset($data['password']);
        }

        return parent::save($data, $id);
    }

    /**
     * Ensure a username and password match up to the values in the database
     *
     * @param string $username The username of the user to verify the password for
     * @param string $password The password to verify
     *
     * @return bool True for valid, false for invalid
    */
    public function password_verify($username, $password)
    {
        $user = $this->get_by(['username' => $username]);

        return password_verify($password, $user->password);
    }

    /**
     * Hash a password using PHP's `password_hash()` function
     *
     * @param string $password The password to hash
     *
     * @return string A password hash containing the salt and hashing algorithm used
    */
    private static function password_hash($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}
