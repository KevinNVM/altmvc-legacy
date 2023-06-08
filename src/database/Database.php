<?php

class Database
{
    private $db;
    private $path;

    /**
     * Constructor for the Database class.
     *
     * @param string $databaseFile The path to the SQLite database file.
     */
    public function __construct($databaseFile = '')
    {
        if ($databaseFile === '') {
            $databaseFile = '../' . $_ENV['DB_PATH'];
        }
        $this->path = $databaseFile;

        try {
            $this->db = new PDO("sqlite:$databaseFile");
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            exit('Failed to connect to the database: ' . $e->getMessage());
        }
    }

    /**
     * Executes an SQL query on the database.
     *
     * @param string $query The SQL query to execute.
     * @return PDOStatement|bool The PDOStatement object or false on failure.
     */
    public function query($query)
    {
        return $this->db->query($query);
    }

    /**
     * Creates a new table in the database.
     *
     * @param string $tableName The name of the table to create.
     * @param string $columns The column definitions for the table.
     */
    public function createTable($tableName, $columns)
    {
        $query = "CREATE TABLE $tableName ($columns)";
        $this->query($query);
        echo "Table '$tableName' created successfully!\n";
    }

    /**
     * Deletes a table from the database.
     *
     * @param string $tableName The name of the table to delete.
     */
    public function deleteTable($tableName)
    {
        $query = "DROP TABLE IF EXISTS $tableName";
        $this->query($query);
        echo "Table '$tableName' deleted successfully!\n";
    }

    /**
     * Performs a search query on a table with optional conditions.
     *
     * @param string $tableName The name of the table to search.
     * @param array $conditions Optional associative array of column-value pairs for the search.
     * @return array An array of rows matching the search conditions.
     */
    public function search($tableName, $conditions = array(), $operator = 'AND')
    {
        $query = "SELECT * FROM $tableName";

        if (!empty($conditions)) {
            $query .= " WHERE ";

            $conditionsSql = array();
            foreach ($conditions as $column => $value) {
                $conditionsSql[] = "$column LIKE :$column";
            }

            $query .= implode(" $operator ", $conditionsSql);
        }

        $statement = $this->db->prepare($query);

        foreach ($conditions as $column => $value) {
            $statement->bindValue(":$column", $value);
        }

        $statement->execute();

        $rows = $statement->fetchAll(PDO::FETCH_OBJ);

        return $rows;
    }


    /**
     * Retrieves a row from a table by its ID.
     *
     * @param string $tableName The name of the table.
     * @param int $id The ID of the row to retrieve.
     * @return mixed The row array or false if not found.
     */
    public function get($tableName, $id)
    {
        $query = "SELECT * FROM $tableName WHERE id = :id";
        $statement = $this->db->prepare($query);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return $row;
    }

    /**
     * Inserts a new row into a table.
     *
     * @param string $tableName The name of the table.
     * @param array $data An associative array of column-value pairs.
     * @return int The ID of the newly inserted row.
     */
    public function insertRow($tableName, $data)
    {
        $keys = array_keys($data);
        $columns = implode(', ', $keys);

        $placeholders = array();
        foreach ($keys as $key) {
            $placeholders[] = ':' . $key;
        }
        $placeholdersStr = implode(', ', $placeholders);

        $query = "INSERT INTO $tableName ($columns) VALUES ($placeholdersStr)";
        $statement = $this->db->prepare($query);

        foreach ($data as $key => $value) {
            $statement->bindValue(':' . $key, $value);
        }

        $statement->execute();

        $lastInsertId = $this->db->lastInsertId();

        return $lastInsertId;
    }

    /**
     * Updates a row in a table.
     *
     * @param string $tableName The name of the table.
     * @param int $id The ID of the row to update.
     * @param array $data An associative array of column-value pairs to update.
     */
    public function updateRow($tableName, $id, $data)
    {
        $updates = array();

        foreach ($data as $key => $value) {
            $updates[] = "$key = :$key";
        }

        $updatesStr = implode(', ', $updates);

        $query = "UPDATE $tableName SET $updatesStr WHERE id = :id";
        $statement = $this->db->prepare($query);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);

        foreach ($data as $key => $value) {
            $statement->bindValue(':' . $key, $value);
        }

        $statement->execute();

        echo "Row updated successfully!\n";
    }

    /**
     * Deletes a row from a table.
     *
     * @param string $tableName The name of the table.
     * @param int $id The ID of the row to delete.
     */
    public function deleteRow($tableName, $id)
    {
        $query = "DELETE FROM $tableName WHERE id = :id";
        $statement = $this->db->prepare($query);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        echo "Row deleted successfully!\n";
    }

    /**
     * Parses a migration array and generates the corresponding SQL statement.
     *
     * @param array $migration The migration array.
     * @return string The SQL statement for creating a table based on the migration.
     */
    public function parseMigration($migration)
    {
        $tableName = $migration['name'];
        $columns = $migration['columns'];

        $sql = "CREATE TABLE $tableName (";

        foreach ($columns as $columnName => $columnOptions) {
            $sql .= "$columnName ";

            if (is_array($columnOptions)) {
                $columnType = array_shift($columnOptions);
                $sql .= $columnType;

                foreach ($columnOptions as $option) {
                    $sql .= " $option";
                }
            } else {
                $sql .= $columnOptions;
            }

            $sql .= ", ";
        }

        $sql = rtrim($sql, ", "); // Remove trailing comma and space
        $sql .= ");";

        return $sql;
    }

    /**
     * Closes the database connection.
     */
    public function close()
    {
        $this->db = null;
    }

    /**
     * Gets the underlying PDO database connection.
     *
     * @return PDO The PDO database connection.
     */
    public function getConnection()
    {
        return $this->db;
    }

    /**
     * Magic getter method to access private properties.
     *
     * @param string $name The name of the property to get.
     * @return mixed The value of the property.
     */
    public function __get($name)
    {
        return $this->$name;
    }
}