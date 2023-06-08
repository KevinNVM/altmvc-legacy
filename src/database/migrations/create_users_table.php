<?php

/**
 * Define the migration table configuration for the 'users' table.
 * 
 * This configuration array specifies the table name and the columns with their respective data types and constraints.
 * The migration table follows the SQLite rules for table creation.
 * 
 * Note:
 * - Char length is currently not supported.
 * - Columns cannot have whitespaces.
 * - Timestamps type is not supported.
 * 
 * @return array An array configuration for defining the 'users' migration table.
 */

return array(
    'name' => 'users',
    'columns' => array(
        'id' => array('INTEGER', 'PRIMARY KEY', 'NOT NULL'),
        'username' => array('TEXT', 'NOT NULL'),
        'email' => array('TEXT', 'NULLABLE'),
        'password' => 'TEXT',
        'created_at' => 'TEXT',
    )
);