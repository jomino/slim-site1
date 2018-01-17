<?php

namespace Framework
{
    use Framework\Base as Base;
    use Framework\Events as Events;
    use Framework\Registry as Registry;
    use Framework\Inspector as Inspector;
    use Framework\Reflector as Reflector;
    use Framework\Model\Exception as Exception;
    use Framework\StringMethods as StringMethods;
    use Framework\ArrayMethods as ArrayMethods;
    
    class Model extends Base
    {
        
        /**
        * @readwrite
        */
        protected $_logger;

        /**
        * @read
        */
        protected $_tableName;
        
        /**
        * @read
        */
        protected $_modelClass;
        
        /**
        * @readwrite
        */
        protected $_table;
        
        /**
        * @readwrite
        */
        protected $_model;
        
        /**
        * @readwrite
        */
        protected $_connector;
        
        /**
        * @readwrite
        */
        protected $_interface;

        /**
        * @readwrite
        */
        protected $_phantom = true;

        /**
        * @readwrite
        */
        protected $_dirty = false;

        /**
        * @read
        */
        protected $_types = array(
            "autonumber",
            "text",
            "integer",
            "decimal",
            "boolean",
            "date",
            "datetime",
            "mixed"
        );
        
        /**
        * @read
        */
        protected $_alt_types = array(
            "autonumber" => array(
                "defaults" => "number"),
            "integer" => array(
                "defaults" => "number"),
            "decimal" => array(
                "defaults" => "number")
        );
        
        /**
        * @read
        */
        protected $_validators = array(
            "pattern" => array(
                "handler" => "_validatePattern",
                "message" => "The \"{0}\" field don't match the pattern"
            ),
            "required" => array(
                "handler" => "_validateRequired",
                "message" => "The \"{0}\" field is required"
            ),
            "alpha" => array(
                "handler" => "_validateAlpha",
                "message" => "The \"{0}\" field can only contain letters"
            ),
            "numeric" => array(
                "handler" => "_validateNumeric",
                "message" => "The \"{0}\" field can only contain numbers('{2}','{3}') : \"{1}\" found."
            ),
            "alphanumeric" => array(
                "handler" => "_validateAlphaNumeric",
                "message" => "The \"{0}\" field can only contain letters and numbers"
            ),
            "max" => array(
                "handler" => "_validateMax",
                "message" => "The \"{0}\" field must contain less than \"{1}\" characters"
            ),
            "min" => array(
                "handler" => "_validateMin",
                "message" => "The \"{0}\" field must contain more than \"{1}\" characters"
            ),
            "gt" => array(
                "handler" => "_validateGt",
                "message" => "The \"{0}\" field must be greater or eq. to \"{2}\" : \"{1}\" found."
            ),
            "lt" => array(
                "handler" => "_validateLt",
                "message" => "The \"{0}\" field must be lower or eq. to \"{2}\" : \"{1}\" found."
            )
        );
        
        /**
        * @readwrite
        */
        protected $_errors = array();
        
        /**
        * @readwrite
        */
        protected $_columns;
        
        /**
        * @readwrite
        */
        protected $_primary;
        
        /**
        * @readwrite
        */
        protected $_labels;

        /**
        * @readwrite
        */
        protected $_display;

        /**
        * @readwrite
        */
        protected $_raw;

        /**
        * @readwrite
        */
        protected $_map;

        /**
        * @read
        */
        protected $_withLocal = true;

        /**
        * @readwrite
        */
        protected $_formats = array();
        
        public function __construct($options = array())
        {

            $this->_logger = Registry::get("container")->logger;

            if(isset($options["data"])){
                $data = $options["data"];
                foreach($data as $k=>$v){
                    $options[$k] = $v;
                }
                unset($options["data"]);
            }

            parent::__construct($options);

            //Events::fire("framework.model.construct.before", array(get_class($this)) );
            //Events::fire("framework.model.construct.before", array(print_r((array) $options,true)) );

            if(isset($data)){
                $this->_raw = new Reflector($data);
                $this->setBelongTo();
            }else{
                $this->_raw = new Reflector();
            }

            if($this->withLocal){
                $this->loadLocales();
            }
            
            //var_dump($this);

            //Events::fire("framework.model.construct.after", array(print_r((array) $this->_raw,true)) );
        }
        
