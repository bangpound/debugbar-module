<?php

class DrupalTimeDataCollector extends \DebugBar\DataCollector\TimeDataCollector
{
    public function collect()
    {
        foreach ($GLOBALS['timers'] as $name => &$timer) {
            if (isset($timer['start'])) {
                timer_stop($name);
            }
            foreach ($timer['measures'] as $measure) {
                call_user_func_array(array($this, 'addMeasure'), $measure);
            }
        }

        return parent::collect();
    }
}
