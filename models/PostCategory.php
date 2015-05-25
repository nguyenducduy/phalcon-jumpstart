<?php
namespace Model;

use Phalcon\DI\FactoryDefault as DI;
use Fly\BaseModel as FlyModel;

/**
 * PostCategory
 *
 * Represents a PostCategory
 *
 * @Source('fly_post_category');
 */
class PostCategory extends FlyModel
{
    /**
    * @Primary
    * @Identity
    * @Column(type="integer", nullable=false, column="pc_id")
    */
    public $id;

    /**
    * @Column(type="string", nullable=true, column="pc_name")
    */
    public $name;

    /**
    * @Column(type="string", nullable=true, column="pc_description")
    */
    public $description;

    /**
    * @Column(type="string", nullable=true, column="pc_slug")
    */
    public $slug;

    /**
    * @Column(type="integer", nullable=true, column="pc_parent")
    */
    public $parent;

    /**
    * @Column(type="integer", nullable=true, column="pc_status")
    */
    public $status;

    /**
    * @Column(type="integer", nullable=true, column="pc_displayorder")
    */
    public $displayorder;

    /**
    * @Column(type="integer", nullable=true, column="pc_post_count")
    */
    public $postcount;

    /**
    * @Column(type="integer", nullable=true, column="pc_datecreated")
    */
    public $datecreated;

    /**
    * @Column(type="integer", nullable=true, column="pc_datemodified")
    */
    public $datemodified;


    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 3;

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
        $this->datecreated = time();
    }

    public function beforeUpdate()
    {
        $this->datemodified = time();
    }

    public function validation()
    {
        $this->validate(new \Phalcon\Mvc\Model\Validator\PresenceOf(
            [
                'field'  => 'name',
                'message' => $this->lang->get('message_name_notempty')
            ]
        ));

        $this->validate(new \Phalcon\Mvc\Model\Validator\Numericality(
            [
                'field'  => 'status',
                'message' => $this->lang->get('message_status_isnum')
            ]
        ));


        return $this->validationHasFailed() != true;
    }

    /**
     * Create Paginator Object for PostCategory Listing
     *
     * @param  [array] $formData    Store condition, order, select column to prepare for query
     * @param  [int] $limit         Record per page
     * @param  [int] $offset        Current Page
     * @return [object] $paginator  Phalcon Paginator Builder Object
     */
    public static function getPostCategoryList($formData, $limit, $offset)
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

    public function getStatusName()
    {
        $name = '';

        switch ($this->status) {
            case self::STATUS_ENABLE:
                $name = $this->lang->get("label_status_enable");
                break;
            case self::STATUS_DISABLE:
                $name = $this->lang->get("label_status_disable");
                break;

        }

        return $name;
    }

    public static function getStatusList()
    {
        $lang = DI::getDefault()->get('lang');

        return $data = [
            [
                "name" => $lang->get("label_status_enable"),
                "value" => self::STATUS_ENABLE
            ],
            [
                "name" => $lang->get("label_status_disable"),
                "value" => self::STATUS_DISABLE
            ],

        ];
    }

    public static function getStatusListArray()
    {
        return [
            self::STATUS_ENABLE,
            self::STATUS_DISABLE,

        ];
    }


}
