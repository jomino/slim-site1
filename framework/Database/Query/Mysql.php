<?php

namespace Framework\Database\Query
{
    use Framework\Database as Database;
    use Framework\Database\Exception as Exception;
    use Framework\Registry as Registry;
    
    class Mysql extends Database\Query
    {
        
        protected function _buildSelect()
        {
            $fields = array();
            $where = $order = $limit = $join = "";
            $template = "SELECT %s FROM %s %s %s %s %s";
            
            foreach ($this->fields as $table => $_fields)
            {
                foreach ($_fields as $field => $alias)
                {
                    if (is_string($field))
                    {
                        $fields[] = "{$field} AS {$alias}";
                    }
                    else
                    {
                        $fields[] = $alias;
                    }
                }
            }
            
            $fields = join(", ", $fields);
            
            $_join = $this->join;
            if (!empty($_join))
            {
                $join = join(" ", $_join);
            }
            
            $_where = $this->where;
            if (!empty($_where))
            {
                /*print("<pre>");
                print_r($_where);
                print("</pre>");*/
                $joined = join(" AND ", $_where);
                $where = "WHERE {$joined}";
            }
            
            $_order = $this->order;
            if (!empty($_order))
            {
                $_direction = $this->direction;
                $order = "ORDER BY {$_order} {$_direction}";
            }
            
            $_limit = $this->limit;
            if (!empty($_limit))
            {
                $_offset = $this->offset;
                
                if ($_offset)
                {
                    $limit = "LIMIT {$_offset}, {$_limit}";
                }
                else
                {
                    $limit = "LIMIT {$_limit}";
                }
            }

            $sql = sprintf($template, $fields, $this->from, $join, $where, $order, $limit);

            return $sql;
        }
        
        protected function _buildInsert($data)
        {
            $fields = array();
            $values = array();
            $template = "INSERT INTO `%s` (`%s`) VALUES (%s)";
            
            foreach ($data as $field => $value)
            {
                $fields[] = $field;
                $values[] = $this->_quote($value);
            }
            
            $fields = join("`, `", $fields);
            $values = join(", ", $values);
            
            return sprintf($template, $this->from, $fields, $values);
        }
        
        protected function _buildUpdate($data)
        {
            $parts = array();
            $where = $limit = "";
            $template = "UPDATE %s SET %s %s %s;";
            
            foreach ($data as $field => $value)
            {
                $parts[] = "{$field} = ".$this->_quote($value);
            }
            
            $parts = join(", ", $parts);
            
            $_where = $this->where;
            if (!empty($_where))
            {
                $joined = join(" AND ", $_where);
                $where = "WHERE {$joined}";
            }
            
            $_limit = $this->limit;
            if (!empty($_limit))
            {
                $_offset = $this->offset;
                $limit = "LIMIT {$_limit} {$_offset}";
            }
            
            return sprintf($template, $this->from, $parts, $where, $limit);
        }
        
        protected function _buildDelete()
        {
            $where = $limit ="";
            $template = "DELETE FROM %s %s %s";
            
            $_where = $this->where;
            if (!empty($_where))
            {
                $joined = join(" AND ", $_where);
                $where = "WHERE {$joined}";
            }
            
            $_limit = $this->limit;
            if (!empty($_limit))
            {
                $_offset = $this->offset;
                $limit = "LIMIT {$_limit} {$_offset}";
            }
            
            return sprintf($template, $this->from, $where, $limit);
        }
        
        public function all()
        {
            $sql = $this->_buildSelect();
            $result = $this->connector->execute($sql);

            // $logger = Registry::get("logger");
            //if(isset($logger)){ $logger->log($sql); }

            if ($result === false)
            {
                $error = $this->connector->lastError;
                //Registry::get('container')->get('logger')->debug($this->getClass(),["request"=>$sql,"result"=>$this->connector]);
                throw new Exception\Sql("There was an error with your SQL query: {$error},\n$sql");
            }
            
            $rows = array();
            
            for ($i = 0; $i < $result->num_rows; $i++)
            {
                $rows[] = $result->fetch_array(MYSQLI_ASSOC);
            }

            if(isset($logger)){ $logger->log(print_r($rows,true)); }

            return $rows;
        }
    }
}