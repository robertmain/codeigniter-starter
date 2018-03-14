<?php

use PHPUnit\Framework\TestCase;
use App\Core\Model as BaseModel;
use Exceptions\Data\ValidationException;

class Model extends TestCase
{
    public function prevents_the_insertion_of_invalid_data()
    {
        $model     = Mockery::mock(BaseModel::class)->makePartial();
        $model->db = Mockery::mock(CI_DB_query_builder::class);

        $this->set_protected_property($model, 'validation_rules', ['firstname' => 'required']);

        $model->db->shouldNotReceive('insert');
        $model->db->shouldNotReceive('insert_id');

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
