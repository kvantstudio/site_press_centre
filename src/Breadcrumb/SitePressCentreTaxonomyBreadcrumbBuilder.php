<?php

/**
 * @file
 * Contains \Drupal\site_press_centre\Breadcrumb\SitePressCentreTaxonomyBreadcrumbBuilder.
 */

namespace Drupal\site_press_centre\Breadcrumb;

use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Link;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;

/**
 * Class to define the site_press_centre breadcrumb builder.
 */
class SitePressCentreTaxonomyBreadcrumbBuilder implements BreadcrumbBuilderInterface {
  use StringTranslationTrait;

  /**
   * The entity manager.
   *
   * @var \Drupal\Core\Entity\EntityManagerInterface
   */
  protected $entityManager;

  /**
   * The taxonomy storage.
   *
   * @var \Drupal\Taxonomy\TermStorageInterface
   */
  protected $termStorage;

  /**
   * Constructs the TermBreadcrumbBuilder.
   *
   * @param \Drupal\Core\Entity\EntityManagerInterface $entityManager
   *   The entity manager.
   */
  public function __construct(EntityManagerInterface $entityManager) {
    $this->entityManager = $entityManager;
    $this->termStorage = $entityManager->getStorage('taxonomy_term');
  }

  /**
   * @inheritdoc
   */
  public function applies(RouteMatchInterface $route_match) {
    if ($route_match->getRouteName() == 'entity.taxonomy_term.canonical') {
      $term = $route_match->getParameter('taxonomy_term');
      $vocabuladyId = $term->getVocabularyId();
      if ($vocabuladyId == 'site_press_centre') {
        return TRUE;
      }
    }
  }

  /**
   * @inheritdoc
   */
  public function build(RouteMatchInterface $route_match) {
    $config = \Drupal::config('site_press_centre.settings');

    $breadcrumb = new Breadcrumb();

    $breadcrumb->addLink(Link::createFromRoute($this->t('Home'), '<front>'));

    $view = \Drupal\views\Views::getView('site_press_centre');
    $view->setDisplay('page');
    $link = Link::fromTextAndUrl($view->getTitle(), Url::fromUserInput('/' . $config->get('url')));
    $breadcrumb->addLink($link);

    return $breadcrumb;
  }

}
