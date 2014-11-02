<?php

/**
 * @return string
 */
function debugbar_monolog_handler_path() {
  return 'handlers';
}

/**
 * @return array
 */
function debugbar_monolog_handler_info() {
  $handlers = array();

  $handlers['debugbar'] = array(
    'label' => t('Debugbar Handler'),
    'description' => t('Logs records into debugbar.'),
    'group' => t('Development'),
    'settings callback' => FALSE,
  );

  return $handlers;
}