        // standard setter
        public function isDirty($field=null)
        {
            if($field && $this->_raw->count()>0){
                $column = $this->getColumn($field);
                if(!empty($column)){
                    return $this->_raw->{$column["name"]}!=$this->{$column["raw"]};
                }
            }else{
                return $this->_dirty;
            }
            return null;
        }
        
        // standard getter
        public function get($field)
        {
            $column = $this->getColumn($field);
            if(!empty($column)){
                return $this->{"_{$field}"};
            }
            return null;
        }
        
        // standard setter
        public function set($key,$value=null)
        {
            if(is_string($key)){ $values = array($key=>$value); }
            else{ $values = $key; }
            foreach($values as $k=>$v){
                $column = $this->getColumn($k);
                if(!empty($column)){
                    $old = $this->{$column["raw"]};
                    if($old!=$v){
                        $this->_raw->{$column["name"]} = $v;
                        $this->_dirty = true;
                    }
                }
            }
            return $this;
        }
        
        // standard getter
        public function getId()
        {
            $p_col = $this->primaryColumn;
            $primary = is_array($p_col) ? $p_col["name"]:"no_primary_key";
            if($this->_raw->count()>0){ return isset($this->_raw->{$primary}) ? $this->_raw->{$primary}:null; }
            else if(isset($this->{"_".$primary})){ return $this->{"_".$primary}; }
            else{ return null; }
        }
        
        // standard getter
        public function getRaw()
        {
            $raw = new \stdClass();
            $_raw = $this->_raw;
            if($_raw->count()>0){
                $columns = $this->getColumns();
                foreach ($columns as $column){
                    if(isset($_raw->{$column["name"]})){
                        $raw->{$column["name"]} = $_raw->{$column["name"]};
                    }
                }
            }
            return $raw;
        }
        
        protected function loadLocales()
        {
            
            $raw = (array) $this->raw;
            $phantom = $this->_phantom;

            $t_model = $this->getModel();
            $r_class = new \ReflectionClass($t_model);
            $model = strtolower($r_class->getShortName());
            
            $display = array();
            $labels = array();

            $localisation = Registry::get("localisation");

            if ($this->_raw->count()>0){
                
                foreach ($raw as $key=>$value)
                {
                    $format = $localisation->format($key,$model,$raw);
                    if ($format)
                    {
                        $this->_formats[$key] = $format;
                        $display[$key] = $format->value($value);
                        $labels[$key] = $format->label();
                    }
                    else{
                        $display[$key] = $value;
                        $labels[$key] = "error_no_format";
                    }
                }

                if(sizeof($display)>0){ $this->_display = new Reflector($display); }
                if(sizeof($labels)>0){ $this->_labels = new Reflector($labels); }

            }else{

                $columns = array_keys($this->columns);

                for ($i=0;$i<sizeof($columns);$i++)
                {
                    $key = $columns[$i];
                    
                    $format = $localisation->format($key,$model,null);

                    if ($format){
                        $this->_formats[$key] = $format;
                        $labels[$key] = $format->label();
                    }else{
                        $labels[$key] = "error_no_format";
                    }

                }

                if(sizeof($labels)>0){ $this->_labels = new Reflector($labels); }

            }

        }

        protected function setBelongTo()
        {

            $columns = $this->columns;
            
            //Events::fire("framework.model.belongto.before", array(get_class($this)) );

            foreach ($columns as $field => $properties){
                if(is_string($properties["belongto"])){
            
                    //Events::fire("framework.model.belongto.before", array(print_r($properties,true)) );

                    $this->_setBelongTo($field,$properties);
                }
            }

        }

