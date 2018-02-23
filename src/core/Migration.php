<?php

namespace App\Core;

use \CI_Migration;
use App\Core\Model;

/**
 * Base Migration
 *
 * Abstract base migration to extend into concrete migrations. Useful for specifying base migration behaviour etc.
 */
abstract class Migration extends CI_Migration
{
    /**
     * @var Array Record metadata that can be easily added to any migration
    */
    protected $date_stamps = [
        Model::CREATED => [
            'type' => 'DATETIME',
            'null' => false
        ],
        Model::UPDATED => [
            'type' => 'DATETIME',
            'null' => false
        ],
        Model::DELETED => [
            'type' => 'DATETIME',
            'null' => true
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
