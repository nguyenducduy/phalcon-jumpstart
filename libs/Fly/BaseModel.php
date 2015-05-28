<?php
/**
 * \Fly\BaseModel
 * BaseModel.php
 *
 * Core Model class
 *
 * @author      Nguyen Duy <nguyenducduy.it@gmail.com>
 * @since       2014-12-19
 * @category    Fly
 *
 */

namespace Fly;

use \Phalcon\Mvc\Model\Query\Builder as PhBuilder;
use \Phalcon\Paginator\Adapter\QueryBuilder as PhQueryBuilder;

class BaseModel extends \Phalcon\Mvc\Model
{
    public function initialize()
    {

    }

    public static function runPaginate($params, $limit, $offset)
    {
        $builder = new PhBuilder($params);

        $paginator = new PhQueryBuilder([
            'builder' => $builder,
            'limit' => $limit,
            'page' => $offset
        ]);

        return $paginator->getPaginate();
    }

    /**
     * Returns the DI container
     */
    public function getDI()
    {
        return \Phalcon\DI\FactoryDefault::getDefault();
    }
}
