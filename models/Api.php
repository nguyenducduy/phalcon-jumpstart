<?php
namespace Model;

use \Fly\BaseModel as FlyModel;

/**
 * Api
 *
 * Represents the api
 *
 * @Source('fly_api');
 */
class Api extends FlyModel
{
    /**
     * @Primary
     * @Identity
     * @Column(type="integer", nullable=false, column="a_client_id")
     */
    public $client_id;

    /**
     * @Column(type="string", nullable=false, column="a_public_id")
     */
    public $public_id;

    /**
     * @Column(type="string", nullable=false, column="a_private_key")
     */
    public $private_key;

    /**
     * @Column(type="string", nullable=false, column="a_status")
     * @var [type]
     */
    public $status;

    /**
     * @Column(type="integer", nullable=true, column="a_isdeleted")
     */
    public $isdeleted;

    /**
     * @Column(type="integer", nullable=true, column="a_datecreated")
     */
    public $datecreated;

    /**
     * @Column(type="integer", nullable=true, column="a_datemodified")
     */
    public $datemodified;

    public function initialize()
    {
        parent::initialize();
    }

}
