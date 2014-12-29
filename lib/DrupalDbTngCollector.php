<?php

use DebugBar\DataCollector\AssetProvider;
use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\Renderable;

/**
 * Collects data about SQL statements executed with PDO
 */
class DrupalDbTngCollector extends DataCollector implements Renderable, AssetProvider
{
    protected $renderSqlWithParams = false;

    protected $sqlQuotationChar = '<>';

    /**
     */
    public function __construct()
    {
        Database::startLog('debugbar', 'default');
    }

    public function collect()
    {
        $data = array(
          'nb_statements' => 0,
          'nb_failed_statements' => 0,
          'accumulated_duration' => 0,
          'memory_usage' => 0,
          'peak_memory_usage' => 0,
          'statements' => array(),
        );

        foreach (array('default') as $name) {
            $pdodata = $this->collectPDO($name);
            $data['nb_statements'] += $pdodata['nb_statements'];
            $data['statements'] = array_merge($data['statements'],
              array_map(function ($s) use ($name) {
                $s['connection'] = $name;

                return $s;
              }, $pdodata['statements']));
        }

        return $data;
    }

    /**
     * Collects data from a single TraceablePDO instance
     *
     * @return array
     */
    protected function collectPDO($name)
    {
        $stmts = array();
        foreach (Database::getLog('debugbar', $name) as $stmt) {
            $conn = Database::getConnection('default', $name);
            $quoted = array();
            foreach ((array) $stmt['args'] as $key => $val) {
                $quoted[$key] = $conn->quote($val);
            }
            $output = strtr($stmt['query'], $quoted);

            $stmts[] = array(
              'sql' => $this->renderSqlWithParams ? $output : $stmt['query'],
              'prepared_stmt' => $stmt['query'],
              'params' => $stmt['args'],
              'duration' => $stmt['time'],
              'duration_str' => $this->getDataFormatter()->formatDuration($stmt['time']),
            );
        }

        return array(
          'nb_statements' => count($stmts),
          'statements' => $stmts,
        );
    }

    public function getName()
    {
        return 'dbtng';
    }

    public function getWidgets()
    {
        return array(
          "database" => array(
            "icon" => "inbox",
            "widget" => "PhpDebugBar.Widgets.SQLQueriesWidget",
            "map" => "dbtng",
            "default" => "[]",
          ),
          "database:badge" => array(
            "map" => "dbtng.nb_statements",
            "default" => 0,
          ),
        );
    }

    public function getAssets()
    {
        return array(
          'css' => 'widgets/sqlqueries/widget.css',
          'js' => 'widgets/sqlqueries/widget.js',
        );
    }
}
