<?php
/*
  +------------------------------------------------------------------------+
  | Phalcon Framework                                                      |
  +------------------------------------------------------------------------+
  | Copyright (c) 2011-2014 Phalcon Team (http://www.phalconphp.com)       |
  +------------------------------------------------------------------------+
  | This source file is subject to the New BSD License that is bundled     |
  | with this package in the file docs/LICENSE.txt.                        |
  |                                                                        |
  | If you did not receive a copy of the license and are unable to         |
  | obtain it through the world-wide-web, please send an email             |
  | to license@phalconphp.com so we can send you a copy immediately.       |
  +------------------------------------------------------------------------+
  | Authors: Andres Gutierrez <andres@phalconphp.com>                      |
  |          Eduar Carvajal <eduar@phalconphp.com>                         |
  +------------------------------------------------------------------------+
*/
namespace Phalcon\Acl\Adapter;

use Phalcon\Acl;
use Phalcon\Acl\Adapter;
use Phalcon\Acl\AdapterInterface;
use Phalcon\Acl\Exception;
use Phalcon\Acl\Resource;
use Phalcon\Acl\Role;
use Phalcon\Db;

/**
 * Phalcon\Acl\Adapter\Database
 * Manages ACL lists in memory
 */
class Database extends Adapter implements AdapterInterface
{

    /**
     * @var array
     */
    protected $options;

