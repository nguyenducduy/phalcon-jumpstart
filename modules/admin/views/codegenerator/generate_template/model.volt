<?php
namespace Model;

use Phalcon\DI\FactoryDefault as DI;
use Fly\BaseModel as FlyModel;

/**
 * {{MODEL_NAME}}
 *
 * Represents a {{MODEL_NAME}}
 *
 * @Source('{{TABLE_NAME}}');
 */
class {{MODEL_NAME}} extends {{BASE_MODEL}}
{
{{COLUMN_DEFINED}}
{{CONSTANT_DEFINED}}
    protected $lang;

    public function initialize()
    {
        parent::initialize();

{{UPLOAD_SECTION}}
    }

    public function onConstruct()
    {
        $this->lang = DI::getDefault()->get('lang');
    }

    public function beforeCreate()
    {

    }

    public function beforeUpdate()
    {

    }

    public function validation()
    {
{{VALIDATION_CONTENT}}
    }

    /**
     * Create Paginator Object for {{MODEL_NAME}} Listing
     *
     * @param  [array] $formData    Store condition, order, select column to prepare for query
     * @param  [int] $limit         Record per page
     * @param  [int] $offset        Current Page
     * @return [object] $paginator  Phalcon Paginator Builder Object
     */
    public static function get{{MODEL_NAME}}List($formData, $limit, $offset)
    {
        $modelName = get_class();
        $whereString = '';
        $bindParams = [];
        $bindTypeParams = [];

        if (is_array($formData['conditions'])) {
            if (isset($formData['conditions']['keyword'])
                && strlen($formData['conditions']['keyword']) > 0
                && isset($formData['conditions']['searchKeywordIn'])
                && count($formData['conditions']['searchKeywordIn']) > 0) {
                // Search keyword
                $searchKeyword = $formData['conditions']['keyword'];
                $searchKeywordIn = $formData['conditions']['searchKeywordIn'];

                $whereString .= $whereString != '' ? ' OR ' : ' (';

                $sp = '';
                foreach ($searchKeywordIn as $searchIn) {
                    $sp .= ($sp != '' ? ' OR ' : '') . $searchIn . ' LIKE :searchKeyword:';
                }

                $whereString .= $sp . ')';
                $bindParams['searchKeyword'] = '%' . $searchKeyword . '%';
            }

            // Optional Filter by tags
            if (count($formData['conditions']['filterBy']) > 0) {
                $filterby = $formData['conditions']['filterBy'];

                foreach ($filterby as $k => $v) {
                    if ($v > 0) {
                        $whereString .= ($whereString != '' ? ' AND ' : '') . $k . ' = :' . $k . ':';
                        $bindParams[$k] = $v;

                        switch (gettype($v)) {
                            case 'string':
                                $bindTypeParams[$k] =  \PDO::PARAM_STR;
                                break;

                            default:
                                $bindTypeParams[$k] = \PDO::PARAM_INT;
                                break;
                        }
                    }
                }
            }

            if (strlen($whereString) > 0 && count($bindParams) > 0) {
                $formData['conditions'] = [
                    [
                        $whereString,
                        $bindParams,
                        $bindTypeParams
                    ]
                ];
            } else {
                $formData['conditions'] = '';
            }
        }

        $params = [
            'models' => $modelName,
            'columns' => $formData['columns'],
            'conditions' => $formData['conditions'],
            'order' => [$modelName . '.' . $formData['orderBy'] .' '. $formData['orderType'] .'']
        ];

        return parent::runPaginate($params, $limit, $offset);
    }

    /**
     * Extends Phalcon findFirst to handle cache
     */
    public static function findFirst($parameters=null)
    {
        $data = parent::findFirst($parameters);
        return $data;
    }

{{CONSTANT_FUNCTION}}
}
