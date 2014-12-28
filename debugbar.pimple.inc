<?php

/**
 *  Implements hook_pimple_info_alter().
 */
function debugbar_pimple_info_alter(&$info) {
  $base_url = base_path() . variable_get('composer_manager_vendor_dir', 'sites/all/vendor');
  $base_url .= '/maximebf/debugbar/src/DebugBar/Resources';
  foreach ($info as &$definition) {
    $definition['calls'][] = array(
      'register',
      array(
        new \Bangpound\Provider\DebugbarServiceProvider(),
        array(
          'debugbar.base_url' => $base_url,
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
