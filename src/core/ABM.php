<?php

namespace Core;

use \CI_Model;

/**
 * Abstract Base Model
 *
 * Abstract database model. Adds additional functionality such as created/updated/deleted timestamps, etc.
 */
abstract class ABM extends CI_Model
{
    /**
     * @var string Table primary key column
     */
    const PRIMARY_KEY = 'id';

    /**
     * @var string The name for the field used to store record creation timestamp
     */
    const CREATED = 'created_at';

    /**
     * @var string The name for the field used to store record update timestamp
    */
    const UPDATED = 'updated_at';

    /**
     * @var string The name for the field used to store record deletion timestamp
     */
    const DELETED = 'deleted_at';

    /**
     * @var string The name of the table data for this model is stored in
     */
    protected $table = null;

    /**
     * @var string Model lifecycle callbacks used to add or agument the existing behaviour of models in CodeIgniter
     */
    protected $after_get = ['date_objects'];

    /**
     * Dynamically set the model's database table name (though, this can be overridden..)
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['inflector']);

        if ($this->table === null) {
            $this->table = strtolower(plural(get_class($this)));
        }
    }

    /**
     * Saves (or creates) a single record if it doesn't already exist. If the `$id1 parameter is provided, the record
     * is presumed not to exist and is created. The newly created ID is then returned.
     *
     * @param array $data The data to persist to the database
     * @param int   $id   The record ID to edit (if omitted, create is assumed)
     *
     * @return int The primary key of the record that was updated/deleted
    */
    public function save($data, $id = null)
    {
        $date = new \DateTime();

        $data[static::UPDATED] = $date->format(MYSQL_DATETIME);

        if ($id) {
            $this->update($id, $data);
            return $id;
        } else {
            $data[static::CREATED] = $date->format(MYSQL_DATETIME);
            return $this->insert($data);
        }
    }

    /**
     * Expand MySQL DateTime created/updated/deleted fields into {@link \DateTime} objects
     *
     * @param object $row Database row
     * @param object $row Database row
    */
    protected function date_objects($row)
    {
        foreach ([static::CREATED, static::UPDATED, static::DELETED] as $field) {
            $row->{$field} = \DateTime::createFromFormat(MYSQL_DATETIME, $row->{$field});
        }

        return $row;
    }

    /**
     * Delete a row from the datbase by primary key
     *
     * @param int  $id   The ID of the row to delete
     * @param bool $soft Enable/Disable soft delete(enabled by default)
    */
    public function delete($id, $soft = true)
    {
        if ($soft) {
            $data = $this->_run_before_callbacks('delete', [$id]);

            $result = (bool)$this->save([static::DELETED => date(MYSQL_DATETIME)], $id);

            $this->_run_after_callbacks('delete', [$id, $result]);

            return $result;
        } else {
            return parent::delete($id);
        }
    }


}
