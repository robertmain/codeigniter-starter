<?php

use PHPUnit\Framework\TestCase;

use App\Models\User as UserModel;

class User extends TestCase
{

    /**
     * @var UserModel
     */
    private $user_model;

    /***/
    public function __construct()
    {
        parent::__construct();

        $this->user_model = Mockery::mock(UserModel::class)->makePartial();
    }

    /**
     * @test
    */
    public function is_granted_login_with_a_valid_username_and_password()
    {
        $user = (object)[
            'username' => 'mrtesting',
            'password' => password_hash('abc', PASSWORD_DEFAULT)
        ];

        $this->user_model->shouldReceive('get_by')
                         ->with(['username' => 'mrtesting'])
                         ->andReturn($user);

        $valid = $this->user_model->password_verify('mrtesting', 'abc');

        $this->assertTrue($valid);
    }

    public function tearDown()
    {
        Mockery::close();
    }

}
