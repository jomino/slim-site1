<?php

namespace App\Models\Interfaces;

use Framework\Service\Request as Request;

class PropertiesDefaultsRequest extends Request\Json
{
    
    public function __construct($options=array())
    {

        parent::__construct($options);

        $this->setProperty("tid",9999);
        $this->setProperty("type","rpc");
        
        $this->setProperty("action","GridviewDataBase");
        $this->setProperty("method","selectRecords");

        $_data = new \stdClass();

        if($this->query){
            $q = $this->query;
            if($q->where){
                $q_where = $q->where;
                foreach($q_where as $k=>$v){
                    $_data->{$k} = $v;
                }
            }
        }

        $this->setProperty("data",[$_data]);

    }

}
    