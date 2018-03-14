<?php

use PHPUnit\Framework\TestCase;
use App\Core\Model as BaseModel;
use Exceptions\Data\ValidationException;

class Model extends TestCase
{

    /**
     * @var BaseModel Abstract base model instance
     */
    private $model;


    public function setUp()
    {
        $this->model     = Mockery::mock(BaseModel::class)->makePartial();
    }

    /**
     * @test
     */
    public function prevents_the_insertion_of_invalid_data()
    {
        $this->set_protected_property($this->model, 'validation_rules', ['firstname' => 'required']);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The firstname field is required');

        $this->model->insert(['lastname' => 'Smith']);
    }


        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The firstname field is required');

        $model->insert(['lastname' => 'Smith']);
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
