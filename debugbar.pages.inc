<?php

function debugbar_open($request = null) {
  $c = pimple_get_container();
  $c['debugbar.handler.open']->handle($request);
}
