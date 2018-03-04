<?php

namespace App\Core;

use CI_Model;

/**
 * Abstract Base Model
 *
 * Abstract database model. Adds additional functionality such as created/updated/deleted timestamps, etc.
 */
abstract class Model extends CI_Model
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
     * @var string The name of the table data for this model is stored in. A protected variable is used to store this
     *             information instead of a class constant, as this value is computed at call time
     */
    protected $table = null;

    /**
     * @var \array<string> Lifecycle callback run prior to record creation
     */
    protected $before_create = [];

    /**
     * @var \array<string> Lifecycle callback run after record creation
    */
    protected $after_create = [];

    /**
     * @var \array<string> Lifecycle callback run prior to record update
    */
    protected $before_update = [];

    /**
     * @var \array<string> Lifecycle callback run prior to record update
    */
    protected $after_update = [];

    /**
     * @var \array<string> Lifecycle callback run prior to record retrieval
    */
    protected $before_get = [];

    /**
     * @var \array<string> Lifecycle callback run prior to record retrieval
    */
    protected $after_get = ['format_record_metadata'];

    /**
     * @var \array<string> Lifecycle callback run prior to record deletion
    */
    protected $before_delete = [];

    /**
     * @var \array<string> Lifecycle callback run prior to record deletion
    */
    protected $after_delete = [];

    /**
     * Dynamically set the model's database table name (though, this can be overridden..)
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->helper(['inflector']);

        $this->load->database();

        if ($this->table === null) {
            $className = (new \ReflectionClass($this))->getShortName();
            $this->table = strtolower(plural($className));
        }
    }

    /**
     * Update a single record in the database
     *
     * @param mixed $primary_value The primary key value of the record to update
     * @param array $data          An ascociative array of update values
     *
     * @return boolean Indication of the success of the update
     */
    protected function update($primary_value, $data) : \boolean
    {
        $data = $this->run_before_callbacks('update', [$data, $primary_value]);

        $result = $this->db->where(static::PRIMARY_KEY, $primary_value)
                           ->update($this->table, $data);

        $this->run_after_callbacks('update', [$data, $primary_value, $result]);

        return $result;
    }

    /**
     * Insert a single record into the database
     *
     * @param array $data An ascociative array of data to insert into the database
     *
     * @return int The primary key value of the newly created record
    */
    protected function insert($data) : int
    {
        $data = $this->run_before_callbacks('create', [$data]);

        $this->db->insert($this->table, $data);
        $insert_id = $this->db->insert_id();

        $this->run_after_callbacks('create', [$data, $insert_id]);

        return $insert_id;
    }

    /**
     * Run the before_ callbacks, each callback taking a $data variable and returning it
     *
     * @param string        $type   The type of callback to be run (can be create, update, get or delete)
     * @param array<string> $params Array of parameters to be passed to the callback
     *
     * @return mixed
     */
    private function run_before_callbacks($type, $params = [])
    {
        $name = 'before_' . $type;
        $data = (isset($params[0])) ? $params[0] : false;

        if (!empty($this->$name))
        {
            foreach ($this->$name as $method)
            {
                $data += call_user_func_array(array($this, $method), $params);
            }
        }

        return $data;
    }

    /**
     * Run the after_ callbacks, each callback taking a $data variable and returning it
     *
     * @param string        $type   The type of callback to be run (can be create, update, get or delete)
     * @param array<string> $params Array of parameters to be passed to the callback
     *
     * @return mixed
     */
    private function run_after_callbacks($type, $params = [])
    {
        $name = 'after_' . $type;
        $data = (isset($params[0])) ? $params[0] : false;

        if (!empty($this->$name))
        {
            foreach ($this->$name as $method)
            {
                $data = call_user_func_array(array($this, $method), $params);
            }
        }

        return $data;
    }

    /**
     * Expand MySQL DateTime created/updated/deleted fields into {@link \DateTime} objects
     *
     * @param object $row Database row
     * @param object $row Database row
    */
    protected function format_record_metadata($row)
    {
        foreach ([static::CREATED, static::UPDATED, static::DELETED] as $field) {
            if (isset($row->{$field})) {
                $row->{$field} = \DateTime::createFromFormat(MYSQL_DATETIME, $row->{$field});
            }
        }

        return $row;
    }

    /**
     * Saves (or creates) a single record if it doesn't already exist. If the `$id` parameter is provided, the record
     * is presumed not to exist and is created. The newly created ID is then returned.
     *
     * @param array $data The data to persist to the database
     * @param int   $id   The primary key value of the record to edit (if omitted, create is assumed)
     *
     * @return int The primary key of the record that was updated/deleted
    */
    public function save($data, $id = null)
    {
        $date = new \DateTime();

        $data[static::UPDATED] = $date->format(MYSQL_DATETIME);

        if ($id) {
            $success = $this->update($id, $data);
            if($success) {
                return $id;
            } else {
                return false;
            }
        } else {
            $data[static::CREATED] = $date->format(MYSQL_DATETIME);
            return $this->insert($data);
        }
    }

    /**
     * Delete a row from the datbase by primary key
     *
     * @param int  $primary_value The primary key value of the row to delete
     * @param bool $soft          Soft delete(enabled by default)
    */
    public function delete($primary_value, $soft = true) : boolean
    {
        $this->run_before_callbacks('delete', [$primary_value]);

        if ($soft) {
            $result = $this->db->where([static::PRIMARY_KEY => $primary_value])
                               ->update($this->table, [static::DELETED => date(MYSQL_DATETIME)]);
        } else {
            $result = $this->db->delete($this->table, [static::PRIMARY_KEY => $primary_value]);
        }

        $this->run_after_callbacks('delete', [$primary_value, $result]);

        return $result;
    }

    /**
     * Retrieve a single record from the database
     *
     * @param int  $primary_value   The primary key value of the record to retrieve
     * @param bool $include_deleted Include deleted records in the result set (defaults to false)
    */
    public function get($primary_value, $include_deleted = false)
    {
        return $this->get_by([static::PRIMARY_KEY => $primary_value], $include_deleted);
    }

    /**
     * Retrieve a single record based on an arbitary key=>value WHERE clause
     *
     * @param array $where           An arbitary WHERE clause determining which records to return
     * @param bool  $include_deleted Include deleted records in the result set (defaults to false)
     *
     * @return object|null The object matching the specified query(if any)
    */
    public function get_by($where, $include_deleted = false)
    {
        $this->run_before_callbacks('get');

        if (!$include_deleted) {
            $this->db->where([static::DELETED => null]);
        }

        $row = $this->db->where($where)
                        ->get($this->table)
                        ->row();

        return $this->run_after_callbacks('get', [$row]);
    }

    /**
     * Retrieve many records by an arbitary WHERE clause
     *
     * @param array $where           An asociative array containing the WHERE clause to retrieve records by
     * @param bool  $include_deleted Include deleted records in the result set (defaults to false)
     *
     * @return \array<stdObject> An array of objects matching the query
    */
    public function get_many_by($where, $include_deleted = false) : array
    {
        $this->db->where($where);
        return $this->get_all($include_deleted);
    }

    /**
     * Retrieve all records for a particular model
     *
     * @param bool $include_deleted Include deleted records in the result set (defaults to false)
     *
     * @return \array<stdObject> An array of objects representing the records in the database
     */
    public function get_all($include_deleted = false) : array
    {
        $this->run_before_callbacks('get');

        if (!$include_deleted) {
            $this->db->where([static::DELETED => null]);
        }

        $result = $this->db->get($this->table)
                           ->result();

        return array_map(function ($row) {
            return $this->run_after_callbacks('get', [$row]);
        }, $result);
    }
}
