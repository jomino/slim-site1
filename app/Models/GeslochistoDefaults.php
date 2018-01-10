<?php

namespace App\Models;

use Framework\StringMethods as StringMethods;
use Framework\Model\Exception as Exception;

use App\Statics\Models as STATICS;

class GeslochistoDefaults extends \Framework\Model
{

    /**
    * @read
    * @override
    */
    protected $_withLocal = false;

    /**
    * @override
    */
    public function getConnector()
    {
        if (empty($this->_connector))
        {
            $connector = new \App\Models\Interfaces\DefaultsConnector(array(
                "interface" => $this->interface
            ));
            if (!$connector)
            {
                throw new Exception\Connector("No connector !");
            }
            $this->_connector = $connector;
        }
        return $this->_connector;
    }

    /**
    * @column
    * @primary
    * @readwrite
    * @type integer
    * @label id
    */
    protected $_idhisto;
    
    /**
    * @column
    * @readwrite
    * @type integer
    */
    protected $_idgesloc;
    
    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_agence;
    
    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_rem;

    /**
    * @column
    * @readwrite
    * @type date
    */
    protected $_date;

    public function getDate()
    {
        $value = $this->_date;
        return strpos($value,"0000")===false ? $value:null;
    }

}