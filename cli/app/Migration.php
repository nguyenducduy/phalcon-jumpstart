<?php

namespace FlyCli;

class Migration
{
    protected $prefix      = '';
    protected $table       = '';
    protected $description = '';
    protected $comment     = '';
    protected $useCommon   = false;
    protected $fields      = [];
    protected $indexes     = [];

    private $database   = null;
    private $statements = [];
    private $data       = [];

    public function __construct($database)
    {
        $this->init($database);
        $this->initStatements();
    }

    public function up($return = false)
    {
        $this->initStatements();
        $this->createTable();

        if (!$return) {
            $this->createData();
            $this->execute();
        } else {
            return $this->statements;
        }
    }

    public function down()
    {
        $this->dropTable();
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getTable()
    {
        return $this->table;
    }

    public function init($database)
    {
        $this->database    = $database;
        $this->prefix      = '';
        $this->table       = '';
        $this->description = '';
        $this->comment     = '';
        $this->useCommon   = false;
        $this->fields      = [];
        $this->indexes     = [];
        $this->data        = [];
    }

    public function dropTable($table = '')
    {
        $toDrop = ($table) ? $table : $this->table;

        $sql = 'DROP TABLE IF EXISTS ' . $toDrop;
        $this->execute([$sql]);
    }

    public function dropTables()
    {
        $tables = $this->listTables();

        foreach ($tables as $table) {
            $this->dropTable($table);
        }
    }

    public function emptyTable($table = '')
    {
        $toDrop = ($table) ? $table : $this->table;

        $sql = 'TRUNCATE TABLE ' . $toDrop;
        $this->execute([$sql]);
    }

    public function emptyTables()
    {
        $tables = $this->listTables();

        foreach ($tables as $table) {
            $this->emptyTable($table);
        }
    }

    public function createTable()
    {
        /**
         * Check the engine. If this is the test suite make all tables
         * use the memory engine
         */
        $engine   = 'MyISAM';
        $template = 'CREATE TABLE %s (%s) ENGINE=%s '
                  . 'DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci '
                  . "COMMENT='%s' ";
        $data     = [];

        // Common fields included
        if ($this->useCommon) {
            $commonFields = $this->getCommonFields();
            $allFields    = array_merge($this->fields, $commonFields);

            $commonIndexes = $this->getCommonFieldsIndexes();
            $allIndexes    = array_merge($this->indexes, $commonIndexes);
        } else {
            $allFields  = $this->fields;
            $allIndexes = $this->indexes;
        }

        if (count($allFields) > 0) {
            $data[] = implode(',', $allFields);
        }

        if (count($allIndexes) > 0) {
            $data[] = implode(',', $allIndexes);
        }

        // Get the create SQL template
        $sql = sprintf(
            $template,
            $this->table,
            implode(',', $data),
            $engine,
            $this->comment
        );

        $this->addStatements($sql);
    }

    public function createData()
    {
        foreach ($this->data as $sql) {
            $this->addStatements($sql);
        }
    }

    public function load($item)
    {
        $this->init($this->database);

        /**
         * Check to see if everything is OK
         */

        if (!is_array($item) ||
            !isset($item['prefix']) ||
            !isset($item['table']) ||
            !isset($item['description']) ||
            !isset($item['comment']) ||
            !isset($item['common']) ||
            !isset($item['fields']) ||
            !isset($item['indexes']) ||
            !isset($item['data'])) {
            return false;
        }

        $this->prefix      = $item['prefix'];
        $this->table       = $item['table'];
        $this->description = $item['description'];
        $this->comment     = $item['comment'];
        $this->useCommon   = $item['common'];

        $fields = $item['fields'];
        foreach ($fields as $field) {
            $this->fields[] = str_replace("[prefix]", $this->prefix, $field['sql']);
        }

        $indexes = $item['indexes'];
        foreach ($indexes as $index) {
            $this->indexes[] = str_replace("[prefix]", $this->prefix, $index);
        }

        $statements = $item['data'];
        foreach ($statements as $statement) {
            $this->data[] = $statement;
        }

        return true;
    }

    public function tableExists($table)
    {
        try {

            echo "Checking if the " . $table . " exists \n";

            $tables = $this->listTables();

            return in_array($table, $tables);

        } catch (\Exception $e) {

            echo $e->getMessage();
            echo $e->getTraceAsString();

            return false;
        }
    }

    public function listTables()
    {
        try {
            $tables = [];
            $sql    = "SHOW TABLES";
            $result = $this->database->fetchAll($sql);

            foreach ($result as $item) {
                $tables[] = $item[0];
            }

            return $tables;
        } catch (\Exception $e) {
            echo $e->getMessage();
            echo $e->getTraceAsString();

            return [];
        }
    }

    public function checkMigration()
    {
        try {
            $migration = 1;
            $found     = $this->tableExists('fly_migration');

            if ($found) {
                // Find the existing migration and start from there
                $sql       = "SELECT mig_version FROM fly_migration";
                $result    = $this->database->fetchAll($sql);

                if (0 < count($result)) {
                    $migration = $result[0][0];
                }
            }

            return $migration;
        } catch (\Exception $e) {
            echo $e->getMessage();
            echo $e->getTraceAsString();
        }
    }

    public function updateMigration($migration)
    {
        echo "Updating the fly_migration table with {$migration} \n";

        $migration = intval($migration);
        $sql       = "UPDATE fly_migration SET mig_version = {$migration}";
        $this->database->execute($sql);
    }

    public function initStatements()
    {
        $this->statements = [];
    }

    public function addStatements($statements)
    {
        if (is_array($statements)) {

            foreach ($statements as $sql) {
                $this->statements[] = $sql;
            }
        } else {
            $this->statements[] = $statements;
        }
    }

    public function execute($statements = [])
    {
        try {
            if (is_array($statements) && count($statements) > 0) {
                $runStatements = $statements;
            } else {
                $runStatements = $this->statements;
            }

            foreach ($runStatements as $sql) {
                $this->database->execute($sql);
                echo ".";
            }

        } catch (\Exception $e) {

            $error = $e->getMessage() . "\n"
                . $e->getTraceAsString() . "\n"
                . "SQL: {$sql}\n";
            throw new \Exception($error);
        }
    }

    public function insertStatement($table, $fields, $values)
    {
        $sql = "INSERT INTO {$table} ({$fields}) VALUES (\"{$values}\")";
        $this->addStatements($sql);

        return $sql;
    }

    private function getCommonFields()
    {
        $statements[] = "{$this->prefix}_datecreated int(10) DEFAULT '0'";
        $statements[] = "{$this->prefix}_datemodified int(10) DEFAULT '0'";

        return $statements;
    }

    private function getCommonFieldsIndexes()
    {
        $statements[] = "KEY {$this->prefix}_datecreated ({$this->prefix}_datecreated)";
        $statements[] = "KEY {$this->prefix}_datemodified ({$this->prefix}_datemodified)";

        return $statements;
    }
}
