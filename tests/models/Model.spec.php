<?php

use PHPUnit\Framework\TestCase;
use App\Core\Model as BaseModel;
use Exceptions\Data\ValidationException;

class Model extends TestCase
{

    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    /**
     * @test
     */
    public function validation_prevents_insertion_of_invalid_data()
    {
        $model = Mockery::mock(BaseModel::class)->makePartial();

        $this->set_protected_property(
            $model,
            'validation_rules',
            [
                'firstname' => 'required',
                'age'       => 'required|is_numeric|greater_than_equal_to[18]'
            ]
        );

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The firstname field is required');
        $this->expectExceptionMessage('The age field must contain a number greater than or equal to 18.');

        $model->insert(['lastname' => 'Smith', 'age' => 12]);
    }

    /**
     * @test
    */
    public function validation_prevents_update_with_invalid_data()
    {
        $model = Mockery::mock(BaseModel::class)->makePartial();

        $this->set_protected_property(
            $model,
            'validation_rules',
            [
                'age' => 'is_numeric'
            ]
        );

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The age field is required');

        $model->update(3, ['lastname' => 'Smith', 'age' => 'twelve']);
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
