<?php


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
            //url del servidor de reportes sms
            $url_report = 'https://api.infobip.com/sms/1/inbox/logs';
            
            //variable curl
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
            
            //resultado en formato JSON de la petición
            $result = curl_exec($ch);
            //resultado en forma de arry asociativo
            $data = json_decode($result,true);
            //cierre de la ejecución curl
            curl_close($ch);
            //acceso a los valores del array 'results' dentro de 'data'
            $smsArr = $data['results'];
            //si el array es vacío, no se recibió novedad en el reporte
            //esto quiere decir que no se ha enviado un nuevo mensaje
            if($smsArr == null)
            {
                //
                echo '<hr>datos nulos<hr>';
            }else
            {
                echo '<hr>hay al menos un mensaje <hr>';
                //smsArr contiene 1 o más elementos
                //cada elemento es un mensaje nuevo
                for($i = 0; i< sizeof($smsArr); $i++)
                {
                    //y cada mensaje contiene los siguentes parámetros:
                    $to = $smsArr[$i]['to'];
                    $from = $smsArr[$i]['from'];
                    $message = $smsArr[$i]['text'];
                    $hour = $smsArr[$i]['receivedAt'];
                    
                    //se guarda el mensaje en la base de datos
                    $database->guardar($to,$from,$message,$hour);
                    
                    //con el mensaje recibido se consulta en el webcarwler 
                    //y se responde en funcion de lo solicitado
                    
                    //se asigna el texto del mensaje al constructor q la clase ReportDecoder toma
                    //como parámetro de contenido del mensaje (cabecera y contenido)
                    $this->reportDecoder = new ReportDecoder(strtoupper($message));
                    //si el tipo de reporte es METAR...
                    if($this->reportDecoder->getTipoReporte() == 'WXRQ')
                    {
                        //se ejecuta la función de conocer el metar de alguna ciudad, cuál?
                        //la ciudad que indicaba el mensaje. Esta se le pasa como parámetro
                        //obtenido de la función getCiudadDeReporte de la clase ReportDecoder
                        $this->metarMsj = $this->crawler->conocerMetarDe($this->reportDecoder->getCiudadDeReporte());
                        //Como ya conocemos el origen del mensaje, podemos enviar la respuesta.
                        //en este caso se hace xq se pidió un reporte METAR
                        //se envía el mensaje de Infobip hacia quien solicitó la petición
                        enviarSms($from,"InfoSMS",$this->metarMsj);
                        //hasta ahora no se contempla la posibilidad de que un mensaje contenga el metar de varias ciudades
                        //falta desarrollarlo
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