<?php

namespace Core;

use \CI_Migration;

/**
 * Base Migration
 *
 * Abstract base migration to extend into concrete migrations. Useful for specifying base migration behaviour etc.
 */
abstract class Migration extends CI_Migration
{
    /**
    * @var string The name for the field used to store record creation timestamp
    */
   const CREATED = 'created_at';

   /**
    * @var string The name for the field used to store record update timestamp
   */
   const UPDATED = 'updated_at';

   /**
    * @var string The name for the field used to store record update timestamp
   */
    const DELETED = 'deleted_at';

    /**
     * @var Array Record metadata that can be easily added to any migration
    */
    protected $metaData = [];

    /**
     * Actions required to perform the migration
    */
    abstract public function up();

    /**
     * Actions required to roll back the migration
    */
    abstract public function down();

    /**
     * Define the datetime metadata fields for inheritance in child migrations
    */
    public function __construct($config = [])
    {
        parent::__construct($config);

        $this->metaData = [
            static::CREATED => [
                'type' => 'DATETIME',
                'null' => false
            ],
            static::UPDATED => [
                'type' => 'DATETIME',
                'null' => false
            ],
            static::DELETED => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ];
    }
}
