<?php

namespace Drupal\site_press_centre\Plugin\views\style;

use Drupal\views\Plugin\views\style\StylePluginBase;

/**
 * Style plugin for the cards view.
 *
 * @ViewsStyle(
 *   id = "site_press_centre_taxonomy_term_style",
 *   title = @Translation("News and articles"),
 *   help = @Translation(""),
 *   theme = "site_press_centre_taxonomy_term",
 *   display_types = {"normal"}
 * )
 */
class SitePressCentreTaxonomyTermStyle extends StylePluginBase {

  /**
   * Does this Style plugin allow Row plugins?
   *
   * @var bool
   */
  protected $usesRowPlugin = TRUE;

  /**
   * Does the Style plugin support grouping of rows?
   *
   * @var bool
   */
  protected $usesGrouping = FALSE;

}