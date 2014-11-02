<?php

class DrupalDebugbarServiceProvider implements \Pimple\ServiceProviderInterface
{
    public function register(\Pimple\Container $pimple)
    {
        $pimple['debugbar.http_driver.drupal'] = function (\Pimple\Container $c) {
            return new DrupalHttpDriver();
        };

        $pimple['debugbar.collector.drupal_database'] = function ($c) {
            return new DrupalDbTngCollector();
        };

        $pimple['debugbar.collector.time_data'] = function ($c) {
            return new DrupalTimeDataCollector();
        };

        $pimple['debugbar.collector.menu_item'] = function ($c) {
            $item = menu_get_item();
            if (!$item) {
                $item = array();
            }

            return new \DebugBar\DataCollector\ConfigCollector($item, 'Menu');
        };

        $pimple['debugbar.collector.globals'] = function ($c) {
            return new GlobalsConfigCollector();
        };

        $pimple['drupal.pdo'] = function ($c) {
            $connection_info = \Database::getConnectionInfo();
            $connection_options = $connection_info['default'];

            // The DSN should use either a socket or a host/port.
            if (isset($connection_options['unix_socket'])) {
                $dsn = 'mysql:unix_socket='.$connection_options['unix_socket'];
            } else {
                // Default to TCP connection on port 3306.
                $dsn = 'mysql:host='.$connection_options['host'].';port='.(empty($connection_options['port']) ? 3306 : $connection_options['port']);
            }
            $dsn .= ';dbname='.$connection_options['database'];
            // Allow PDO options to be overridden.
            $connection_options += array(
              'pdo' => array(),
            );
            $connection_options['pdo'] += array(
                // So we don't have to mess around with cursors and unbuffered queries by default.
              PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => TRUE,
                // Because MySQL's prepared statements skip the query cache, because it's dumb.
              PDO::ATTR_EMULATE_PREPARES => TRUE,
            );

            return new PDO($dsn, $connection_options['username'], $connection_options['password'], $connection_options['pdo']);
        };

        $pimple['debugbar.storage.drupal'] = function ($c) {
            return new \DebugBar\Storage\PdoStorage($c['drupal.pdo']);
        };
        $pimple->extend('debugbar', function (\DebugBar\DebugBar $debugbar, \Pimple\Container $c) {
              $debugbar->setStorage($c['debugbar.storage.drupal']);
              $debugbar->setHttpDriver($c['debugbar.http_driver.drupal']);

              \Symfony\Component\Debug\ErrorHandler::setLogger($c['debugbar.collector.messages'], 'scream');
              set_exception_handler(array($c['debugbar.collector.exceptions'], 'addException'));

              return $debugbar;
          });

        $pimple->extend('debugbar.renderer', function (\DebugBar\JavascriptRenderer $renderer, \Pimple\Container $c) {
              $renderer->setOpenHandlerUrl('/debugbar/open');

              return $renderer;
        });
    }
}
