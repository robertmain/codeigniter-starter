<?php

use Mockery\Adapter\Phpunit\MockeryTestCase;

use App\Models\User as UserModel;

class User extends MockeryTestCase
{

    /**
     * @var UserModel
     */
    private $user_model;

    /**
     * @var Object
     */
    private $test_user;

    public function setUp()
    {
        $this->user_model = Mockery::mock(UserModel::class)->makePartial();

        $this->test_user = (object)[
            'username' => 'mrtesting',
            'password' => password_hash('abc', PASSWORD_DEFAULT)
        ];
    }

    /**
     * @test
    */
    public function is_granted_login_with_a_valid_username_and_password()
    {
        $this->user_model->shouldReceive('get_by')
                         ->with(['username' => 'mrtesting'])
                         ->andReturn($this->test_user);

        $valid = $this->user_model->password_verify('mrtesting', 'abc');

        $this->assertTrue($valid);
    }

    /**
     * @test
     */
    public function is_denied_login_if_username_is_incorrect_or_does_not_exist()
    {
        $this->user_model->shouldReceive('get_by')
                         ->with(['username' => 'dontexist'])
                         ->andReturn(null);

        $valid = $this->user_model->password_verify('dontexist', 'abc');

        $this->assertFalse($valid);
    }

    /**
     * @test
     */
    public function is_denied_login_if_password_is_incorrect()
    {
        $this->user_model->shouldReceive('get_by')
                         ->with(['username' => 'mrtesting'])
                         ->andReturn($this->test_user);

        $valid = $this->user_model->password_verify('mrtesting', 'def');

        $this->assertFalse($valid);
    }

    public function tearDown()
    {
        Mockery::close();
    }

}
