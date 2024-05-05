<?php

/**
 * @file
 * Contains \Robo\RoboFile.
 *
 * Implementation of class for Robo - http://robo.li/
 *
 * You may override methods provided by RoboFileBase.php in this file.
 * Configuration overrides should be made in the constructor.
 */

declare(strict_types=1);

use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

include_once 'RoboFileBase.php';

/**
 * Class RoboFile.
 */
class RoboFile extends RoboFileBase {

  // @codingStandardsIgnoreStart
  /**
   * Example of overriding the constructor.
   *
   * Coding standards ignored as its empty.
   */
  public function __construct() {
    parent::__construct();
    // Put project specific overrides here, below the parent constructor.
  }
  // @codingStandardsIgnoreEnd

  /**
   * Example of how to install from existing config.
   * Uncomment to use.
   */
  public function buildInstall(): void {
    $this->drush('site:install')
      ->arg($this->drupalProfile)
      ->option('existing-config')
      ->option('account-mail', $this->config['site']['admin_email'])
      ->option('account-name', $this->config['site']['admin_user'])
      ->option('account-pass', $this->config['site']['admin_password'])
      ->option('site-name', $this->config['site']['title'])
      ->option('site-mail', $this->config['site']['mail'])
      ->option('yes')
      ->run();

    $this->drush('pm:enable')
      ->arg('token_tree_content')
      ->run();
  }

  /**
   * Export the default content for the profile module.
   *
   * @noinspection PhpUnused
   */
  public function devContentExport($module = '/code/web/profiles/custom/token_tree_profile/modules/token_tree_content'): void {
    $folder = $module . '/content';

    // Export the references.
    $this->drush('default-content:export-references')
      ->arg('node')
      ->rawArg('--folder=' . $folder)
      ->run();

    // Load the existing yml of the module
    $moduleFile = Yaml::parseFile($module . '/' . basename($module) . '.info.yml');

    foreach ((new Finder())->directories()->in($folder) as $directory) {
      $dir = (string)$directory;
      $dirArray = explode('/', $dir);
      $type = end($dirArray);
      unset($moduleFile['default_content'][$type]);

      $files = [];
      foreach ((new Finder())->files()->name('*.yml')->in($dir) as $filename) {
        $fileArray = explode('/', (string)$filename);
        $file = end($fileArray);
        array_unshift($files, rtrim($file, '.yml'));
      }
      $moduleFile['default_content'][$type] = $files;
    }

    $this->say('Updated module info file.');
    $this->io()->write(Yaml::dump($moduleFile, 3, 2));
  }

}
