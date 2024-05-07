<?php

declare(strict_types=1);

namespace Drupal\token_tree_filter;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

/**
 * Modifies the Token tree builder service.
 *
 * Ideas taken from https://www.drupal.org/docs/drupal-apis/services-and-dependency-injection/altering-existing-services-providing-dynamic-services
 */
class TokenTreeFilterServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container) {
    if ($container->hasDefinition('token.tree_builder')) {
      $definition = $container->getDefinition('token.tree_builder');
      $definition->setClass('Drupal\token_tree_filter\Service\TokenTreeFilterBuilder');
    }
  }

}