        protected function _setBelongTo($name,$properties)
        {

            $raw = $this->raw;

            $map = array();
            $raw_map = array();

            if(!empty($this->_map)){ $map = $this->map; }

            $raw_prop = $properties["name"];

            $t_values = explode("::",$properties["belongto"]);

            $w_value = explode(".",$t_values[0]);

            $table = $w_value[0];

            $w_field = $w_value[1];

            $f_field = str_replace("{$table}.","",$t_values[1]);

            $r_class = new \ReflectionClass(get_class($this));

            $class = $r_class->getNamespaceName()."\\".ucfirst($table);
            
            $record = null;
            
            if(isset($raw->{$raw_prop})){
                $record = $class::first(array(
                    "{$w_field} = ?" => $raw->{$raw_prop}
                ));
            }else{
                $msg = "FOR: #{$raw_prop}# : <pre>".print_r($raw,true)."</pre>";
                //file_put_contents(__DIR__ . "/../App/_cache/debug.html",$msg);
            }

            if(!empty($record)){

                $record_raw = $record->raw;

                foreach ($record_raw as $field=>$value){
                    if($f_field!="-"){
                        if($f_field==$field){
                            $raw_map[$field] = $value;
                            if($record->withLocal){
                                $raw_map[$field."_display"] = $record->display->{$field};
                                $raw_map[$field."_label"] = $record->labels->{$field};
                            }
                        }
                    }else{
                        $raw_map[$field] = $value;
                        if($record->withLocal){
                            $raw_map[$field."_display"] = $record->display->{$field};
                            $raw_map[$field."_label"] = $record->labels->{$field};
                        }
                    }
                }

                //$raw_map["model"] = $record;

                $map[$name] = $raw_map;

            }else{
                //var_dump($this);
                //throw new Exception\Argument("{$w_field}=".$raw->{$raw_prop}." on {$class} not found");
                $raw_map = array_fill_keys(ArrayMethods::column((new $class())->columns,"name"),"");
                $map[$name] = $raw_map;
            }

            $this->_map = $map;

        }

        protected function _getBelongTo($path,$model=null)
        {

            $mixed = null;

            if(is_null($model)){ $model = $this; }

            $p_segment = explode(".",$path);

            $map = !empty($model->_map) ? $model->map:array();

            $model_map = isset($map[$p_segment[0]]) ? $map[$p_segment[0]]:array();

            if(sizeof($p_segment)>2){

                $belongto = $model->getColumn($p_segment[0])["belongto"];

                $table = explode(".",$belongto);

                $r_class = new \ReflectionClass(get_class($this));
                $class = $r_class->getNamespaceName()."\\".ucfirst($table[0]);

                $record = $class::first(array(
                    "{$p_segment[0]} = ?" => $model->raw->{$p_segment[0]}
                ));

                $mixed = $this->_getBelongTo(
                    str_replace($p_segment[0].".","",$path),
                    $record
                );
                
            }else{

                if(isset($p_segment[1])){
                    $p_segment[1] = str_replace("#","_",$p_segment[1]);
                    if( isset($model_map[$p_segment[1]]) ){
                        $mixed = $model_map[$p_segment[1]];
                    }else if( !empty($model->getRaw()->{$p_segment[1]}) ){
                        $mixed = $model->getRaw()->{$p_segment[1]};
                    }else{
                        $mixed = (array) $model->getRaw();
                    }
                }

            }

            return $mixed;

        }
    
        public function getBelongTo($path){

            if(strpos($path,".")!==false){
                return $this->_getBelongTo($path);
            }else{
                $map = !empty($this->_map) ? $this->_map:array();
                return isset($map[$path]) ? $map[$path]:$map;
            }
            
        }

