<?php
namespace Model;

use Phalcon\DI\FactoryDefault as DI;
use Fly\BaseModel as FlyModel;

/**
 * Logs
 *
 * Represents a Logs
 *
 * @Source('fly_logs');
 */
class Logs extends FlyModel
{
    /**
    * @Primary
    * @Identity
    * @Column(type="integer", nullable=false, column="l_id")
    */
    public $id;

    /**
    * @Column(type="string", nullable=true, column="l_name")
    */
    public $name;

    /**
    * @Column(type="integer", nullable=true, column="l_type")
    */
    public $type;

    /**
    * @Column(type="string", nullable=true, column="l_content")
    */
    public $content;

    /**
    * @Column(type="integer", nullable=true, column="l_datecreated")
    */
    public $datecreated;



    protected $lang;

    public function initialize()
    {
        parent::initialize();


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

    public function beforeValidation()
    {

    }

    const LEVEL_EMERGENCY = 0;
    const LEVEL_CRITICAL = 1;
    const LEVEL_ALERT = 2;
    const LEVEL_ERROR = 3;
    const LEVEL_WARNING = 4;
    const LEVEL_NOTICE = 5;
    const LEVEL_INFO = 6;
    const LEVEL_DEBUG = 7;

    /**
     * Create Paginator Object for Logs Listing
     *
     * @param  [array] $formData    Store condition, order, select column to prepare for query
     * @param  [int] $limit         Record per page
     * @param  [int] $offset        Current Page
     * @return [object] $paginator  Phalcon Paginator Builder Object
     */
    public static function getLogsList($formData, $limit, $offset)
    {
        $modelName = get_class();
        $whereString = '';
        $bindParams = [];

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
                    }
                }
            }

            if (strlen($whereString) > 0 && count($bindParams) > 0) {
                $formData['conditions'] = [
                    [
                        $whereString,
                        $bindParams
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

    public function getTypeName()
    {
        $name = '';

        switch ($this->type) {
            case self::LEVEL_EMERGENCY:
                $name = 'Emergency';
                break;
            case self::LEVEL_CRITICAL:
                $name = 'Critical';
                break;
            case self::LEVEL_ALERT:
                $name = 'Alert';
                break;
            case self::LEVEL_ERROR:
                $name = 'Error';
                break;
            case self::LEVEL_WARNING:
                $name = 'Warning';
                break;
            case self::LEVEL_NOTICE:
                $name = 'Notice';
                break;
            case self::LEVEL_INFO:
                $name = 'Info';
                break;
            case self::LEVEL_DEBUG:
                $name = 'Debug';
                break;

        }

        return $name;
    }

    public static function getTypeList()
    {
        $output = array();

        $output[self::LEVEL_EMERGENCY] = 'Emergency';
        $output[self::LEVEL_CRITICAL] = 'Critical';
        $output[self::LEVEL_ALERT] = 'Alert';
        $output[self::LEVEL_ERROR] = 'Error';
        $output[self::LEVEL_WARNING] = 'Warning';
        $output[self::LEVEL_NOTICE] = 'Notice';
        $output[self::LEVEL_INFO] = 'Info';
        $output[self::LEVEL_DEBUG] = 'Debug';

        return $output;
    }

    public function getTypeLabel()
    {
        $label = '';

        switch ($this->type) {
            case self::LEVEL_EMERGENCY:
                $label = 'danger';
                break;
            case self::LEVEL_CRITICAL:
                $label = 'danger';
                break;
            case self::LEVEL_ALERT:
                $label = 'warning';
                break;
            case self::LEVEL_ERROR:
                $label = 'danger';
                break;
            case self::LEVEL_WARNING:
                $label = 'warning';
                break;
            case self::LEVEL_NOTICE:
                $label = 'primary';
                break;
            case self::LEVEL_INFO:
                $label = 'info';
                break;
            case self::LEVEL_DEBUG:
                $label = 'default';
                break;
        }

        return $label;
    }
}
