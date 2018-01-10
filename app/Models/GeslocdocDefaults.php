<?php

namespace App\Models;

class GeslocdocDefaults extends \Framework\Model
{

    /**
    * @read
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
    * @label historic id
    */
    protected $_iddoc;
    
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
    * @type integer
    */
    protected $_doctype;
    
    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_label_compat;

    public function setLabel($arg1,$arg2="")
    {
        //override for compat inherited 'setLabel' method
        $this->_label_compat = $arg1;
    }
    
    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_filedatas;

    /**
    * @column
    * @readwrite
    * @type date
    */
    protected $_dt_in;

    public function getDt_in()
    {
        $value = $this->_dt_in;
        return strpos($value,"0000")===false ? $value:null;
    }

}