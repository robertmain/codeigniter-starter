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
    /**
     * @var string The name for the field used to store record creation timestamp
     */
    const CREATED = 'created_at';

    /**
     * @var string The name for the field used to store record update timestamp
    */
    const UPDATED = 'updated_at';

    /**
     * @var string Model lifecycle callbacks used to add or agument the existing behaviour of models in CodeIgniter
     */
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

        $data[self::UPDATED] = $date->format(MYSQL_DATETIME);

        if ($id) {
            $this->update($id, $data);
            return $id;
        } else {
            $data[self::CREATED] = $date->format(MYSQL_DATETIME);
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
        $row->{self::CREATED} = \DateTime::createFromFormat(MYSQL_DATETIME, $row->{self::CREATED});
        $row->{self::UPDATED} = \DateTime::createFromFormat(MYSQL_DATETIME, $row->{self::UPDATED});

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

            $result = (bool)$this->save(['deleted' => true], $id);

            $this->_run_after_callbacks('delete', [$id, $result]);

            return (bool)$result;
        } else {
            return parent::delete($id);
        }
    }

}
