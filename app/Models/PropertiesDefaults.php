<?php

namespace App\Models;

use Framework\StringMethods as StringMethods;
use Framework\DateMethods as DateMethods;
use Framework\Model\Exception as Exception;

use App\Statics\Models as STATICS;

class PropertiesDefaults extends \Framework\Model
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
    protected $_id;

    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_type;
        
    public function getId_ptype()
    {
        $v_ref = substr($this->_type,0,1);
        $v_trans = substr($this->_type,1,1);
        $t_ref = array(
            "A" => STATICS::PROPERTY_TYPE_APPART,
            "M" => STATICS::PROPERTY_TYPE_HOUSE,
            "Z" => STATICS::PROPERTY_TYPE_PARKING
        );
        return isset($t_ref[$v_ref]) && $v_trans=="L" ? $t_ref[$v_ref] : STATICS::PROPERTY_TYPE_OTHER;
    }

    public function getId_ref()
    {
        $value = $this->id;
        return $value;
    }

    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_prix;
    
    public function getPrice()
    {
        $value = $this->_prix;
        return (int) $value;
    }

    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_code;

    public function getName()
    {
        $value = $this->_code;
        return $value;
    }

    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_adresse1;

    public function getStreet()
    {
        $value = $this->_adresse1;
        return $value;
    }

    /**
    * @column
    * @readwrite
    * @type integer
    */
    protected $_nummaison;

    public function getNum()
    {
        $value = $this->_nummaison;
        return (int) $value;
    }

    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_cp;

    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_commune;

    public function getVille()
    {
        $value = $this->_commune;
        return $value;
    }

    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_country;

    public function getId_cty()
    {
        $_id = STATICS::COUNTRY_TYPE_DEFAULT; // belgique
        $value = $this->_country;
        if(!empty($value)){
            $country = \App\Models\Countries::first(array(
                "name = ?" => $value
            ));
            if(empty($country)){
                $patterns = \App\Models\Countries::all(array(
                    "pattern != ?" => ""
                ));
                /*print("<pre>");
                print_r($patterns);
                print("</pre>");*/
                if(sizeof($patterns)>0){
                    for($i=0;$i<sizeof($patterns);$i++){
                        $_raw = $patterns[$i]->getRaw();
                        $patt = $_raw->pattern;
                        $match = StringMethods::match($value, $patt);
                        if((!empty($match) && $match!=$value) || strtoupper($_raw->name)==strtoupper($value)){
                            $_id = $_raw->id_cty;
                            break;
                        }
                    }
                }
            }else{
                $_id = $country->id_cty;
            }
        }
        return $_id;
    }

    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_titrefr;

    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_titrenl;

    public function getComment()
    {
        $value = $this->_titrefr.($this->_titrenl!="" ? PHP_EOL.$this->_titrenl:"");
        return $value;
    }

    /**
    * @column
    * @readwrite
    * @type date
    */
    protected $_datecrea;

    public function getDatein()
    {
        $value = $this->_datecrea;
        return false===strpos($value,"0000") ? $value : DateMethods::now();
    }

    public function getDatemod()
    {
        return DateMethods::now();
    }
}