<?php

namespace Core;

use \MY_Model;

/**
 * Abstract Base Model
 *
 * Abstract database model. Extends Jamie Rumbelow's base model and adds additional functionality such as
 * created/updated timestamps, etc.
 */
abstract class ABM extends MY_Model
{
    protected $after_get = ['date_objects'];

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

        $data['updated_at'] = $date->format(MYSQL_DATETIME);

        if ($id) {
            $this->update($id, $data);
            return $id;
        } else {
            $data['created_at'] = $date->format(MYSQL_DATETIME);
            return $this->insert($data);
        }
    }

    /**
     * Expand MySQL DateTime updated/created at fields into php DateTime objects
     *
     * @param object $row Database row
     * @param object $row Database row
    */
    protected function date_objects($row)
    {
        $row->created_at = \DateTime::createFromFormat(MYSQL_DATETIME, $row->created_at);
        $row->updated_at = \DateTime::createFromFormat(MYSQL_DATETIME, $row->updated_at);

        return $row;
    }

}
