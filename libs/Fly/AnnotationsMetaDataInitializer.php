<?php
/**
 * \Fly\AnnotationsMetaDataInitializer
 * AnnotationsMetaDataInitializer.php
 *
 * Metadata Annotation Initializer class
 *
 * @author      phalconphp.com
 * @since       2014-12-19
 * @category    Fly
 *
 */

namespace Fly;

use Phalcon\Db\Column;
use Phalcon\DiInterface;
use Phalcon\Mvc\Model\MetaData;
use Phalcon\Mvc\ModelInterface;

class AnnotationsMetaDataInitializer
{

	/**
	 * Initializes the model's meta-data
	 *
	 * @param Phalcon\Mvc\ModelInterface $model
	 * @param Phalcon\DiInterface $di
	 * @return array
	 */
	public function getMetaData(ModelInterface $model, DiInterface $di)
	{
		$reflection = $di['annotations']->get($model);

		$properties = $reflection->getPropertiesAnnotations();
		if (!$properties) {
			throw new Exception("There are no properties defined on the class");
		}

		$attributes = [];
		$nullables = [];
		$dataTypes = [];
		$dataTypesBind = [];
		$numericTypes = [];
		$primaryKeys = [];
		$nonPrimaryKeys = [];
		$identity = false;

		foreach ($properties as $name => $collection) {

			if ($collection->has('Column')) {

				$arguments = $collection->get('Column')->getArguments();

				/**
				 * Get the column's name
				 */
				if (isset($arguments['column'])) {
					$columnName = $arguments['column'];
				} else {
					$columnName = $name;
				}

				/**
				 * Check for the 'type' parameter in the 'Column' annotation
				 */
				if (isset($arguments['type'])) {
					switch ($arguments['type']) {
						case 'integer':
							$dataTypes[$columnName] = Column::TYPE_INTEGER;
							$dataTypesBind[$columnName] = Column::BIND_PARAM_INT;
							$numericTypes[$columnName] = true;
							break;
						case 'string':
							$dataTypes[$columnName] = Column::TYPE_VARCHAR;
							$dataTypesBind[$columnName] = Column::BIND_PARAM_STR;
							break;
					}
				} else {
					$dataTypes[$columnName] = Column::TYPE_VARCHAR;
					$dataTypesBind[$columnName] = Column::BIND_PARAM_STR;
				}

				/**
				 * Check for the 'nullable' parameter in the 'Column' annotation
				 */
				if (!$collection->has('Identity')) {
					if (isset($arguments['nullable'])) {
						if (!$arguments['nullable']) {
							$nullables[] = $columnName;
						}
					}
				}

				$attributes[] = $columnName;

				/**
				 * Check if the attribute is marked as primary
				 */
				if ($collection->has('Primary')) {
					$primaryKeys[] = $columnName;
				} else {
					$nonPrimaryKeys[] = $columnName;
				}

				/**
				 * Check if the attribute is marked as identity
				 */
				if ($collection->has('Identity')) {
					$identity = $columnName;
				}

			}


		}

		return [

            //Every column in the mapped table
            MetaData::MODELS_ATTRIBUTES => $attributes,

            //Every column part of the primary key
            MetaData::MODELS_PRIMARY_KEY => $primaryKeys,

            //Every column that isn't part of the primary key
            MetaData::MODELS_NON_PRIMARY_KEY => $nonPrimaryKeys,

            //Every column that doesn't allows null values
            MetaData::MODELS_NOT_NULL => $nullables,

            //Every column and their data types
            MetaData::MODELS_DATA_TYPES => $dataTypes,

            //The columns that have numeric data types
            MetaData::MODELS_DATA_TYPES_NUMERIC => $numericTypes,

            //The identity column, use boolean false if the model doesn't have
            //an identity column
            MetaData::MODELS_IDENTITY_COLUMN => $identity,

            //How every column must be bound/casted
            MetaData::MODELS_DATA_TYPES_BIND => $dataTypesBind,

            //Fields that must be ignored from INSERT SQL statements
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],

            //Fields that must be ignored from UPDATE SQL statements
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => []

        ];
	}

	/**
	 * Initializes the model's column map
	 *
	 * @param Phalcon\Mvc\ModelInterface $model
	 * @param Phalcon\DiInterface $di
	 * @return array
	 */
	public function getColumnMaps(ModelInterface $model, DiInterface $di)
	{
		$reflection = $di['annotations']->get($model);

		$columnMap = [];
		$reverseColumnMap = [];

		$renamed = false;
		foreach ($reflection->getPropertiesAnnotations() as $name => $collection) {

			if ($collection->has('Column')) {

				$arguments = $collection->get('Column')->getArguments();

				/**
				 * Get the column's name
				 */
				if (isset($arguments['column'])) {
					$columnName = $arguments['column'];
				} else {
					$columnName = $name;
				}

				$columnMap[$columnName] = $name;
				$reverseColumnMap[$name] = $columnName;

				if (!$renamed) {
					if ($columnName != $name) {
						$renamed = true;
					}
				}
			}
		}

		if ($renamed) {
			return [
				MetaData::MODELS_COLUMN_MAP => $columnMap,
				MetaData::MODELS_REVERSE_COLUMN_MAP => $reverseColumnMap
			];
		}

		return null;
	}

}
