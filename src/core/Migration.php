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
     * @var Array Record metadata that can be easily added to any migration
    */
    protected $metaData = [
        'deleted' => [
            'type'       => 'INT',
            'constraint' => '1',
            'default'    => 0,
            'null'       => false
        ]
    ];

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
    public function __construct($config = array())
    {
        parent::__construct();

        $this->metaData[self::CREATED] = [
            'type' => 'DATETIME'
        ];

        $this->metaData[self::UPDATED] = [
            'type' => 'DATETIME'
        ];
    }
}
