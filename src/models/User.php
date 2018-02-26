<?php

namespace App\Models;

use App\Core\Model;

/**
 * Abstraction of user related business logic in the application
 */
class User extends Model
{
    /**
     * {@inheritdoc}
     */
    public function save($data, $id = null)
    {
        if (array_key_exists('password', $data) && strlen($data['password']) > 0) {
            $data['password'] = $this->password_hash($data['password']);
        } else {
            unset($data['password']);
        }

        return parent::save($data, $id);
    }

    /**
     * Ensure a username and password match up to the values in the database
     *
     * @param string $username The username of the user to verify the password for
     * @param string $password The unhashed password supplied by the user in plain text
     *
     * @return bool True for valid, false for invalid
    */
    public function password_verify($username, $password)
    {
        $user = $this->get_by(['username' => $username]);
        if ($user) {
            return password_verify($password, $user->password);
        } else {
            return false;
        }
    }

    /**
     * Hash a password securely for storage in the database
     * '
     * @internal
     *
     * @param string $password The password to hash
     *
     * @return string A password hash containing the salt and hashing algorithm used
    */
    protected function password_hash($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}
