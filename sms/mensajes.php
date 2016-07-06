<?php

    require('database.php');
    require('../web-crawler/web_crawler.php');
    class Mensajes
    {
        public $database, $reportDecoder, $crawler;
        private $metarMsj;
        
        public function __construct()
        {
            $this->database = new Database();
            $this->crawler = new Crawler();
            $this->recibirSmsReport();
        }
        
        function recibirSmsReport()
        {
            $url_report = 'https://api.infobip.com/sms/1/reports';
    
            $ch = curl_init($url_report);  
        
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
            
            $smsArr = $data['results'];
            
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
                    
                    //guardado de mensaje recibido en db
                    $database->guardar($to,$from,$message,$hour);
                    
                    //con el mensaje recibido se consulta en el webcarwler 
                    //y se responde en funcion de lo solicitado
                    
                    //se asigna el texto del mensaje al constructor q la clase ReportDecoder toma
                    //como parámetro de contenido del mensaje (cabecera y contenido)
                    $this->reportDecoder = new ReportDecoder($message);
                    //si el tipo de reporte es METAR...
                    if($this->reportDecoder->getTipoReporte() == 'WXRQ')
                    {
                        //se ejecuta la función de conocer el metar de alguna ciudad, cuál?
                        //la ciudad que indicaba el mensaje. Esta se le pasa como parámetro
                        //obtenido de la función getCiudadDeReporte de la clase ReportDecoder
                        $this->metarMsj = $this->crawler->conocerMetarDe($this->reportDecoder->getCiudadDeReporte());
                        enviarSms($from,"InfoSMS",$metarMsj);
                    }
                    
                }
                
            }
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
        
        
        

        
    }
?>