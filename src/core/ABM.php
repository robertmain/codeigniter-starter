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
     * Update a single record in the database
     *
     * @param mixed $primary_value The primary key value of the record to update
     * @param array $data          An ascociative array of update values
     *
     * @return boolean Indication of the success of the update
     */
    private function update($primary_value, $data)
    {
        return $this->db->where(static::PRIMARY_KEY, $primary_value)
                        ->update($this->table, $data);
    }

    /**
     * Insert a single record into the database
     *
     * @param array $data An ascociative array of data to insert into the database
     *
     * @return int The primary key value of the newly created record
    */
    private function insert($data)
    {
        $this->db->insert($data);

        return $this->db->insert_id();
    }

    /**
     * Set WHERE parameters, cleverly
     *
     * @param array $params An array of where clause parameters to be applied to the query
     * @return void
     */
    private function set_where($params)
    {
        if (count($params) == 1)
        {
            $this->db->where($params[0]);
        }
        else
        {
            $this->db->where($params[0], $params[1]);
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
     * Saves (or creates) a single record if it doesn't already exist. If the `$id1 parameter is provided, the record
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
    public function delete($primary_value, $soft = true)
    {
        if ($soft) {
            return (bool)$this->save([static::DELETED => date(MYSQL_DATETIME)], $id);
        } else {
            return $this->db->delete($this->table, [static::PRIMARY_KEY => $primary_value]);
        }
    }

    /**
     * Retrieve a single record from the database
     *
     * @param int $primary_value The primary key value of the record to retrieve
    */
    public function get($primary_value)
    {
        return $this->get_by([static::PRIMARY_KEY => $primary_value]);
    }

    /**
     * Retrieve a single record based on an arbitary key=>value WHERE clause
     *
     * @param array $where Description
    */
    public function get_by($where)
    {
        return $this->db->where($where)
                        ->get($this->table)
                        ->row();
    }
}
