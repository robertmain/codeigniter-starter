<?php

use PHPUnit\Framework\TestCase;
use App\Core\Model as BaseModel;
use Exceptions\Data\ValidationException;

class Model extends TestCase
{

    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    /**
     * @var BaseModel Abstract base model instance
     */
    private $model;


    public function setUp()
    {
        $this->model     = Mockery::mock(BaseModel::class)->makePartial();
        $this->model->db = Mockery::mock(CI_DB_query_builder::class);
    }

    /**
     * @test
     */
    public function data_cannot_be_created_if_validation_fails()
    {
        $this->set_protected_property($this->model, 'validation_rules', ['firstname' => 'required']);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The firstname field is required');

        $this->model->insert(['lastname' => 'Smith']);
    }

    /**
     * @test
    */
    public function data_cannot_be_updated_if_validation_fails()
    {
        $this->set_protected_property($this->model, 'validation_rules', ['firstname' => 'required']);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The firstname field is required');

        $this->model->update(3, ['lastname' => 'Smith']);
    }

    /**
     * @test
    */
    public function data_can_be_updated()
    {

        $this->model->db->shouldReceive('where')
                        ->with(BaseModel::PRIMARY_KEY, 3)
                        ->andReturn($this->model->db);

        $this->model->db->shouldReceive('update')
                        ->with(null, ['lastname' => 'Smith'])
                        ->andReturn(true);

        $this->model->update(3, ['lastname' => 'Smith']);
    }


    /**
     * Set the value of a private or protected property for testing using reflection
     *
     * @param stdClass $obj      The property to modify
     * @param string   $property The name of the property to modify
     * @param mixed    $value    The value to set on the property
     *
     * @return void
     */
    private function set_protected_property($obj, $property, $value) : void
    {
        $reflect  = new \ReflectionObject($obj);
        $property = $reflect->getProperty($property);
        $property->setAccessible(true);
        $property->setValue($obj, $value);
    }
}
