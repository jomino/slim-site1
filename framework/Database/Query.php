<?php

namespace Framework\Database
{
    use Framework\Base as Base;
    use Framework\Registry as Registry;
    use Framework\ArrayMethods as ArrayMethods;
    use Framework\Database\Exception as Exception;
    
    class Query extends Base
    {
        /**
        * @readwrite
        */
        protected $_connector;
        
        /**
        * @read
        */
        protected $_from;
        
        /**
        * @read
        */
        protected $_fields;
        
        /**
        * @read
        */
        protected $_limit;
        
        /**
        * @read
        */
        protected $_offset;
        
        /**
        * @read
        */
        protected $_order;
        
        /**
        * @read
        */
        protected $_direction;
        
        /**
        * @read
        */
        protected $_join = array();
        
        /**
        * @read
        */
        protected $_where = array();
        
        protected function _getExceptionForImplementation($method)
        {
            return new Exception\Implementation("{$method} method not implemented");
        }
                    
        protected function _quote($value)
        {
            if (is_string($value))
            {
                $escaped = $this->connector->escape($value);
                return "'{$escaped}'";
            }
            
            if (is_array($value))
            {
                $buffer = array();
                
                foreach ($value as $i)
                {
                    array_push($buffer, $this->_quote($i));
                }
        
                $buffer = join(", ", $buffer);
                return "({$buffer})";
            }
            
            if (is_null($value))
            {
                return "NULL";
            }
            
            if (is_bool($value))
            {
                return (int) $value;
            }
            
            return $this->connector->escape($value);
        }
        
        public function save($data)
        {
            $isInsert = sizeof($this->_where) == 0;
        
            if ($isInsert)
            {
                $sql = $this->_buildInsert($data);
            }
            else
            {
                $sql = $this->_buildUpdate($data);
            }
            
            /*print("<pre>sql:\n");
            print($sql);
            print("</pre>");*/

            $result = $this->connector->execute($sql);

            //var_dump($this->connector);

            if ($result === false)
            {
                $errors = $this->connector->error.PHP_EOL.$sql;
                throw new Exception\Sql($errors);
            }
            
            if ($isInsert)
            {
                return $this->connector->lastInsertId;
            }
            
            return 0;
        }
        
        public function delete()
        {
            $sql = $this->_buildDelete();
            $result = $this->connector->execute($sql);
            
            if ($result === false)
            {
                throw new Exception\Sql();
            }
            
            return $this->connector->affectedRows;
        }
        
        public function from($from, $fields = array("*"))
        {
            if (empty($from))
            {
                throw new Exception\Argument("Invalid argument");
            }
            
            $this->_from = $from;
            
            if ($fields)
            {
                $this->_fields[$from] = $fields;
            }
            
            return $this;
        }
        
        public function join($join, $on, $fields = array())
        {
            if (empty($join))
            {
                throw new Exception\Argument("Invalid argument");
            }
            
            if (empty($on))
            {
                throw new Exception\Argument("Invalid argument");
            }
            
            $this->_fields += array($join => $fields);
            $this->_join[] = "JOIN {$join} ON {$on}";
            
            return $this;
        }
        
        public function limit($limit, $offset = 0)
        {
            if (empty($limit))
            {
                throw new Exception\Argument("Invalid argument");
            }

            $this->_limit = $limit;
            $this->_offset = $offset;
            
            return $this;
        }
        
        public function order($order, $direction = "asc")
        {
            if (empty($order))
            {
                throw new Exception\Argument("Invalid argument");
            }
            
            $this->_order = $order;
            $this->_direction = $direction;
            
            return $this;
        }
        
        public function where()
        {
            $arguments = func_get_args();
            
            if (sizeof($arguments) < 1)
            {
                throw new Exception\Argument("Invalid argument");
            }
            
            $arguments[0] = preg_replace("#\?#", "%s", $arguments[0]);
            
            foreach (array_slice($arguments, 1, null, true) as $i => $parameter)
            {
                $arguments[$i] = $this->_quote($arguments[$i]);
            }
            
            $this->_where[] = call_user_func_array("sprintf", $arguments);
            
            return $this;
        }
        
        public function like($filters=array())
        {
            $filteringRules = array();
            
            if (sizeof($filters) < 1)
            {
                throw new Exception\Argument("Invalid argument");
            }
            
            for ( $i=0 ; $i<sizeof($filters); $i++ ) {
                $filteringRules[] = "`".$filters[$i]["column"]."` LIKE ".$this->_quote("%".$filters[$i]["search"]."%");
            }

            if (!empty($filteringRules)) {
                $this->_where[] = '('.implode(" OR ", $filteringRules).')';
            }
                    
            return $this;
        }
        
        public function first()
        {
            $limit = $this->_limit;
            $offset = $this->_offset;
            
            $this->limit(1);
            
            $all = $this->all();
            $first = ArrayMethods::first($all);
        
            if ($limit)
            {
                $this->_limit = $limit;
            }
            if ($offset)
            {
                $this->_offset = $offset;
            }
            
            return $first;
        }
        
        public function count()
        {
            $limit = $this->limit;
            $offset = $this->offset;
            $fields = $this->fields;
            
            $this->_fields = array($this->from => array("COUNT(1)" => "rows"));
            
            $this->limit(1);
            $row = $this->first();
            
            $this->_fields = $fields;
            
            if ($fields)
            {
                $this->_fields = $fields;
            }
            if ($limit)
            {
                $this->_limit = $limit;
            }
            if ($offset)
            {
                $this->_offset = $offset;
            }
            
            return $row["rows"];
        }
    }
}