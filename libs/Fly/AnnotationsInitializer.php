<?php
/**
 * \Fly\AnnotationInitializer
 * AnnotationInitializer.php
 *
 * Model Annotation Initializer class
 *
 * @author      phalconphp.com
 * @since       2014-12-19
 * @category    Fly
 *
 */

namespace Fly;

use Phalcon\Events\Event;
use Phalcon\Mvc\Model\Manager as ModelsManager;
use Phalcon\Mvc\ModelInterface;

class AnnotationsInitializer extends \Phalcon\Mvc\User\Plugin
{

	/**
	 * This is called after initialize the model
	 *
	 * @param Phalcon\Events\Event $event
	 */
	public function afterInitialize(Event $event, ModelsManager $manager, ModelInterface $model)
	{
		//Reflector
		$reflector = $this->annotations->get($model);

		/**
		 * Read the annotations in the class' docblock
		 */
		$annotations = $reflector->getClassAnnotations();

		if ($annotations) {

			/**
			 * Traverse the annotations
			 */
			foreach ($annotations as $annotation) {
				switch ($annotation->getName()) {

					/**
					 * Initializes the model's source
					 */
					case 'Source':
						$arguments = $annotation->getArguments();
						$manager->setModelSource($model, $arguments[0]);
						break;

					/**
					 * Initializes Has-Many relations
					 */
					case 'HasMany':
						$arguments = $annotation->getArguments();
						$manager->addHasMany($model, $arguments[0], $arguments[1], $arguments[2]);
						break;

					/**
					 * Initializes Has-Many relations
					 */
					case 'BelongsTo':
						$arguments = $annotation->getArguments();
						if (isset($arguments[3])) {
							$manager->addBelongsTo($model, $arguments[0], $arguments[1], $arguments[2], $arguments[3]);
						} else {
							$manager->addBelongsTo($model, $arguments[0], $arguments[1], $arguments[2]);
						}
						break;
					/**
					 * Initializes has-One relations
					 */	
					case 'hasOne':
			                        $arguments = $annotation->getArguments();
			                        if (isset($arguments[3])) {
			                            $manager->addHasOne($model, $arguments[0], $arguments[1], $arguments[2], $arguments[3]);
			                        }
			                        else {
			                            $manager->addHasOne($model, $arguments[0], $arguments[1], $arguments[2]);
			                        }
			                        break;
			                /**
					 * Initializes hasManyToMany relations
					 */        
		                        case 'hasManyToMany':
			                        $arguments = $annotation->getArguments();
			                        if (isset($arguments[6])) {
			                            $manager->addHasManyToMany($model, $arguments[0], $arguments[1], $arguments[2], $arguments[3], $arguments[4], $arguments[5], $arguments[6]);
			                        }
			                        else {
			                            $manager->addHasManyToMany($model, $arguments[0], $arguments[1], $arguments[2], $arguments[3], $arguments[4], $arguments[5]);
			                        }
			                        break;
		                         /**
			                   * Initializes the model's Behavior
			                   */
			                 case 'Behavior':
			                        $arguments = $annotation->getArguments();
			                        $behaviorName = $arguments[0];
			                        if (isset($arguments[1])) {
			                            $manager->addBehavior($model, new $behaviorName($arguments[1]));
			                        } else {
			                            $manager->addBehavior($model, new $behaviorName);
			                        }
			                        break;
		                        /**
	                     		  * Initializes the model's source connection
			                  */
			                    case 'setConnectionService':
			                        $arguments = $annotation->getArguments();
			                        $manager->setConnectionService($model, $arguments[0]);
			                        break;
				}
			}
		}

	}

}
