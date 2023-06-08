<?php

/**
 * Model Class
 * 
 * This class serves as a base model for interacting with the database.
 * It extends the Database class and provides common CRUD operations.
 */
class Model extends Database
{
    protected $table;

    /**
     * Model constructor.
     * 
     * @param string $databaseFile The path to the database file.
     * If not provided, it defaults to the value specified in the DB_PATH environment variable.
     */
    public function __construct($databaseFile = '')
    {
        if (empty($databaseFile)) {
            $databaseFile = '../' . $_ENV['DB_PATH'];
        }
        parent::__construct($databaseFile);
    }

    /**
     * Get all records from the table.
     * 
     * @return array An array of all records from the table.
     */
    public function getAll($conditions = array(), $operator = "AND")
    {
        return $this->search($this->table, $conditions, $operator);
    }

    /**
     * Get a record by its ID.
     * 
     * @param int $id The ID of the record to retrieve.
     * @return array|null The record matching the provided ID, or null if not found.
     */
    public function getById($id)
    {
        return $this->get($this->table, $id);
    }

    /**
     * Create a new record in the table.
     * 
     * @param array $data An associative array of data for the new record.
     * @return int The ID of the newly created record.
     */
    public function create($data)
    {
        return $this->insertRow($this->table, $data);
    }

    /**
     * Update a record in the table.
     * 
     * @param int $id The ID of the record to update.
     * @param array $data An associative array of data to update in the record.
     */
    public function update($id, $data)
    {
        $this->updateRow($this->table, $id, $data);
    }

    /**
     * Delete a record from the table.
     * 
     * @param int $id The ID of the record to delete.
     */
    public function delete($id)
    {
        $this->deleteRow($this->table, $id);
    }
}