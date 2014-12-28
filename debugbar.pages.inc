<?php

function debugbar_open() {
  $c = pimple_get_container();
  /** @var \DebugBar\OpenHandler $debugbar */
  $handler = $c['debugbar.handler.open'];
  $json = call_user_func_array(array($handler, 'handle'), func_get_args());
}
