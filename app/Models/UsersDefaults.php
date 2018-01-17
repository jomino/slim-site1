<?php

namespace App\Models;

use Framework\StringMethods as StringMethods;
use Framework\Model\Exception as Exception;

use App\Statics\Models as STATICS;

class UsersDefaults extends \Framework\Model
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
    * @label id
    */
    protected $_iduser;
    
    /**
    * @column
    * @readwrite
    * @setter
    * @type text
    */
    protected $_email;
        
    public function setEmail($param=array())
    {
        $v_ref = trim($this->_email);
        $this->logger->debug("setEmail()",array($v_ref));
        if(!empty($v_ref) && strlen($v_ref)>5){
            $class = \App\Models\Contacts::class;
            $t_ref = array_merge(array(
                "id_ctype" => STATICS::CONTACT_TYPE_EMAIL,
                "contact" => $v_ref
            ), $param );
            $rec = new $class(array("data"=>$t_ref));
            $rec->insert();
        }
    }

    /**
    * @column
    * @readwrite
    * @setter
    * @type text
    */
    protected $_telbur;
        
    public function setTelbur($param=array())
    {
        $v_ref = trim($this->_telbur);
        $this->logger->debug("setTelbur()",array($v_ref));
        if(!empty($v_ref)){
            $class = \App\Models\Contacts::class;
            $t_ref = array_merge(array(
                "id_ctype" => STATICS::CONTACT_TYPE_PHONE,
                "contact" => $v_ref
            ), $param );
            $rec = new $class(array("data"=>$t_ref));
            $rec->insert();
        }
    }

    /**
    * @column
    * @readwrite
    * @setter
    * @type text
    */
    protected $_fax;
        
    public function setFax($param=array())
    {
        $v_ref = trim($this->_fax);
        $this->logger->debug("setFax()",array($v_ref));
        if(!empty($v_ref)){
            $class = \App\Models\Contacts::class;
            $t_ref = array_merge(array(
                "id_ctype" => STATICS::CONTACT_TYPE_FAX,
                "contact" => $v_ref
            ), $param );
            $rec = new $class(array("data"=>$t_ref));
            $rec->insert();
        }
    }

    /**
    * @column
    * @readwrite
    * @setter
    * @type text
    */
    protected $_gsm;
        
    public function setGsm($param=array())
    {
        $v_ref = trim($this->_gsm);
        $this->logger->debug("setGsm()",array($v_ref));
        if(!empty($v_ref)){
            $class = \App\Models\Contacts::class;
            $t_ref = array_merge(array(
                "id_ctype" => STATICS::CONTACT_TYPE_PHONE,
                "contact" => $v_ref
            ), $param );
            $rec = new $class(array("data"=>$t_ref));
            $rec->insert();
        }
    }

    /**
    * @column
    * @readwrite
    * @setter
    * @type text
    */
    protected $_compte;
        
    public function setCompte($param=array())
    {
        $v_ref = trim(preg_replace("#[^a-zA-Z0-9-]*#","",$this->_compte));
        $this->logger->debug("setCompte()",array($v_ref));
        if(!empty($v_ref)){
            $id_bank = 0;
            $b_ref = "000";
            if(preg_match("#^[a-zA-Z]{2,3}[0-9-]*$#",$v_ref)){
                $b_ref = substr($v_ref,4,strlen($b_ref));
            }
            if(preg_match("#^([0-9]{1,3})-[0-9-]*$#",$v_ref,$match)){
                $b_ref = substr($v_ref,0,strlen($match[1]));
            }
            //print("prefix:".$b_ref."<br>");
            if((int)$b_ref>0 && (int)$b_ref<999){
                $bank = \App\Models\Banks::first(array(
                    " ? BETWEEN `from` AND `to`" => (int)$b_ref
                ));
                if(!empty($bank)){
                    $id_bank = $bank->id;
                }
            }
            if($id_bank>0){
                $class = \App\Models\Comptes::class;
                $t_ref = array_merge(array(
                    "id_bank" => $id_bank,
                    "compte" => $v_ref
                ), $param );
                $rec = new $class(array("data"=>$t_ref));
                $rec->insert();
            }
        }
    }
    
    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_lang;
        
    public function getId_lang()
    {
        $v_ref = $this->_lang;
        $t_ref = array(
            "F" => STATICS::LANG_TYPE_FR,
            "N" => STATICS::LANG_TYPE_NL
        );
        return isset($t_ref[$v_ref]) ? $t_ref[$v_ref]:STATICS::LANG_TYPE_FR;
    }
    
    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_sex;
        
    public function getId_civil()
    {
        $v_ref = $this->_sex;
        $t_ref = array(
            "X" => STATICS::CIVIL_TYPE_NONE,
            "M" => STATICS::CIVIL_TYPE_MR,
            "F" => STATICS::CIVIL_TYPE_MM
        );
        return isset($t_ref[$v_ref]) ? $t_ref[$v_ref]:STATICS::CIVIL_TYPE_NONE;
    }
    
    /**
    * @column
    * @readwrite
    * @type integer
    */
    protected $_etatciv;
        
    public function getId_eciv()
    {
        $v_ref = $this->_etatciv;
        $t_ref = array(
            0 => STATICS::ECIV_TYPE_NC,
            1 => STATICS::ECIV_TYPE_ALONE,
            2 => STATICS::ECIV_TYPE_MARIED,
            3 => STATICS::ECIV_TYPE_SPLIT,
            4 => STATICS::ECIV_TYPE_DIV,
            5 => STATICS::ECIV_TYPE_WID
        );
        return isset($t_ref[$v_ref]) ? $t_ref[$v_ref]:STATICS::ECIV_TYPE_NC;
    }
        
    public function getId_stat()
    {
        return STATICS::STATUS_TYPE_ACTIVE;
    }
    
    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_level;
        
    public function getId_utype()
    {
        $v_ref = $this->_level;
        $t_ref = array(
            "L" => STATICS::USER_TYPE_TENANT,
            "P" => STATICS::USER_TYPE_OWNER,
            "Y" => STATICS::USER_TYPE_SYNDIC
        );
        return isset($t_ref[$v_ref]) ? $t_ref[$v_ref]:STATICS::USER_TYPE_OTHER;
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
    protected $_pnom;
    
    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_nom;
    
    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_societe;

    public function getSoc()
    {
        $value = $this->_societe;
        return $value;
    }
    
    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_adresse;

    public function getStreet()
    {
        $value = $this->_adresse;
        return $value;
    }

    public function getNum()
    {
        return 0;
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
    protected $_ville;
    
    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_pays;

    public function getId_cty()
    {
        $_id = STATICS::COUNTRY_TYPE_DEFAULT; // belgique
        $value = $this->_pays;
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
    * @type date
    */
    protected $_born;

    public function getBorn()
    {
        $value = $this->_born;
        return false===strpos($value,"0000") ? $value:null;
    }
    
    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_wborn;
    
    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_numnat;
    
    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_natio;
    
    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_rem;

    public function getComment()
    {
        $value = $this->_rem;
        return $value;
    }
    
    /**
    * @column
    * @readwrite
    * @type date
    */
    protected $_dateen;

    public function getDatein()
    {
        $value = $this->_dateen;
        return false===strpos($value,"0000") ? $value:null;
    }

    public function getDatemod()
    {
        $dt = new \DateTime("now", new \DateTimeZone("Europe/Brussels"));
        return $dt->format("Y-m-d H:i:s");
    }

}