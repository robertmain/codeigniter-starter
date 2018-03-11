<?php
use PHPUnit\Framework\TestCase;

use App\Models\User as UserModel;

class User extends TestCase
{
    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

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
        $this->user_model = Mockery::mock(UserModel::class)->makePartial()
                                                           ->shouldAllowMockingProtectedMethods();

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

    /**
     * @test
     */
    public function password_can_be_changed()
    {
        $this->user_model->shouldReceive('password_hash')
                         ->with('hello')
                         ->andReturn('secure_hash');

        $this->user_model->shouldReceive('update')
                         ->with(6, Mockery::subset(['password' => 'secure_hash']));

        $this->user_model->save(['password' => 'hello'], 6);
    }

    /**
     * @test
    */
    public function password_remains_unchanged_if_not_supplied()
    {
        $this->user_model->shouldNotreceive('password_hash');

        $this->user_model->shouldReceive('update')
                         ->once()
                         ->with(6, Mockery::on(function($data) {
                            return !array_key_exists('password', $data);
                         }));

        $this->user_model->save(['firstname' => 'bob'], 6);
    }

    /**
     * @test
    */
    public function password_remains_unchanged_if_less_than_one_char()
    {
        $this->user_model->shouldNotreceive('password_hash');

        $this->user_model->shouldReceive('update')
                         ->once()
                         ->with(6, Mockery::on(function($data) {
                            return !array_key_exists('password', $data);
                         }));

        $this->user_model->save(['password' => ''], 6);
    }

    public function tearDown()
    {
        Mockery::close();
    }

}
