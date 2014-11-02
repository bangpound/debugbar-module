<?php

/**
 *  Implements hook_pimple_info().
 */
function debugbar_pimple_info() {
  $base_url = base_path() . variable_get('composer_manager_vendor_dir', 'sites/all/vendor');
  $base_url .= '/maximebf/debugbar/src/DebugBar/Resources';
  $info['debugbar'] = array(
    'class' => 'Pimple\Container',
    'arguments' => array(
      array(
      ),
    ),
    'calls' => array(
      array(
        'register',
        array(
          new \Bangpound\Provider\DebugbarServiceProvider(),
          array(
            'debugbar.base_url' => $base_url,
            'debugbar.base_path' => null,
          ),
        ),
      ),
      array(
        'register',
        array(
          new DrupalDebugbarServiceProvider(),
          array(),
        ),
      ),
    ),
  );
  return $info;
}
