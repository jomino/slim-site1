<?php

namespace App\Models\Interfaces;

use Framework\Service\Request as Request;

class UsersDefaultsRequest extends Request\Json
{
    
    public function __construct($options=array())
    {

        parent::__construct($options);

        $this->setProperty("tid",9999);
        $this->setProperty("type","rpc");
        
        $this->setProperty("action","LightWeightUserDB");
        $this->setProperty("method","selectRecords");

        $_data = new \stdClass();

        $sort = new \stdClass();
        $sort->property = "nom";
        $sort->direction = "ASC";

        $_data->sort = [$sort];

        $fl_level = new \stdClass();
        $fl_level->property = "level";
        $fl_level->value = "L,P,Y";

        $_data->filter = array(
            $fl_level
        );

        if($this->query){
            $q = $this->query;
            if($q->where){
                $q_where = $q->where;
                foreach($q_where as $k=>$v){
                    if($k!="filter"){ $_data->{$k} = $v; }
                    else{ $_data->filter[] = (object)$v; }
                }
            }
            if($q->limit){
                $_data->limit = $q->limit;
                $_data->start = $q->offset;
            }
        }

        $this->setProperty("data",[$_data]);

    }

}
    