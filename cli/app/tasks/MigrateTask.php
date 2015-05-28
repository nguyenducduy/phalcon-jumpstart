<?php

use Foolz\Connection;
use Foolz\Helper;
use Foolz\SphinxQL;
use Phalcon\CLI\Task as PhTask;

class MigrateTask extends PhTask
{
    private $migrations = null;

    public function mainAction()
    {
        echo "Migrate - Options\n";
        echo "rebuild - rebuilds the database\n";
        echo "load - loads sample data \n";
        echo "indexes <index-name-1> <index-name-2> <...>- loads Realtime Index to Sphinx \n";
    }

    /**
     * Drops all tables from the database and rebuilds it
     */
    public function rebuildAction()
    {
        $db = $this->di['db'];

        if ($this->loadMigrations()) {

            /**
             * Instantiate the migration
             */
            $migration = new \FlyCli\Migration($db);

            /**
             * Load the files from it
             */
            echo "Starting migration rebuild task\n";
            echo "Clearing the database\n";
            $migration->dropTables();
            echo "\n";

            foreach ($this->migrations as $key => $data) {

                echo "Processing migration {$key} \n";

                $migration->load($data);

                echo $migration->getDescription();

                echo "Running migration (up) # {$key}";
                $migration->up();
                echo "\n";

                echo "Updating fly_migration table # {$key} \n";
                $migration->updateMigration($key);

            }

            echo "Performed migrations. Terminating. \n Finished. \n";

        } else {

            echo "Error in loading migrations \n";

        }
    }

    /**
     * Loads data from the relevant file
     */
    public function loadAction()
    {
        $db = $this->di['db'];

        if ($this->loadData()) {

            /**
             * Instantiate the migration
             */
            $migration = new \FlyCli\Migration($db);

            /**
             * Load the files from it
             */
            echo "Starting migration load task\n";

            foreach ($this->migrations as $row) {
                foreach ($row as $table => $items) {

                    if ($migration->tableExists($table)) {

                        echo "\nTruncating table {$table}" . PHP_EOL;
                        $migration->emptyTable($table);
                        echo "\n";

                        echo "Adding data to table {$table}" . PHP_EOL;
                        foreach ($items as $row) {

                            $fields = [];
                            $values = [];

                            foreach ($row as $field => $value) {
                                $fields[] = $field;
                                $values[] = $value === "null" ? NULL : addslashes($value);
                            }

                            $fieldList = implode(',', $fields);
                            $valueList = implode('","', $values);

                            $migration->initStatements();
                            $migration->insertStatement($table, $fieldList, $valueList);
                            $migration->execute();
                        }
                        echo "\n";
                    } else {
                        echo "Table {$table} does not exist. Ignoring... \n";
                    }
                }
            }

            echo "Loaded data. Terminating. \n";

        } else {
            echo "Error in loading data \n";
        }
    }

    public function indexesAction(array $params)
    {
        $recordPerPage = 100;

        //Inititlizes SphinxQL Connection
        $conn = new Connection();
        $conn->setParams(['host' => $this->config->app_sphinx->host, 'port' => $this->config->app_sphinx->realtime_port]);

        $totalFromIndex = count($params);

        $formData['columns'] = '*';
        $formData['conditions'] = '';
        $formData['orderBy'] = 'id';
        $formData['orderType'] = 'ASC';

        /**
         * Loading data from fly_manga table to sphinx indexes
         * @var [type]
         */
        if (in_array('fly_manga', $params)) {
            echo "Flush content of index fly_manga. \n";
            Helper::create($conn)->flushRtIndex('fly_manga')->execute();

            $totalManga = \Fly\Models\Manga::count();
            $totalMangaPage = ceil($totalManga / $recordPerPage);

            echo "Loading {$totalManga} Manga to Sphinx Indexes. \n";

            $countManga = 0;
            for ($i = 1; $i <= $totalMangaPage; $i++) {
                $myMangaList = \Fly\Models\Manga::getMangaList($formData, $recordPerPage, $i);

                foreach ($myMangaList->items as $item) {
                    //Insert to SphinxQL index
                    $sq = SphinxQL::create($conn)->insert()->into('fly_manga');
                    $sq->value('id', $item->id)
                        ->value('m_link', $item->link)
                        ->value('m_isupdateinfo', $item->isupdateinfo)
                        ->value('m_title', $item->title)
                        ->value('m_status', $item->status);

                    $countManga += $sq->execute();
                    echo '.';
                }
            }

            echo "\n Total {$countManga} Manga inserted to Sphinx Indexes. \n";
        }

        /**
         * Loading data from fly_chapter table to sphinx indexes
         * @var [type]
         */
        if (in_array('fly_chapter', $params)) {
            echo "Flush content of index fly_chapter \n";
            Helper::create($conn)->flushRtIndex('fly_chapter')->execute();

            $totalChapter = \Fly\Models\Chapter::count();
            $totalChapterPage = ceil($totalChapter / $recordPerPage);

            echo "Loading {$totalChapter} Chapter to Sphinx Indexes. \n";

            $countChapter = 0;
            for ($i = 1; $i <= $totalChapterPage; $i++) {
                $myChapterList = \Fly\Models\Chapter::getChapterList($formData, $recordPerPage, $i);

                foreach ($myChapterList->items as $item) {
                    //Insert to SphinxQL index
                    $sq = SphinxQL::create($conn)->insert()->into('fly_chapter');
                    $sq->value('id', $item->id)
                        ->value('c_link', $item->link)
                        ->value('c_title', $item->title)
                        ->value('c_status', $item->status);

                    $countChapter += $sq->execute();
                    echo '.';
                }
            }

            echo "\n Total {$countChapter} Chapter inserted to Sphinx Indexes. \n";
        }

        /**
         * Loading data from fly_chapter_content table to sphinx indexes
         * @var [type]
         */
        if (in_array('fly_chapter_content', $params)) {
            echo "Flush content of index fly_chapter_content \n";
            Helper::create($conn)->flushRtIndex('fly_chapter_content')->execute();

            $totalChapterContent = \Fly\Models\ChapterContent::count();
            $totalChapterContentPage = ceil($totalChapterContent / $recordPerPage);

            echo "Loading {$totalChapterContent} Chapter Content to Sphinx Indexes. \n";

            $countChapterContent = 0;
            for ($i = 1; $i <= $totalChapterContentPage; $i++) {
                $myChapterContentList = \Fly\Models\ChapterContent::getChapterContentList($formData, $recordPerPage, $i);

                foreach ($myChapterContentList->items as $item) {
                    //Insert to SphinxQL index
                    $sq = SphinxQL::create($conn)->insert()->into('fly_chapter_content');
                    $sq->value('id', $item->id)
                        ->value('cc_content', md5($item->link));

                    $countChapterContent += $sq->execute();
                    echo '.';
                }
            }

            echo "\n Total {$countChapterContent} Chapter Content inserted to Sphinx Indexes. \n";
        }
    }

    private function loadMigrations()
    {
        $fileName  = JSON_STRUCTURE;

        if (file_exists($fileName)) {

            $json = file_get_contents($fileName);
            $data = json_decode($json, true);

            $this->migrations = $data;

            return true;
        } else {
            return false;
        }
    }

    private function loadData()
    {
        $fileName  = JSON_DATA;

        if (file_exists($fileName)) {

            $json = file_get_contents($fileName);
            $data = json_decode($json, true);

            $this->migrations = $data;

            return true;
        } else {
            return false;
        }
    }
}
