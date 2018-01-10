<?php

namespace Framework\Database\Response
{
    
    class Datatable
    {
        
        public $draw = 0;
        public $data = array();
        public $recordsTotal = 0;
        public $recordsFiltered = 0;

        public function __construct($recordset=array())
        {
            $this->data = $recordset;
        }

        public function __toString(){
            return json_encode($this);
        }

    }
}