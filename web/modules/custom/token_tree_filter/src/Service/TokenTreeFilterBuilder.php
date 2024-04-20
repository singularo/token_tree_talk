<?php

declare(strict_types=1);

namespace Drupal\token_tree_filter\Service;

use Drupal\token\TreeBuilder;

/**
 * Provide our own Token TreeBuilder.
 *
 * Fully backwards compatible, but if you specify some extra, its
 * will filter out all variables except those which match, to
 * provide a simpler user experience.
 */
class TokenTreeFilterBuilder extends TreeBuilder {

  /**
   * Filter token output to only provide a subset of tokens.
   *
   * {@inheritdoc}
   */
  public function buildRenderable(array $token_types, array $options = []) {
    $new_token_types = $filters = $removals = [];

    // Build the list of token types for the parent.
    foreach ($token_types as $token_type) {
      if (str_contains($token_type, '%')) {
        [$token_type, $filter] = explode('%', $token_type);
        $filters[$token_type][] = $filter;
      }

      if (str_contains($token_type, '!')) {
        [$token_type, $filter] = explode('!', $token_type);
        $removals[$token_type][] = $filter;
      }

      $new_token_types[$token_type] = $token_type;
    }

    // Get the full list of tokens.
    $token_types = $new_token_types;
    $tree = parent::buildRenderable($token_types, $options);

    // Filter to only the allowed things now.
    foreach ($filters as $type => $list) {
      foreach ($tree['#token_tree'][$type]['tokens'] as $token => $details) {
        foreach ($list as $filter) {
          if (str_contains($token, $filter)) {
            continue 2;
          }
        }
        unset($tree['#token_tree'][$type]['tokens'][$token]);
      }
    }

    // Finally take out any specific removals.
    foreach ($removals as $type => $list) {
      foreach ($tree['#token_tree'][$type]['tokens'] as $token => $details) {
        foreach ($list as $filter) {
          if (str_contains($token, $filter)) {
            unset($tree['#token_tree'][$type]['tokens'][$token]);
          }
        }
      }
    }

    // Sort things more nicely.
    foreach ($token_types as $type) {
      // Don't try and sort if its not an array.
      if (is_array($tree['#token_tree'][$type]['tokens'])) {
        uasort($tree['#token_tree'][$type]['tokens'], fn ($a, $b) => $a['token'] <=> $b['token']);
      }
    }

    return $tree;
  }

}
