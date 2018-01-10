<?php

namespace Framework
{
    class DateMethods
    {
        private function __construct()
        {
            // do nothing
        }
        
        private function __clone()
        {
            // do nothing
        }
        
        public static function now($locale="en",$full=false)
        {
            $dt = new \DateTime("now", new \DateTimeZone("Europe/Brussels"));
            switch(true){
                case $locale=="fr" || $locale=="nl":
                    $pattern = "d/m/Y";
                break;
                default:
                    $pattern = "Y-m-d";
            }
            $pattern .= $full ? " H:i:s":"";
            return $dt->format($pattern);
        }
        
    }

}