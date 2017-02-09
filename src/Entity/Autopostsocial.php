<?php

namespace Drupal\autopost_social\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the robot entity.
 *
 * The lines below, starting with '@ConfigEntityType,' are a plugin annotation.
 * These define the entity type to the entity type manager.
 *
 * The properties in the annotation are as follows:
 *  - id: The machine name of the entity type.
 *  - label: The human-readable label of the entity type. We pass this through
 *    the "@Translation" wrapper so that the multilingual system may
 *    translate it in the user interface.
 *  - handlers: An array of entity handler classes, keyed by handler type.
 *    - access: The class that is used for access checks.
 *    - list_builder: The class that provides listings of the entity.
 *    - form: An array of entity form classes keyed by their operation.
 *  - entity_keys: Specifies the class properties in which unique keys are
 *    stored for this entity type. Unique keys are properties which you know
 *    will be unique, and which the entity manager can use as unique in database
 *    queries.
 *  - links: entity URL definitions. These are mostly used for Field UI.
 *    Arbitrary keys can set here. For example, User sets cancel-form, while
 *    Node uses delete-form.
 *
 * @see http://previousnext.com.au/blog/understanding-drupal-8s-config-entities
 * @see annotation
 * @see Drupal\Core\Annotation\Translation
 *
 * @ingroup config_entity_example
 *
 * @ConfigEntityType(
 *   id = "autopostsocial",
 *   label = @Translation("Autopost"),
 *   admin_permission = "administer content",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   }
 * )
 */
class Autopostsocial extends ConfigEntityBase {

  /**
   * The robot ID.
   *
   * @var string
   */
  public $id;

  /**
   * The robot UUID.
   *
   * @var string
   */
  public $nid;

  /**
   * The Providers label.
   *
   * @var string
   */
  public $providers;

  /**
   * The Providers label.
   *
   * @var string
   */
  public $status;
}
