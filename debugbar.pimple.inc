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
