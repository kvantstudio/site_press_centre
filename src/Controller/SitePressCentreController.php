<?php

/**
 * @file
 * Contains \Drupal\site_press_centre\Controller\SitePressCentreController
 */

namespace Drupal\site_press_centre\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\views\Views;

class SitePressCentreController extends ControllerBase {

  /**
   * Страница отображения содержимого пресс-центра.
   */
  public static function view($type = 'block') {
    $build = array();

    $view = Views::getView('site_press_centre_last');
    $content = '';
    if (is_object($view)) {
      $view->setDisplay($type);
      $content = $view->buildRenderable();
    }

    // Создает HTML отображение материалов.
    $build['site_press_centre'] = array(
      '#theme' => 'site_press_centre',
      '#content' => $content,
    );

    return $build;
  }
}