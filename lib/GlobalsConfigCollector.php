<?php

class GlobalsConfigCollector extends \DebugBar\DataCollector\ConfigCollector
{
    public function __construct()
    {
        $this->name = '$GLOBALS';
        $this->data = &$GLOBALS;
    }

    public function setData(array $data)
    {
        throw new \Exception();
    }

    public function collect()
    {
        $data = array();
        $vars = array('_GET', '_POST', '_SESSION', '_COOKIE', '_SERVER', 'GLOBALS');
        foreach ($this->data as $k => $v) {
            if (in_array($k, $vars)) {
                continue;
            }
            if (!is_string($v)) {
                $v = $this->getDataFormatter()->formatVar($v);
            }
            $data[$k] = $v;
        }

        return $data;
    }
}
