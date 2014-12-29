<?php

/**
 *  Implements hook_pimple_info_alter().
 */
function debugbar_pimple_info_alter(&$info) {
  foreach ($info as &$definition) {
    $definition['calls'][] = array(
      'register',
      array(
        new \Bangpound\Provider\DebugbarServiceProvider(),
        array(
          'debugbar.base_url' => base_path() .'debugbar/asset',
          'debugbar.base_path' => null,
        ),
      ),
    );
    $definition['calls'][] = array(
      'register',
      array(
        new DrupalDebugbarServiceProvider(),
        array(),
      ),
    );
  }
}
