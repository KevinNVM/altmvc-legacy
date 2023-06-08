<?php

/**
 * Seeder file for populating the 'users' table with sample data.
 *
 * This seeder file returns an array configuration that specifies the table name and
 * an array of rows representing user data. 
 *
 * @return array An array configuration for populating the 'users' table with sample data.
 */


return array(
    'table' => 'users',
    'rows' => array(
        array(
            'username' => 'Jan Co',
            'email' => 'jan.co@example.com',
            'password' => 'password123',
            'created_at' => now()
        ),
        array(
            'username' => 'Cane Toad',
            'email' => 'canejane@example.com',
            'password' => 'password123',
            'created_at' => now()
        ),
    ),
);