<?php

namespace App;

class LineChart
{

    private $config;
    private $dataString;

    public function __construct()
    {
        
    }

    public function setDataString($dataString)
    {

        $this->dataString = $dataString;
        return $this;

    }

    public function setConfig($config)
    {
        $this->config = $config;
        return $this;

    }

    public function generate()
    {
        $html     = [];
        $confTemp = [];
        $dataTemp = [];

        $html[] = "Morris.Line({";

        foreach ($this->config as $confKey => $confValue) {
            if( $confKey == 'ykeys' || $confKey == 'labels' || $confKey == 'goals' || $confKey == 'goalLineColors' || $confKey == 'axes' || $confKey == 'grid' ) {
                $confTemp[] = $confKey . ": " . $confValue;
            } else {
                $confTemp[] = $confKey . ": '" . $confValue . "'";
            }
            
        }

        $html[] = implode(',', $confTemp) . ',';
        $html[] = "data: [";
        
        $html[] = $this->dataString;
            
        $html[] = implode(',',$dataTemp);
                
        $html[] = "]";

        $html[] = "});";

        return implode("\n", $html);

    }

}
