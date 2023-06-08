<?php


require 'src/bootstrap/autoload.php';

$env = new Env('env');
$env->load();

function say($str)
{
    echo "\n$str\n";
}

function welcomeText()
{

    $text = "
\033[1mPHP ALTMVC CLI v0.1\033[0m

Usage: php alt [options]

\033[1mOptions:\033[0m

Database
    db:show - Show database information
    db:createFresh - Create a fresh new SQLite Database
    db:migrate - Migrate and seed the Database
Others
    hello - Say 'Hello World'


";

    echo $text;
}


/**
 * Command Functions
 * prefix with cmd_ for option functions
 *
 */

class Commands
{
    public static function dbshow()
    {
        $dbPath = 'db.sqlite';
        $db = new Database($dbPath);

        // Show all tables in the database
        $query = "SELECT name FROM sqlite_master WHERE type='table'";
        $result = $db->query($query);

        echo "\nDatabase Path: `$dbPath`\n";
        echo "Tables in the database:\n";

        while ($row = sqlite_fetch_array($result)) {
            echo '- ' . $row['name'] . "\n";
        }

        echo "\n";

        // Show the structure of each table
        $result = $db->query($query);

        while ($row = sqlite_fetch_array($result)) {
            $table = $row['name'];

            echo "Table: $table\n";

            $structureQuery = "PRAGMA table_info($table)";
            $structureResult = $db->query($structureQuery);

            echo "Column\t\tType\t\tNullable\tPrimary Key\n";

            while ($structureRow = sqlite_fetch_array($structureResult)) {
                echo "{$structureRow['name']}\t\t{$structureRow['type']}\t\t{$structureRow['notnull']}\t\t{$structureRow['pk']}\n";
            }

            echo "\n";
        }

        // Close the database connection
        $db->close();
    }

    public static function dbmigrate()
    {
        $dbPath = $_ENV['DB_PATH'];
        $db = new Database($dbPath);


        $migrationsDir = 'src/database/migrations';

        // Get all migration files in the directory
        $migrationFiles = glob($migrationsDir . '/*.php');

        foreach ($migrationFiles as $migrationFile) {
            // Require the migration file
            $migrationData = require $migrationFile;

            // Run the parseMigration function on the migration
            $sqlCode = $db->parseMigration($migrationData);
            say("Running Query: " . $sqlCode);
            $db->query($sqlCode);
        }

        echo "\nMigration has ended.\n\n";

        $db->close();
    }

    public static function dbfresh()
    {
        echo "Are you sure ? This action cannot be undone! (y/N): \n";
        if (strtolower(trim(fgets(STDIN))) !== 'y') {
            say("User cancelled the action.");
            return false;
        }
        $dbPath = $_ENV['DB_PATH'];


        if (file_exists($dbPath)) {
            echo "Database found! Deleting file...\n";
            unlink($dbPath);
        } else {
            echo "Database not found! Creating a new one...\n";
        }

        if (!file_exists($dbPath)) {
            // Create a new SQLite database file
            $db = sqlite_open($dbPath) or die('Could not create database');
            sqlite_close($db);
            echo 'SQLite database file created successfully.' . "\n";
        } else {
            echo 'SQLite database file already exists.' . "\n";
        }

        echo "Database created at {$dbPath} \n";
    }

    public static function makemigration($dbName)
    {
        $dbName = strtolower($dbName);
        $dbName = preg_replace('/\s+/', '_', $dbName);
        $path = "src/database/migrations";
        $file = fopen("${path}/create_${dbName}_table.php", "w"); // Open the file in write mode

        if ($file) {
            $content = file_get_contents("src/commands/template/migration_template.txt");

            $content = preg_replace('/%%TableName%%/', $dbName, $content);

            fwrite($file, $content); // Write the content to the file
            fclose($file); // Close the file
            say("File created successfully.");
        } else {
            echo "Failed to open the file.";
        }
    }

    public static function makeseeder($dbName)
    {
        $dbName = strtolower($dbName);
        $dbName = preg_replace('/\s+/', '_', $dbName);
        $path = "src/database/seeds";
        $file = fopen("${path}/${dbName}_seeder.php", "w"); // Open the file in write mode

        if ($file) {
            $content = file_get_contents("src/commands/template/seeder_template.txt");

            $content = preg_replace('/%%TableName%%/', $dbName, $content);

            fwrite($file, $content); // Write the content to the file
            fclose($file); // Close the file
            say("File created successfully.");
        } else {
            echo "Failed to open the file.";
        }
    }

    public static function makecontroller($name)
    {
        $name = preg_replace('/\s+/', '_', $name);
        $path = "src/app/Controllers";
        $file = fopen("${path}/${name}.php", "w"); // Open the file in write mode

        if ($file) {
            $content = file_get_contents("src/commands/template/controller_template.txt");

            $content = preg_replace('/%%Name%%/', $name, $content);

            fwrite($file, $content); // Write the content to the file
            fclose($file); // Close the file
            say("File created successfully.");
        } else {
            echo "Failed to open the file.";
        }
    }

    public static function makeviews($name)
    {
        $name = preg_replace('/\s+/', '_', $name);
        $path = "src/app/Views";

        $newFolderPath = $path . '/' . implode('/', array_slice(explode('/', $name), 0, -1));

        // $name argument example: makeviews('users/index')

        // Create the folder if it doesn't exist
        if (!is_dir($newFolderPath)) {
            mkdir($newFolderPath, 0777, true);
        }

        // Create the file path
        $filePath = $path . '/' . $name . '.php';

        $file = fopen($filePath, 'w'); // Open the file in write mode


        if ($file) {
            $content = file_get_contents("src/commands/template/views_template.txt");

            try {

                $response = curl($_ENV['QUOTE_API'] . '/quotes/random?limit=1&tags=inspirational');


                $quote = JSON::parse($response);
                $quote = reset($quote);


                $content = preg_replace('/%%QUOTE%%/', $quote->content ? $quote->content : 'Stay Strong', $content);
                $content = preg_replace('/%%AUTHOR%%/', $quote->author ? $quote->author : 'Me', $content);

            } catch (Exception $e) {
                say("Cannot get quotes :(");
            }


            fwrite($file, $content); // Write the content to the file
            fclose($file); // Close the file
            say("File created successfully.");
        } else {
            say("Failed to open the file.");
        }
    }


    public static function dbseed()
    {
        $dbPath = $_ENV['DB_PATH'];
        $db = new Database($dbPath);

        $seedsDir = 'src/database/seeds';

        // Get all seeding files in the directory
        $seedFiles = glob($seedsDir . '/*.php');

        foreach ($seedFiles as $seedFile) {
            // Require the seeding file
            $seedData = require $seedFile;

            // Seed the data
            Commands::seedData($db, $seedData);
        }

        echo "\nSeeding has ended.\n\n";

        $db->close();
    }

    private static function seedData($db, $seedData)
    {
        $tableName = $seedData['table'];
        $rows = $seedData['rows'];

        foreach ($rows as $row) {
            $columns = implode(', ', array_keys($row));
            $values = implode(', ', array_map(create_function('$value', '
            return is_string($value) ? "\'" . $value . "\'" : $value;
        '), array_values($row)));

            $query = "INSERT INTO $tableName ($columns) VALUES ($values)";
            $db->query($query);
        }
    }

    private static function createDirectory($path)
    {
        if (!is_dir($path)) {
            // Create the directory and missing parent directories recursively
            $parentDir = dirname($path);
            if (!is_dir($parentDir)) {
                self::createDirectory($parentDir);
            }
            mkdir($path, 0777);
        }
    }

}