        public function delete($where=array())
        {

            if(sizeof($where)>0){
                $where_clause = $where;
            }else{
                $where_clause = $this->_getWhereClause();
            }
                
            if(is_array($where_clause))
            {
                
                $query = $this->connector
                    ->query()
                    ->from($this->table);

                foreach ($where_clause as $clause => $value)
                {
                    $query->where($clause, $value);
                }

                $this->_phantom = true;

                return $query->delete();

            }

            return false;

        }
        
        public static function deleteAll($where=array())
        {
            $error = false;
            
            $instancies = static::all($where);

            if(!empty($instancies)){
                for($i=0;$i<sizeof($instancies);$i++){
                    $instance = $instancies[$i];
                    $error |= !$instance->delete();
                }
            }

            return !$error;

        }
        
        public function update()
        {

            if($this->_raw->count()==0){ return; }
            
            $data = (array) $this->raw;

            $query = $this->connector
                ->query()
                ->from($this->table);

            $primaries = $this->_getPrimaryKeys();

            for($i=0;$i<sizeof($primaries);$i++)
            {
                $column = $primaries[$i];
                unset($data[$column["name"]]);
            }
            
            $where_clause = $this->_getWhereClause();

            if(is_array($where_clause))
            {
                foreach ($where_clause as $clause => $value)
                {
                    $query->where($clause, $value);
                }

                //var_dump($this);

                $result = $query->save($data);

                $this->_commit();

                return true;

            }
            
            return false;
        }

        protected function _commit()
        {
            $this->_dirty = false;
            foreach($this->columns as $key=>$column){
                $this->{$column["raw"]} = $this->_raw->{$column["name"]};
            }
        }
        
        public function insert()
        {

            if($this->_raw->count()==0){ return; }
            
            $data = (array) $this->raw;

            $primary = $this->primaryColumn;

            if(isset($primary["name"]) && empty($data[$primary["name"]])){
                $data[$primary["name"]] = null;
            }

            $query = $this->connector
                ->query()
                ->from($this->table);
            
            $result = $query->save($data);

            if($result>0){
                if($this->getPrimaryColumn()){
                    $column = $this->primaryColumn;
                    $name = $column["name"];
                    $field = $column["raw"];
                    $this->{$field} = $result;
                    $this->_raw->{$name} = $result;
                }else if($column=$this->secondaryColumn){
                    $name = $column["name"];
                    $field = $column["raw"];
                    $this->{$field} = $result;
                    $this->_raw->{$name} = $result;
                }else{
                    $this->_raw->insertId = $result;
                }
            }
            
            return $result;
        }

        public static function getPrimaryKeys()
        {
            $instance = new static();
            return $instance->_getPrimaryKeys();
        }

        public function getWhereClause()
        {
            return $this->_getWhereClause();
        }
        
        protected function _getWhereClause()
        {

            $primaries = $this->_getPrimaryKeys();

            if(sizeof($primaries)>0){

                $raw = $this->raw;

                $where = array();

                for($i=0;$i<sizeof($primaries);$i++)
                {
                    $column = $primaries[$i];
                    $name = $column["name"];
                    $where["{$name} = ?"] = $raw->{$name};
                }

                if(sizeof($where)>0){
                    return $where;
                }

            }

            return;

        }

        protected function _getPrimaryKeys()
        {
            $primary = $this->primaryColumn;
            
            if ($primary)
            {
                return array($primary);
            }

            $primaries = $this->indexColumns;

            if(sizeof($primaries)>0)
            {
                return $primaries;
            }

            return;

        }

        public function getModel()
        {
            if (empty($this->_model))
            {
                if (empty($this->_modelClass))
                {
                    $this->_model = get_class($this);
                    
                }
                else
                {
                    $this->_model = $this->_modelClass;
                }
            }

            return $this->_model;

        }

