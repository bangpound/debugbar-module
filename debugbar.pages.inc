<?php

function debugbar_open($request = null) {
  $c = pimple_get_container();
  $c['debugbar.handler.open']->handle($request);
}

function debugbar_asset() {
  $c = pimple_get_container();
  $r = new ReflectionObject($c['debugbar']);
  $uri = dirname($r->getFileName());
  $uri .= DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, func_get_args());
  if (file_exists($uri)) {
    $fileinfo = new SplFileInfo($uri);
    $mimetype = DrupalLocalStreamWrapper::getMimeType($uri);
    $headers = array(
      'Content-Type' => $mimetype,
      'Content-Length' => $fileinfo->getSize(),
      'Cache-Control' => 'private',
    );
    if (ob_get_level()) {
      ob_end_clean();
    }
    foreach ($headers as $name => $value) {
      drupal_add_http_header($name, $value);
    }
    drupal_send_headers();
    // Transfer file in 1024 byte chunks to save memory usage.
    if ($fd = fopen($uri, 'rb')) {
      while (!feof($fd)) {
        print fread($fd, 1024);
      }
      fclose($fd);
    }
  }
  else {
    drupal_not_found();
  }
  drupal_exit();
}
