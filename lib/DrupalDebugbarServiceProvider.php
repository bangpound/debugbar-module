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

        $pimple['debugbar.storage.drupal'] = function ($c) {

            /** @var \PDO $pdo */
            $pdo = $c['db']->getWrappedConnection();

            return new \DebugBar\Storage\PdoStorage($pdo);
        };

        $pimple['debugbar.storage.file'] = function ($c) {
            return new \DebugBar\Storage\FileStorage('var://debugbar');
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
