<?php

namespace App;

class BarChart
{

    private $config;
    private $data;

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

        $html[] = "Morris.Bar({";

        foreach ($this->config as $confKey => $confValue) {
            if( $confKey == 'ykeys' || $confKey == 'labels' ) {
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
