<?php

class DrupalHttpDriver extends \DebugBar\PhpHttpDriver
{
    /**
   * Sets HTTP headers
   *
   * @param array $headers
   */
  public function setHeaders(array $headers)
  {
      foreach ($headers as $name => $value) {
          drupal_add_http_header($name, $value, true);
      }
  }

    public function isSessionStarted()
    {
        return drupal_session_started();
    }
}
