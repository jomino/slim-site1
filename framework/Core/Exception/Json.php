<?php

namespace Framework\Core\Exception
{
    use Framework\Core as Core;
    
    class Json extends Core\Exception
    {

        public function __construct($n)
        {
            $e_json = array(
                JSON_ERROR_NONE => 'No error',
                JSON_ERROR_DEPTH => 'Maximum stack depth exceeded',
                JSON_ERROR_STATE_MISMATCH => 'State mismatch (invalid or malformed JSON)',
                JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
                JSON_ERROR_SYNTAX => 'Syntax error',
                JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded'
            );

            $message = $e_json[$n];

            parent::__construct($message,$n);

        }
        
    }
}