        public function getTable()
        {
            if (empty($this->_table))
            {
                if (empty($this->_tableName))
                {
                    $t_model = $this->getModel();
                    $r_class = new \ReflectionClass($t_model);
                    $this->_table = strtolower($r_class->getShortName());
                }
                else
                {
                    $this->_table = $this->_tableName;
                }
            }

            $configuration = Registry::get("configuration");
            if ($configuration)
            {
                $configuration = $configuration->initialize();
                $parsed = $configuration->parse("database");
                if (!empty($parsed->database->default))
                {
                    $database_default = $parsed->database->default;
                    $production = isset($database_default->production) ? $database_default->production:"0";
                    if ($production=="-1")
                    {
                        $this->_table .= isset($database_default->extention) ? $database_default->extention:"";
                    }
                    $prefix = isset($database_default->prefix) ? $database_default->prefix:"";
                    if($prefix!="")
                    {
                        $this->_table = $prefix.$this->_table;
                    }
                }
            }
                        
            return $this->_table;
        }
        
        public function getConnector()
        {
            if (empty($this->_connector))
            {
                $database = Registry::get("database");
                
                if (!$database)
                {
                    throw new Exception\Connector("No connector !");
                }
                
                $this->_connector = $database->initialize();
            }

            if (!$this->_connector->isConnected){
                $this->_connector->connect();
            }
            
            return $this->_connector;
        }
        
        public function getColumns()
        {
            if (empty($this->_columns))
            {
                $primaries = 0;
                $columns = array();
                //$class = get_class($this);
                $types = $this->types;
                
                $inspector = new Inspector($this);
                $properties = $inspector->getClassProperties();
                
                $first = function($array, $key)
                {
                    if (isset($array[$key]) && sizeof($array[$key]))
                    {
                        return $array[$key][0];
                    }
                    return null;
                };
                
                foreach ($properties as $property)
                {
                    $propertyMeta = $inspector->getPropertyMeta($property);
                    
                    if (!empty($propertyMeta["@column"]))
                    {

                        $name = preg_replace("#^_#", "", $property);
                        $type = $first($propertyMeta, "@type");
                        $alt_type = isset($this->_alt_types[$type]) ? $this->_alt_types[$type]:"";
                        $length = $first($propertyMeta, "@length");
                        $validate = !empty($propertyMeta["@validate"]) ? $propertyMeta["@validate"] : false;
                        $label = $first($propertyMeta, "@label");
                        $belongto = $first($propertyMeta, "@belongto");

                        $index = (boolean) !empty($propertyMeta["@index"]);
                        $readwrite = (boolean) !empty($propertyMeta["@readwrite"]);
                        $read = (boolean) !empty($propertyMeta["@read"]) || $readwrite;
                        $write = (boolean) !empty($propertyMeta["@write"]) || $readwrite;
                        $primary = (boolean) !empty($propertyMeta["@primary"]);
                        $secondary = (boolean) !empty($propertyMeta["@secondary"]);
                        $setter = (boolean) !empty($propertyMeta["@setter"]);
                        
                        if (!in_array($type, $types))
                        {
                            throw new Exception\Type("{$type} is not a valid type");
                        }

                        $columns[$name] = array(
                            "raw" => $property,
                            "name" => $name,
                            "primary" => $primary,
                            "secondary" => $secondary,
                            "type" => $type,
                            "setter" => $setter,
                            "length" => $length,
                            "index" => $index,
                            "read" => $read,
                            "alt" => $alt_type,
                            "write" => $write,
                            "validate" => $validate,
                            "label" => $label,
                            "belongto" => $belongto
                        );
                    }
                }
                
                $this->_columns = $columns;

            }
            
            return $this->_columns;
        }
        
        public function getColumn($name)
        {
            if (!empty($this->columns[$name]))
            {
                return $this->_columns[$name];
            }
            return null;
        }
        
        public function getPrimaryColumn()
        {
            if (!isset($this->_primary))
            {
                $primary;
                
                foreach ($this->columns as $column)
                {
                    if ($column["primary"])
                    {
                        $primary = $column;
                        break;
                    }
                }
                
                $this->_primary = !empty($primary) ? $primary:null;
            }
            
            return $this->_primary;
        }
        
