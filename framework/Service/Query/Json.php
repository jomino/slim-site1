<?php

namespace Framework\Service\Query
{
    use Framework\Service as Service;
    use Framework\Registry as Registry;
    use Framework\Service\Exception as Exception;
    
    class Json extends Service\Query
    {
        
        protected function _buildRequest()
        {

            $request = $this->getConnector()->getInterface()->request;

            //var_dump($request);

            if(empty($request)){
                //var_dump($this->getConnector());
                return;
            }

            $interface = new $request([
                "query" => $this
            ]);

            //var_dump($interface);

            return $interface->getRequest();
        }
        
        protected function _buildResponse($payload)
        {

            //var_dump($this);

            $response = $this->getConnector()->getInterface()->response;

            if(empty($response)){
                //var_dump($this->getConnector());
                return;
            }

            $interface = new $response([
                "payload" => $payload
            ]);

            //var_dump($interface);

            /*print("<pre>");
            print_r($interface);
            print("</pre>");*/

            return $interface->getResponse();

        }
        
        public function all()
        {            
            //var_dump($this);

            if(empty($this->getConnector())){ return; }

            $datas = array();
            
            $request = $this->_buildRequest();

            /*print("<pre>");
            print($request);
            print("</pre>");*/

            if(!empty($request)){
                $datas = $this->_buildResponse(
                    $this->getConnector()->execute($request)
                );
            }

            /*if ($datas===false){
                var_dump($this->getConnector());
            }*/

            //var_dump($datas);

            return $datas;
        }
        
    }

}