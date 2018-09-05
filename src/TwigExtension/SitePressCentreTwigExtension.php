<?php

namespace Drupal\site_press_centre\TwigExtension;

use Drupal\node\Entity\Node;
use Drupal\site_press_centre\Controller\SiteSitePressCentreController;

/**
 * Twig extension that adds a custom function and a custom filter.
 */
class SitePressCentreTwigExtension extends \Twig_Extension {

  /**
   * In this function we can declare the extension function
   */
  public function getFunctions() {
    return array(
      new \Twig_SimpleFunction('getLastPressCentre', array($this, 'getLastPressCentre'), array('is_safe' => array('html'))),
      new \Twig_SimpleFunction('getPressCentre', array($this, 'getPressCentre')),
      new \Twig_SimpleFunction('getLastPressCentreByCategory', array($this, 'getLastPressCentreByCategory')),
    );
  }

  /**
   * Gets a unique identifier for this Twig extension.
   *
   * @return string
   *   A unique identifier for this Twig extension.
   */
  public function getName() {
    return 'site_press_centre.twig_extension';
  }

  /**
   * Формирует блок с последними публикациями.
   */
  public static function getLastPressCentre($type = 'block') {
    return SitePressCentreController::view($type);
  }

  /**
   * Формирует новость по номеру с конца.
   */
  public static function getPressCentre($id = 0) {
    $db = \Drupal::database();
    $query = $db->select('node_field_data', 'n');
    $query->condition('n.status', 1);
    $query->condition('n.type', 'press_centre');
    $query->fields('n', array('nid'));
    $query->orderBy('n.created', 'DESC');
    $query->range($id, 1);
    $nid = $query->execute()->fetchField();

    $data = [];
    if (!empty($nid)) {
      $node = Node::load($nid);

      $viewmode = 'default';
      $entityType = 'node';
      $display = entity_get_display($entityType, 'review', $viewmode);
      $viewBuilder = \Drupal::entityTypeManager()->getViewBuilder($entityType);

      $fieldsToRender = array(
        'title', 'body', 'field_image',
      );

      $data['node'] = $node;

      foreach ($fieldsToRender as $field_name) {
        if (isset($node->{$field_name}) && $field = $node->{$field_name}) {
          $fieldRenderable = $viewBuilder->viewField($field, $display->getComponent($field_name));
          if (count($fieldRenderable) && !empty($fieldRenderable)) {
            $data[$field_name] = drupal_render($fieldRenderable);
          }
        }
      }
    }

    return $data;
  }

  /**
   * Формирует массив с перечнем последних новостей по категории.
   */
  public static function getLastPressCentreByCategory($tid = 0, $count = 5) {
    $db = \Drupal::database();
    $query = $db->select('node_field_data', 'n');
    $query->condition('n.status', 1);
    $query->condition('n.type', 'press_centre');
    $query->fields('n', array('nid', 'title', 'created'));
    $query->orderBy('n.created', 'DESC');
    $query->range(0, $count);

    if ($tid) {
      $query->join('node__field_category', 't', 'n.nid = t.entity_id');
      $query->condition('t.field_category_target_id', $tid);
    }

    $result = $query->execute();

    $data = [];

    foreach ($result as $row) {
      $data[$row->nid] = [
        'title' => $row->title,
        'created' => $row->created,
      ];
    }

    return $data;
  }
}