        public function getSecondaryColumn()
        {
            $secondary = array();
            
            foreach ($this->columns as $column)
            {
                if ($column["secondary"])
                {
                    $secondary = $column;
                    break;
                }
            }
        
            return $secondary;
        }
        
        public function getIndexedColumns()
        {
            
            $columns = array();

            foreach ($this->columns as $column)
            {
                if ($column["index"])
                {
                    $columns[] = $column;
                }
            }

            return $columns;
            
        }
        
        public static function getColumnsInfos()
        {
            $model = new static();
            return $model->columns;
        }
        
        public function getFormats()
        {
            if (sizeof($this->_formats)>0)
            {
                return $this->_formats;
            }
            return null;
        }
        
        public function getFormat($name)
        {
            $formats = $this->_formats;
            if (sizeof($formats)>0 && isset($formats[$name]))
            {
                return $formats[$name];
            }
            return null;
        }
        
        public function getLabels()
        {
            if (!empty($this->_labels))
            {
                return $this->_labels;
            }
            return null;
        }
        
        public function getLabel($name=null)
        {
            if($name){
                $labels = (array) $this->_labels;
                if (!empty($labels)){
                    return isset($labels[$name]) ? $labels[$name]:null;
                }
            }
            return !empty($this->_labels) ? $this->_labels:null;
        }
        
        public function setLabel($key,$value)
        {
            $labels = (array) $this->_labels;
            if (is_array($key))
            {
                foreach($key as $k=>$v){
                    $labels[$k] = $v;
                }
            }
            if (is_string($key))
            {
                $labels[$key] = $value;
            }
            $this->_labels = (object) $labels;
        }
        
        public static function first($where = array(), $fields = array("*"), $order = null, $direction = null)
        {
            $model = new static();
            return $model->_first($where, $fields, $order, $direction);
        }
        
        protected function _first($where = array(), $fields = array("*"), $order = null, $direction = null)
        {
                        
            $query = $this
                ->connector
                ->query()
                ->from($this->table, $fields);

            foreach ($where as $clause => $value)
            {
                $query->where($clause, $value);
            }
            
            if ($order != null)
            {
                $query->order($order, $direction);
            }

            $first = $query->first();
            $class = $this->getModel();
            
            if ($first)
            {
                $instance = new $class(array("data"=>$first));
                $instance->phantom = false;
                //$logger = Registry::get("logger");
                //if(isset($logger)){ $logger->log(print_r($instance,true)); }
                return $instance;
            }
            
            return null;
        }
        
        public function getAll($where = array(), $fields = array("*"), $order = null, $direction = null, $limit = null, $offset = null)
        {
            return $this->_all($where, $fields, $order, $direction, $limit, $offset);
        }
        
        public static function all($where = array(), $fields = array("*"), $order = null, $direction = null, $limit = null, $offset = null)
        {
            $model = new static();
            return $model->_all($where, $fields, $order, $direction, $limit, $offset);
        }
        
        protected function _all($where = array(), $fields = array("*"), $order = null, $direction = null, $limit = null, $offset = null)
        {
            $query = $this
                ->connector
                ->query()
                ->from($this->table, $fields);
            
            foreach ($where as $clause => $value)
            {
                $query->where($clause, $value);
            }
            
            if ($order != null)
            {
                $query->order($order, $direction);
            }
            
            if ($limit != null)
            {
                $query->limit($limit, $offset);
            }
            
            $rows = array();
            $class = $this->getModel();
            
            foreach ($query->all() as $row)
            {
                $rows[] = new $class(array(
                    "phantom" => false,
                    "data"=>$row
                ));
            }
            
            return $rows;
        }
        
        public static function search($filters = array(), $where = array(), $fields = array("*"), $order = null, $direction = null, $limit = null, $offset = null)
        {
            $model = new static();
            return $model->_search($filters, $where, $fields, $order, $direction, $limit, $offset);
        }
        