    /**
     * Class constructor.
     *
     * @param  array                  $options
     * @throws \Phalcon\Acl\Exception
     */
    public function __construct(array $options)
    {
        if (!isset($options['db'])) {
            throw new Exception("Parameter 'db' is required");
        }

        if (!isset($options['roles'])) {
            throw new Exception("Parameter 'roles' is required");
        }

        if (!isset($options['resources'])) {
            throw new Exception("Parameter 'resources' is required");
        }

        if (!isset($options['resourcesAccesses'])) {
            throw new Exception("Parameter 'resourcesAccesses' is required");
        }

        if (!isset($options['accessList'])) {
            throw new Exception("Parameter 'accessList' is required");
        }

        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     * Example:
     * <code>$acl->addRole(new Phalcon\Acl\Role('administrator'), 'consultor');</code>
     * <code>$acl->addRole('administrator', 'consultor');</code>
     *
     * @param  \Phalcon\Acl\Role|string $role
     * @param  string                   $accessInherits
     * @return boolean
     */
    public function addRole($role, $accessInherits = null)
    {
        if (!is_object($role)) {
            $role = new Role($role);
        }

        $exists = $this->options['db']->fetchOne(
            'SELECT COUNT(*) FROM ' . $this->options['roles'] . ' WHERE ug_name = ?',
            null,
            [$role->getName()]
        );

        if (!$exists[0]) {

            // $this->options['db']->execute(
            //     'INSERT INTO ' . $this->options['roles'] . ' (
            //         grp_name,
            //         grp_description) VALUES (?, ?)',
            //     array($role->getName(), strlen($role->getDescription()) > 0 ? $role->getDescription : 'Default System Role')
            // );

            $this->options['db']->execute(
                'INSERT INTO ' . $this->options['accessList'] . ' (
                    aal_roles_name,
                    aal_resources_name,
                    aal_access_name,
                    aal_allowed,
                    aal_datecreated) VALUES (?, ?, ?, ?, ?)',
                [$role->getName(), '*', '*', $this->_defaultAccess, time()]
            );
        }

        if ($accessInherits) {
            return $this->addInherit($role->getName(), $accessInherits);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @param  string                 $roleName
     * @param  string                 $roleToInherit
     * @throws \Phalcon\Acl\Exception
     */
    public function addInherit($roleName, $roleToInherit)
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->options['roles'] . ' WHERE ug_name = ?';
        $exists = $this->options['db']->fetchOne($sql, null, [$roleName]);
        if (!$exists[0]) {
            throw new Exception("Role '" . $roleName . "' does not exist in the role list");
        }

        $exists = $this->options['db']->fetchOne(
            'SELECT COUNT(*) FROM ' . $this->options['rolesInherits'] . ' WHERE ari_roles_name = ? AND ari_roles_inherit = ?',
            null,
            [$roleName, $roleToInherit]
        );

        if (!$exists[0]) {
            $this->options['db']->execute(
                'INSERT INTO ' . $this->options['rolesInherits'] . '(
                    ari_roles_name,
                    ari_roles_inherit,
                    ari_datecreated) VALUES (?, ?, ?)',
                [$roleName, $roleToInherit, time()]
            );
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param  string  $roleName
     * @return boolean
     */
    public function isRole($roleName)
    {
        $exists = $this->options['db']->fetchOne(
            'SELECT COUNT(*) FROM ' . $this->options['roles'] . ' WHERE ug_name = ?',
            null,
            [$roleName]
        );

        return (bool) $exists[0];
    }

    /**
     * {@inheritdoc}
     *
     * @param  string  $resourceName
     * @return boolean
     */
    public function isResource($resourceName)
    {
        $exists = $this->options['db']->fetchOne(
            'SELECT COUNT(*) FROM ' . $this->options['resources'] . ' WHERE ar_name = ?',
            null,
            [$resourceName]
        );

        return (bool) $exists[0];
    }

    /**
     * {@inheritdoc}
     * Example:
     * <code>
     * //Add a resource to the the list allowing access to an action
     * $acl->addResource(new Phalcon\Acl\Resource('customers'), 'search');
     * $acl->addResource('customers', 'search');
     * //Add a resource  with an access list
     * $acl->addResource(new Phalcon\Acl\Resource('customers'), array('create', 'search'));
     * $acl->addResource('customers', array('create', 'search'));
     * </code>
     *
     * @param  \Phalcon\Acl\Resource|string $resource
     * @param  array|string                 $accessList
     * @return boolean
     */
    public function addResource($resource, $accessList = null)
    {
        if (!is_object($resource)) {
            $resource = new Resource($resource);
        }

        $exists = $this->options['db']->fetchOne(
            'SELECT COUNT(*) FROM ' . $this->options['resources'] . ' WHERE ar_name = ?',
            null,
            [$resource->getName()]
        );

        if (!$exists[0]) {
            $this->options['db']->execute(
                'INSERT INTO ' . $this->options['resources'] . '(
                    ar_name,
                    ar_description,
                    ar_datecreated) VALUES (?, ?, ?)',
                [$resource->getName(), $resource->getDescription(), time()]
            );
        }

        if ($accessList) {
            return $this->addResourceAccess($resource->getName(), $accessList);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @param  string                 $resourceName
     * @param  array|string           $accessList
     * @return boolean
     * @throws \Phalcon\Acl\Exception
     */
    public function addResourceAccess($resourceName, $accessList)
    {
        if (!$this->isResource($resourceName)) {
            throw new Exception("Resource '" . $resourceName . "' does not exist in ACL");
        }

        $sql = 'SELECT COUNT(*) FROM ' .
            $this->options['resourcesAccesses'] .
            ' WHERE rxa_resources_name = ? AND rxa_access_name = ?';

        if (is_array($accessList)) {
            foreach ($accessList as $accessName) {
                $exists = $this->options['db']->fetchOne($sql, null, [$resourceName, $accessName]);

                if (!$exists[0]) {
                    $this->options['db']->execute(
                        'INSERT INTO ' . $this->options['resourcesAccesses'] . '(
                            rxa_resources_name,
                            rxa_access_name,
                            rxa_datecreated) VALUES (?, ?, ?)',
                        [$resourceName, $accessName, time()]
                    );
                }
            }
        } else {
            $exists = $this->options['db']->fetchOne($sql, null, [$resourceName, $accessList]);
            if (!$exists[0]) {
                $this->options['db']->execute(
                    'INSERT INTO ' . $this->options['resourcesAccesses'] . '(
                        rxa_resources_name,
                        rxa_access_name,
                        rxa_datecreated) VALUES (?, ?, ?)',
                    [$resourceName, $accessList, time()]
                );
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Phalcon\Acl\Resource[]
     */
    public function getResources()
    {
        $resources = [];
        $sql       = 'SELECT * FROM ' . $this->options['resources'];

        foreach ($this->options['db']->fetchAll($sql, Db::FETCH_ASSOC) as $row) {
            $resources[] = new Resource($row['name'], $row['description']);
        }

        return $resources;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Phalcon\Acl\Role[]
     */
    public function getRoles()
    {
        $roles = [];
        $sql   = 'SELECT * FROM ' . $this->options['roles'];

        foreach ($this->options['db']->fetchAll($sql, Db::FETCH_ASSOC) as $row) {
            $roles[] = new Role($row['name'], $row['description']);
        }

        return $roles;
    }

    /**
     * {@inheritdoc}
     *
     * @param string       $resourceName
     * @param array|string $accessList
     */
    public function dropResourceAccess($resourceName, $accessList)
    {
    }

    /**
     * {@inheritdoc}
     * You can use '*' as wildcard
     * Example:
     * <code>
     * //Allow access to guests to search on customers
     * $acl->allow('guests', 'customers', 'search');
     * //Allow access to guests to search or create on customers
     * $acl->allow('guests', 'customers', array('search', 'create'));
     * //Allow access to any role to browse on products
     * $acl->allow('*', 'products', 'browse');
     * //Allow access to any role to browse on any resource
     * $acl->allow('*', '*', 'browse');
     * </code>
     *
     * @param string       $roleName
     * @param string       $resourceName
     * @param array|string $access
     */
    public function allow($roleName, $resourceName, $access)
    {
        $this->allowOrDeny($roleName, $resourceName, $access, Acl::ALLOW);
    }

    /**
     * {@inheritdoc}
     * You can use '*' as wildcard
     * Example:
     * <code>
     * //Deny access to guests to search on customers
     * $acl->deny('guests', 'customers', 'search');
     * //Deny access to guests to search or create on customers
     * $acl->deny('guests', 'customers', array('search', 'create'));
     * //Deny access to any role to browse on products
     * $acl->deny('*', 'products', 'browse');
     * //Deny access to any role to browse on any resource
     * $acl->deny('*', '*', 'browse');
     * </code>
     *
     * @param  string       $roleName
     * @param  string       $resourceName
     * @param  array|string $access
     * @return boolean
     */
    public function deny($roleName, $resourceName, $access)
    {
        $this->allowOrDeny($roleName, $resourceName, $access, Acl::DENY);
    }

    /**
     * {@inheritdoc}
     * Example:
     * <code>
     * //Does Andres have access to the customers resource to create?
     * $acl->isAllowed('Andres', 'Products', 'create');
     * //Do guests have access to any resource to edit?
     * $acl->isAllowed('guests', '*', 'edit');
     * </code>
     *
     * @param string $role
     * @param string $resource
     * @param string $access
     *
     * @return bool
     */
    public function isAllowed($role, $resource, $access)
    {
        $sql = implode(' ', [
            'SELECT aal_allowed FROM', $this->options['accessList'], 'AS a',
            // role_name in:
            'WHERE aal_roles_name IN (',
                // given 'role'-parameter
                'SELECT ? ',
                // inherited role_names
                'UNION SELECT ari_roles_inherit FROM', $this->options['rolesInherits'], 'WHERE ari_roles_name = ?',
                // or 'any'
                "UNION SELECT '*'",
            ')',
            // resources_name should be given one or 'any'
            "AND aal_resources_name IN (?, '*')",
            // access_name should be given one or 'any'
            "AND aal_access_name IN (?, '*')",
            // order be the sum of bools for 'literals' before 'any'
            "ORDER BY (aal_roles_name != '*')+(aal_resources_name != '*')+(aal_access_name != '*') DESC",
            // get only one...
            'LIMIT 1'
        ]);

        // fetch one entry...
        $allowed = $this->options['db']->fetchOne($sql, Db::FETCH_NUM, [$role, $role, $resource, $access]);

        if (is_array($allowed)) {
            return (int) $allowed[0];
        }

        /**
         * Return the default access action
         */

        return $this->_defaultAccess;
    }

    /**
     * Inserts/Updates a permission in the access list
     *
     * @param  string                 $roleName
     * @param  string                 $resourceName
     * @param  string                 $accessName
     * @param  integer                $action
     * @return boolean
     * @throws \Phalcon\Acl\Exception
     */
    protected function insertOrUpdateAccess($roleName, $resourceName, $accessName, $action)
    {
        /**
         * Check if the access is valid in the resource
         */
        $sql = 'SELECT COUNT(*) FROM ' .
            $this->options['resourcesAccesses'] .
            ' WHERE rxa_resources_name = ? AND rxa_access_name = ?';
        $exists = $this->options['db']->fetchOne($sql, null, [$resourceName, $accessName]);
        if (!$exists[0]) {
            throw new Exception(
                "Access '" . $accessName . "' does not exist in resource '" . $resourceName . "' in ACL"
            );
        }

        /**
         * Update the access in access_list
         */
        $sql = 'SELECT COUNT(*) FROM ' .
            $this->options['accessList'] .
            ' WHERE aal_roles_name = ? AND aal_resources_name = ? AND aal_access_name = ?';
        $exists = $this->options['db']->fetchOne($sql, null, [$roleName, $resourceName, $accessName]);
        if (!$exists[0]) {
            $sql = 'INSERT INTO ' . $this->options['accessList'] . '(
                aal_roles_name,
                aal_resources_name,
                aal_access_name,
                aal_allowed,
                aal_datecreated) VALUES (?, ?, ?, ?, ?)';
            $params = [$roleName, $resourceName, $accessName, $action, time()];
        } else {
            $sql = 'UPDATE ' .
                $this->options['accessList'] .
                ' SET aal_allowed = ?, aal_datemodified = ? WHERE aal_roles_name = ? AND aal_resources_name = ? AND aal_access_name = ?';
            $params = [$action, time(), $roleName, $resourceName, $accessName];
        }

        $this->options['db']->execute($sql, $params);

        /**
         * Update the access '*' in access_list
         */
        $sql = 'SELECT COUNT(*) FROM ' .
            $this->options['accessList'] .
            ' WHERE aal_roles_name = ? AND aal_resources_name = ? AND aal_access_name = ?';
        $exists = $this->options['db']->fetchOne($sql, null, [$roleName, $resourceName, '*']);
        if (!$exists[0]) {
            $sql = 'INSERT INTO ' . $this->options['accessList'] . '(
                aal_roles_name,
                aal_resources_name,
                aal_access_name,
                aal_allowed,
                aal_datecreated) VALUES (?, ?, ?, ?, ?)';
            $this->options['db']->execute($sql, [$roleName, $resourceName, '*', $this->_defaultAccess, time()]);
        }

        return true;
    }

    /**
     * Inserts/Updates a permission in the access list
     *
     * @param  string                 $roleName
     * @param  string                 $resourceName
     * @param  array|string           $access
     * @param  integer                $action
     * @throws \Phalcon\Acl\Exception
     */
    protected function allowOrDeny($roleName, $resourceName, $access, $action)
    {
        if (!$this->isRole($roleName)) {
            throw new Exception('Role "' . $roleName . '" does not exist in the list');
        }

        if (is_array($access)) {
            foreach ($access as $accessName) {
                $this->insertOrUpdateAccess($roleName, $resourceName, $accessName, $action);
            }
        } else {
            $this->insertOrUpdateAccess($roleName, $resourceName, $access, $action);
        }
    }
}
