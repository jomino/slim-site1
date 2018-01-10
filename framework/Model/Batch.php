<?php

namespace Framework\Model
{
    use Framework\Base as Base;
    use Framework\Template as Template;
    
    class Batch extends Base
    {
        /**
        * @read
        */
        protected $_templates = "application/libraries/batch";

        /**
        * @read
        */
        protected $_ext = "twig";

        /**
        * @readwrite
        */
        protected $_model;

        /**
        * @readwrite
        */
        protected $_query;

        /**
        * @readwrite
        */
        protected $_params;
        
        public function __construct($options = array())
        {
            parent::__construct($options);
            $this->_init();
        }
        
        protected function _init()
        {
            $tpl = new Template(array(
                "templates" => $this->_templates
            ));
            
            $path = $this->getPath();

            $data = $this->getData();

            $this->_query = $tpl->parse($path)->process($data);

        }

        protected function getPath()
        {
            $path = strtolower($this->model).".".$this->ext;
            return($path);
        }
        
        protected function getData()
        {
            $data = array(
                "datas" => $this->params
            );
            return($data);
        }

    }
}