        protected function _search($filters, $where, $fields, $order, $direction, $limit, $offset)
        {
            $query = $this
                ->connector
                ->query()
                ->from($this->table, $fields);
            
            if (sizeof($filters)>0)
            {
                $query->like($filters);
            }
            
            foreach ($where as $clause => $value)
            {
                $query->where($clause, $value);
            }
            
            if ($order != null)
            {
                $query->order($order, $direction);
            }
            
            if ($limit != null)
            {
                $query->limit($limit, $offset);
            }
            
            $rows = array();
            $class = $this->getModel();
            
            foreach ($query->all() as $row)
            {
                $rows[] = new $class(array(
                    "phantom" => false,
                    "data"=>$row
                ));
            }
            
            return $rows;
        }
        
        public static function count($where=array(),$filters=array())
        {
            $model = new static();
            return $model->_count($where,$filters);
        }
        
        protected function _count($where=array(),$filters=array())
        {
            $query = $this
                ->connector
                ->query()
                ->from($this->table);
            
            if (sizeof($filters)>0)
            {
                $query->like($filters);
            }
            
            foreach ($where as $clause => $value)
            {
                $query->where($clause, $value);
            }
            
            return $query->count();
        }
        
        public static function batch($options)
        {
            $model = new static();
            return $model->_batch($options);
        }
        
        protected function _batch($options)
        {
            $class = $this->getModel();

            $options["model"] = $class;

            $batch = new \Framework\Model\Batch($options);

            $query = $batch->query;
            
            //$logger = Registry::get("logger");
            if(isset($logger)){ $logger->log($query); }
            
            $result = $this->connector->execute($query);
        
            $rows = array();
            
            for ($i = 0; $i < $result->num_rows; $i++)
            {
                $row = $result->fetch_array(MYSQLI_ASSOC);
                $rows[] = new $class(array(
                    "phantom" => false,
                    "data"=>$row
                ));
            }
            
            return $rows;
        }
        
        public function syncWith($class,$options)
        {
            return $this->_sync($class,$options);
        }
        
        public static function sync($class,$options)
        {
            $model = new static();
            return $model->_sync($class,$options);
        }

        protected function _sync($class,$options=array())
        {

            $prim_id = 0;
            $where = array();

            $model_abs = is_string($class) ? new $class():$class;

            if(empty($model_abs))
            {
                throw $this->_getExceptionForImplementation($this->getModel()."::sync");
            }
            
            $model_query = $model_abs->connector->query();

            if(!empty($options["where"]))
            {
                $where = $options["where"];
                foreach ($where as $clause => $value)
                {
                    $model_query->where($clause, $value);
                }
            }

            if(!empty($options["limit"]))
            {
                $limit = $options["limit"];
                $model_query->limit($limit["max"], $limit["offset"]);
            }

            //var_dump($model_query);

            $result = $model_query->all();
            $response = array();

            if(sizeof($result)>0)
            {
                $_class = get_class($model_abs);

                for($i=0;$i<sizeof($result);$i++)
                {
                    $_row = new $_class(array("data"=>(array)$result[$i]));
                    $model_raw = array();
                    foreach($this->columns as $field=>$props)
                    {
                        $model_raw["{$field}"] = $_row->{$field};
                    }
                    if(sizeof($model_raw)>0)
                    {
                        //var_dump($model_raw);
                        $record = $response[] = new static(array("data"=>$model_raw));
                        $ref_field = $record->getSecondaryColumn();
                        if(!empty($ref_field)){
                            //delete previus record by ref
                            $n_field = $ref_field['name'];
                            $is_previus = self::first(array(
                                "{$n_field} = ?" => $record->{$ref_field}
                            ));
                            if(!empty($is_previus)){
                                $is_previus->delete();
                            }
                        }
                        if($record->validate()){
                            //insert the new record
                            $insertId = array(
                                $this->getPrimaryColumn()["name"] => $record->insert()
                            );
                            //set the dependent records's values
                            $cols = $_class::getColumnsInfos();
                            foreach($cols as $col=>$prop)
                            {
                                if($prop["setter"])
                                {
                                    $method = "set".ucfirst($prop["name"]);
                                    $this->logger->debug("{$method}()",$insertId);
                                    if(method_exists($_row,$method))
                                    {
                                        $_row->$method($insertId);
                                        //call_user_func_array(array($_row,$method),array($insertId));
                                    }
                                }
                            }
                        }
                    }
                }
            }

            return $response;

        }
        
