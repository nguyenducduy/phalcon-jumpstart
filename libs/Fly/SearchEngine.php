<?php
namespace Fly;

use Fly\Sphinx\SphinxClient;
use Phalcon\DI\FactoryDefault as DI;

class SearchEngine
{
    public $indexedTables = ['manga'];
    public $searchTables = [];
    public $searcher = null;
    protected $config;

    public function __construct()
    {
        $this->config = DI::getDefault()->get('config');
        $this->searcher = new SphinxClient();
        $this->searcher->SetServer($this->config->app_sphinx->host, $this->config->app_sphinx->port);
        $this->searcher->SetConnectTimeout(1);
        $this->searcher->SetArrayResult(true);
        $this->searcher->SetMatchMode(SPH_MATCH_EXTENDED2);
        $this->searcher->SetRankingMode(SPH_RANK_PROXIMITY_BM25);
    }

    public function addtable($tablename)
    {
        if (in_array($tablename, $this->indexedTables) && !in_array($tablename, $this->searchTables)) {
            $this->searchTables[] = $tablename;

            return true;
        } else {
            return false;
        }
    }

    public function search($keyword)
    {
        $output = [];

        //query tu index
        foreach ($this->searchTables as $tablename) {
            $this->searcher->addQuery($this->searcher->EscapeString($keyword), "$tablename");
        }

        $result = $this->searcher->runQueries();


        if (empty($result[0]['error'])) {
            //lay gia tri tra ve theo index
            $indexCount = count($result);

            for ($i = 0; $i < $indexCount; $i++) {
                if ($result[$i]['total_found'] > 0) {
                    $arrayId = [];

                    for ($k = 0; $k < count($result[$i]['matches']); $k++) {
                        $arrayId[] = $result[$i]['matches'][$k];
                    }

                    $arrayId['result_found'] = $result[$i]['total_found'];
                    $output[$this->searchTables[$i]] = $arrayId;
                }
            }
        } else {
            $output = $result[0]['error'];
        }

        return $output;
    }

}
