<?php

namespace App\Models\Interfaces
{
    
    class FootablePipelineResponse
    {
        
        public $rows = array();

        public function __construct($recordset=array())
        {
            $this->rows = $recordset;
        }

        public function __toString(){
            return "FootablePipelineResponse.v.1.0";
        }

    }
}