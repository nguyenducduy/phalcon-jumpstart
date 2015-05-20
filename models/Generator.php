<?php
/**
 * \Model\Generator.php
 * Generator.php
 *
 * Generator Model
 *
 * @author      Nguyen Duy <nguyenducduy.it@gmail.com>
 * @since       2014-04-14
 * @category    Model
 *
 */

namespace Model;

use Fly\BaseModel as FlyModel;

class Generator extends FlyModel
{
    public static function getTypeName($value)
    {
        $myConstList = [];

        $refl = new \ReflectionClass('\Phalcon\Db\Column');
        $constList = $refl->getConstants();

        foreach ($constList as $constName => $constValue) {
            if (preg_match('/^TYPE_([A-Z])+$/', $constName)) {
                $myConstList[$constValue] = (string) $constName;
            }
        }

        return $myConstList[$value];
    }
}
