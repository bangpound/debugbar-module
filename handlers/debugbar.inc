<?php

/**
 * @param $options
 * @return mixed
 */
function monolog_debugbar_handler_loader($options) {
  $c = pimple_get_container('debugbar');
  return $c['debugbar.collector.logger_data'];
}
