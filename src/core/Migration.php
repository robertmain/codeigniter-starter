<?php

namespace Core;

use \CI_Migration;

/**
 * Base Migration
 *
 * Abstract base migration to extend into concrete migrations. Useful for specifying base migration behaviour etc.
 */
abstract class Migration extends CI_Migration{

    /**
     * @var Array Record metadata that can be easily added to any migration
    */
    protected $metaData = [
        'deleted' => [
            'type'       => 'INT',
            'constraint' => '1',
            'default'    => 0,
            'null'       => false
        ],
        'created_at' => [
            'type' => 'DATETIME'
        ],
        'updated_at' => [
            'type' => 'DATETIME'
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
}
