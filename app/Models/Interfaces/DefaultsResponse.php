<?php

namespace App\Models\Interfaces;

use Framework\Service\Response as Response;

class DefaultsResponse extends Response\Json
{

    /**
    * @override
    */
    protected function parse()
    {
        //var_dump($this);
        $datas = array();
        if(!empty($this->_payload)){
            $payload = json_decode($this->payload,false);
            //var_dump($payload);
            if(isset($payload->result)){
                $result = $payload->result;
                //var_dump($result);
                if(is_object($payload->result)){
                    if(isset($result->success) && $result->success==true){
                        $datas = $result->datas;
                    }
                }
                if(is_array($payload->result)){
                    $datas = $payload->result;
                }
            }
        }
        return $datas;
    }

}
