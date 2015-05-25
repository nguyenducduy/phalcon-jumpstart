<?php
namespace Model;

use Phalcon\DI\FactoryDefault as DI;
use Fly\BaseModel as FlyModel;

/**
 * Post
 *
 * Represents a Post
 *
 * @Source('fly_post');
 */
class Post extends FlyModel
{
    /**
    * @Column(type="integer", nullable=true, column="u_id")
    */
    public $uid;

    /**
    * @Column(type="integer", nullable=true, column="pc_id")
    */
    public $pcid;

    /**
    * @Primary
    * @Identity
    * @Column(type="integer", nullable=false, column="p_id")
    */
    public $id;

    /**
    * @Column(type="string", nullable=true, column="p_title")
    */
    public $title;

    /**
    * @Column(type="string", nullable=true, column="p_summary")
    */
    public $summary;

    /**
    * @Column(type="string", nullable=true, column="p_content")
    */
    public $content;

    /**
    * @Column(type="string", nullable=true, column="p_slug")
    */
    public $slug;

    /**
    * @Column(type="string", nullable=true, column="p_tags")
    */
    public $tags;

    /**
    * @Column(type="string", nullable=true, column="p_cover")
    */
    public $cover;

    /**
    * @Column(type="integer", nullable=true, column="p_status")
    */
    public $status;

    /**
    * @Column(type="integer", nullable=true, column="p_type")
    */
    public $type;

    /**
    * @Column(type="integer", nullable=true, column="p_displayorder")
    */
    public $displayorder;

    /**
    * @Column(type="integer", nullable=true, column="p_comment_count")
    */
    public $commentcount;

    /**
    * @Column(type="integer", nullable=true, column="p_datecreated")
    */
    public $datecreated;

    /**
    * @Column(type="integer", nullable=true, column="p_datemodified")
    */
    public $datemodified;


    const STATUS_ENABLE = 1; 
    const STATUS_DISABLE = 3; 
    const TYPE_TEXT = 1; 
    const TYPE_VIDEO = 3; 

    protected $lang;

    public function initialize()
    {
        parent::initialize();

        $this->cover = DI::getDefault()->get('config')->post['directory'] . date('Y') . '/' . date('m');
        $this->addBehavior(new \Phalcon\Behavior\Imageable([
            'isoverwrite' => false,
            'sanitize' => true,
            'uploadPath' => $this->cover,
        ]));
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
        $this->validate(new \Phalcon\Mvc\Model\Validator\Numericality(
            [
                'field'  => 'uid',
                'message' => $this->lang->get('message_uid_isnum')
            ]
        ));

        $this->validate(new \Phalcon\Mvc\Model\Validator\Numericality(
            [
                'field'  => 'pcid',
                'message' => $this->lang->get('message_pcid_isnum')
            ]
        ));

        $this->validate(new \Phalcon\Mvc\Model\Validator\PresenceOf(
            [
                'field'  => 'title',
                'message' => $this->lang->get('message_title_notempty')
            ]
        ));

        $this->validate(new \Phalcon\Mvc\Model\Validator\Numericality(
            [
                'field'  => 'status',
                'message' => $this->lang->get('message_status_isnum')
            ]
        ));

        $this->validate(new \Phalcon\Mvc\Model\Validator\Numericality(
            [
                'field'  => 'type',
                'message' => $this->lang->get('message_type_isnum')
            ]
        ));


        return $this->validationHasFailed() != true;
    }

    /**
     * Create Paginator Object for Post Listing
     *
     * @param  [array] $formData    Store condition, order, select column to prepare for query
     * @param  [int] $limit         Record per page
     * @param  [int] $offset        Current Page
     * @return [object] $paginator  Phalcon Paginator Builder Object
     */
    public static function getPostList($formData, $limit, $offset)
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

    public function getTypeName()
    {
        $name = '';

        switch ($this->type) {
            case self::TYPE_TEXT:
                $name = $this->lang->get("label_type_text");
                break;
            case self::TYPE_VIDEO:
                $name = $this->lang->get("label_type_video");
                break;

        }

        return $name;
    }

    public static function getTypeList()
    {
        $lang = DI::getDefault()->get('lang');

        return $data = [
            [
                "name" => $lang->get("label_type_text"),
                "value" => self::TYPE_TEXT
            ],
            [
                "name" => $lang->get("label_type_video"),
                "value" => self::TYPE_VIDEO
            ],

        ];
    }

    public static function getTypeListArray()
    {
        return [
            self::TYPE_TEXT,
            self::TYPE_VIDEO,

        ];
    }


}
