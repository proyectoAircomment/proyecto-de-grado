<?php

    require('database.php');
    class Mensajes
    {
        public $database;
        
        public function __construct()
        {
            $this->database = new Database();
            $this->contestarSms($this->recibirSmsReport());
        }
        
        function recibirSmsReport()
        {
            $url_log = 'https://api.infobip.com/sms/1/inbox/logs';
    
            $ch = curl_init($url_log);  
        
            curl_setopt($ch, CURLOPT_HTTPHEADER, 
                        array
                            (
                                'Authorization: Basic Y2xhanBzMG46UGFzc3cwcmQ=',
                                'Content-Type: application/json',  
                                'Accept: application/json'
                            )                                                                       
                        );
            
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
            $result = curl_exec($ch);

            $data = json_decode($result,true);
            
            curl_close($ch);
            
            return $data['results'];
        }
        
        
        function enviarSms($to,$from,$message)
        {
            $url = 'https://api.infobip.com/sms/1/text/single';
            $data = array("from" => $from, "to" => $to,"text" => $message);                                                                    
            $data_string = json_encode($data);  
            
            $ch = curl_init($url);                                                                      
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Basic Y2xhanBzMG46UGFzc3cwcmQ=',
                'Content-Type: application/json',  
                'Accept: application/json',
                'Content-Length: ' . strlen($data_string))                                                                       
            );                                                                                                                   
                                                                                                                                 
            $result = curl_exec($ch);
            
            if($result)
            {
                error_log($result);
            }
            
            curl_close($ch);
        }
        
        
        function contestarSms($smsArr)
        {
            if($smsArr == null)
            {
                echo 'datos nulos';
            }else
            {
                for($i = 0; i< strlen($smsArr); $i++)
                {
                    $to = $smsArr[$i]['to'];
                    $from = $smsArr[$i]['from'];
                    $message = $smsArr[$i]['text'];
                    $hour = $smsArr[$i]['receivedAt'];
                    
                    $database->guardar($to,$from,$message,$hour);
                    enviarSms($from,$to,$message);
                }
                
            }
    
            error_log($result.'se esta ejecutando la peticion.');
        }

    }
?>