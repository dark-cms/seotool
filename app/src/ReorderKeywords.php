<?php

namespace App;

class ReorderKeywords
{

    private $db;
    private $timing = '';

    public function __construct($db, $newTiming='')
    {
        $this->db = $db;
        $this->timing = $newTiming;
    }

    public function start()
    {

        if( $this->timing != '' ) {
            $hours = $this->timing;
        } else {
            $this->db->where('optionName','cronjobHours');
            $result = $this->db->getOne('settings');
            $hours = $result['value'];
        }


        $hours = explode(',', $hours);
        $tPh   = count($hours);

        $result    = $this->db->getOne('keywords', 'COUNT(*) as number');
        
        $kwPh       = $result['number'] / $tPh;
        $limitPhour = ceil($kwPh);
                
        $i = 0;
        foreach ($hours as $hour) {
            if($i < $tPh + 1) {
                $start = $i * $limitPhour;
                $query = "UPDATE st_keywords SET keywordUpdateHour=$hour WHERE keywordID IN (
                            SELECT keywordID FROM (
                                SELECT keywordID FROM st_keywords 
                                ORDER BY keywordID DESC  
                                LIMIT $start, $limitPhour
                          ) tmp
                        )";
                $this->db->rawQuery($query);
                

            }
            $i++;
        }

    }

}
