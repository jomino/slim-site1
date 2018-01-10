<?php

namespace App\Models\Interfaces;

use Framework\Service\Request as Request;

class GeslochistoDefaultsRequest extends Request\Json
{
    
    public function __construct($options=array())
    {

        parent::__construct($options);

        $this->setProperty("tid",9999);
        $this->setProperty("type","rpc");
        
        $this->setProperty("action","GeslochistoDataBase");
        $this->setProperty("method","selectRecord");

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

        //var_dump($_data);

        $this->setProperty("data",[$_data]);

    }

}
    