<?php

namespace App;

class DonutChart
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

        $html[] = "Morris.Donut({";

        foreach ($this->config as $confKey => $confValue) {
            $confTemp[] = $confKey . ": '" . $confValue . "'";
        }

        $html[] = implode(',', $confTemp) . ',';
        $html[] = "data: [";

        $html[] = $this->dataString;

        $html[] = implode(',', $dataTemp);

        $html[] = "]";

        $html[] = "});";

        return implode("\n", $html);

    }

}