        public function assert($where)
        {
            return !is_null($this->_first($where));
        }

        protected function _validatePattern($value,$pattern)
        {
            $match = StringMethods::match($value, $pattern);
            return sizeof($match)>0;
        }
        
        protected function _validateRequired($value)
        {
            return !empty($value) || $value===0;
        }
        
        protected function _validateAlpha($value)
        {
            return StringMethods::match($value, "#^([a-zA-Z]+)$#");
        }
        
        protected function _validateNumeric($value,$digit=0,$float=0)
        {
            if(!empty($digit)){
                if(!empty($float)){ return StringMethods::match($value, "#^(-?[0-9]{1,".$digit."})\.([0-9]{".$float.",".$float."})$#"); }
                else{ return StringMethods::match($value, "#^(-?[0-9]{1,".$digit."})$#"); }
            }else{
                return StringMethods::match($value, "#^(-?[0-9]+)$#");
            }
        }
        
        protected function _validateAlphaNumeric($value)
        {
            return StringMethods::match($value, "#^([a-zA-Z0-9]+)$#");
        }
        
        protected function _validateMax($value, $number)
        {
            return strlen($value) <= (int) $number;
        }
        
        protected function _validateMin($value, $number)
        {
            return strlen($value) >= (int) $number;
        }
        
        protected function _validateGt($value, $number)
        {
            return (int) $value >= (int) $number;
        }
        
        protected function _validateLt($value, $number)
        {
            return (int) $value <= (int) $number;
        }
        
        public function validate()
        {
            $this->_errors = array();
            
            foreach ($this->columns as $column)
            {
                if ($column["validate"])
                {
                    $pattern = "#[a-z]+\(([a-zA-Z0-9-\[\]\^$+,. ]+)\)#";
                    
                    $raw = $column["raw"];
                    $name = $column["name"];
                    $validators = $column["validate"];
                    $label = $column["label"];
                    
                    $defined = $this->getValidators();
                    
                    foreach ($validators as $validator)
                    {
                        $function = $validator;
                        $arguments = array(
                            $this->getRaw()->{$name}
                        );
                        
                        $match = StringMethods::match($validator, $pattern);
                        
                        if (count($match) > 0)
                        {
                            $matches = StringMethods::split($match[0], ",\s*");
                            $arguments = array_merge($arguments, $matches);
                            $offset = StringMethods::indexOf($validator, "(");
                            $function = substr($validator, 0, $offset);
                        }
                        
                        if (!isset($defined[$function]))
                        {
                            throw new Exception\Validation("The {$function} validator is not defined");
                        }
                        
                        $template = $defined[$function];
                        
                        if (!call_user_func_array(array($this, $template["handler"]), $arguments))
                        {
                            
                            $replacements = array_merge(array(
                                $label ? "{$label}" : "{$raw}"
                            ), $arguments);
                            
                            $message = $template["message"];
                            
                            foreach ($replacements as $i => $replacement)
                            {
                                $message = str_replace("{{$i}}", $replacement, $message);
                            }
                            
                            if (!isset($this->_errors[$name]))
                            {
                                $this->_errors[$name] = array();
                            }
                            
                            $this->_errors[$name][] = $message;
                            //var_dump($this->_errors);
                        }
                    }
                }
            }
            return !sizeof($this->errors);
        }

        public function _getExceptionForImplementation($method)
        {
            return new Exception\Implementation("{$method} method not implemented");
        }
        
    }    
}