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
            $dt = new \DateTime($full ? "now":"today", new \DateTimeZone("Europe/Brussels"));
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
        
        public static function format($date=null,$pattern=null)
        {
            if(is_null($date) || is_null($pattern)){
                return "NaD";
            }else{
                $dt = new \DateTime($date, new \DateTimeZone("Europe/Brussels"));
                return $dt->format($pattern);
            }
        }
        
        public static function compare($date1=null,$date2=null,$strict=false)
        {
            if(is_null($date1)){
                return null;
            }else{
                if(is_null($date2)){ $date2 = static::now("en",$strict); }
                $dt1 = new \DateTime($date1, $tz);
                $dt2 = new \DateTime($date2, $tz);
                $interval = $dt1->diff($dt2);
                $equality = $interval->days==0;
                if($strict){ $equality = $equality && ($interval->h==0 && $interval->i==0 && $interval->s==0); }
                return $equality;
            }
        }
        
    }

}