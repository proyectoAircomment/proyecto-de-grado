<?php
    class ReportDecoder
    {
        private $mensaje, $tipo;
        private $cabecera,$contenido;
        private $arrayTipoMetar,$assocArrayMetar,$assocArrayPosRpt;
        
        public function __construct($mensaje = 'QU ANPOCFC

                                .DDLXCXA 032259
                                
                                WXR
                                
                                FI FC8149/AN HK-4811
                                
                                DT DDL MTR 032259 M50A
                                
                                - 3N01 POSRPT 8138/23 SKBO/SKBQ HK-4818
        
        /UTC 195906/POS N09216W074273/ALT +35133/MCH 749/FOB 00475')
        {
            $this->mensaje = $mensaje;
            $this->cabecera = '';
            $this->contenido = '';
            $this->formatearComponentes($this->mensaje);
        }

        
        //divide el mensaje en 2 partes: cabecera y contenido
        //llena los arrays cabecera y contenido
        function formatearComponentes($mensaje)
        {
            $msj = preg_split('[ - ]',$mensaje);
            $this->cabecera = explode(' ', $msj[0]);
            $this->contenido = str_replace('/',' ',$msj[1]);
            
        }
        
        function getCabecera()
        {
            return $this->cabecera;
        }
        
        function getContenido()
        {
            return $this->contenido;
        }
        
        //en el campo contenido[1] se encuentra el tipo de reporte
        //esta funcion lo entrega para optar de acuerdo a este.
        function getTipoReporte()
        {
            $this->tipo = $this->contenido[1];
            return $this->tipo;
        }
        
        //crea uno o varios arrays dependiendo del tipo de reporte
        //emitido de la aeronave hacia el sistema
        function setArray()
        {
            $arr = preg_split("/[\s,]+/",$this->contenido);
            //la posición 1 del vetor contiene el tipo de reporte
            switch ($arr[1]) 
            {
                case 'WXRQ':
                    $this->setArrayMetar();
                    break;
                    
                case 'POSRPT':
                    $this->setArrayPosRpt();
                    break;
                
                case 'OUTRP' || 'OFFRP' || 'ONRP' || 'INRP':
                    $this->setArrayOOOI();
                    break;
                
                default:
                    //HACER ALGO
                    break;
            }
            
        }
        
        //Fragmenta el contenido del mensaje
        //y lo convierte en un array asociativo
        function setArrayMetar()
        {
            //abstrae las ciudades de las que se solicita el reporte
            $citiesReport = preg_split("/[\s,]+/",trim(str_replace('STA','',strstr($this->contenido,'STA'))));
            $arr = preg_split("/[\s,]+/",$this->contenido);
            //asigna los valores del array asociativo
            $this->assocArrayMetar = array('messageHeader'=>$arr[0].' '.$arr[1],
                                     'flightNumber' =>$arr[2],
                                     'flightDay'    =>$arr[3],
                                     'origin'       =>$arr[4],
                                     'destination'  =>$arr[5],
                                     'aircraftId'   =>$arr[6],
                                     'reportId'     =>$arr[7].' '.$arr[8],
                                     'citiesReport' =>$citiesReport);
            var_dump($this->assocArrayMetar);
        }
        
        //Fragmenta el contenido del mensaje
        //y lo convierte en un array asociativo
        function setArrayPosRpt()
        {
            //crea un vector de palabras que serán eliminadas en el mensaje
            $replace = array('UTC','POS','ALT','MCH','FOB');
            $arr = preg_split("/[\s,]+/",trim(str_replace($replace,'',$this->contenido)));
            //separación de los valores de posición recibidos como un string continuo (sin espacios)
            $toSplit = preg_split("/(,?\s+)|((?<=[a-z])(?=\d))|((?<=\d)(?=[a-z]))/i",$arr[8]);
            //formateo del valor de las coordenadas en g° m' s"
            $dirLat = $toSplit[0];
            $segsLat = substr($toSplit[1],-1,1);
            $minsLat = substr($toSplit[1],-3,2);
            $degsLat = substr($toSplit[1],0,-3);
            $dirLon = $toSplit[2];
            $segsLon = substr($toSplit[3],-1,1);
            $minsLon = substr($toSplit[3],-3,2);
            $degsLon = substr($toSplit[3],0,-3);
            
            //asignación de los valores de orientación a la posición como un array
            $position = array('lat' => array('direction'=>$dirLat, 'dms'=>array('d'=>$degsLat,'m'=>$minsLat,'s'=>$segsLat)), 
                              'lon' => array('direction'=>$dirLon, 'dms'=>array('d'=>$degsLon,'m'=>$minsLon,'s'=>$segsLon)));
            //asigna los valores del array asociativo
            $this->assocArrayPosRpt = array(
                                     'messageHeader'=>$arr[0].' '.$arr[1],
                                     'flightNumber' =>$arr[2],
                                     'flightDay'    =>$arr[3],
                                     'origin'       =>$arr[4],
                                     'destination'  =>$arr[5],
                                     'aircraftId'   =>$arr[6],
                                     'UTCtime'      =>$arr[7],
                                     'position'     =>$position,
                                     'altitude'     =>$arr[9],
                                     'speed'        =>$arr[10],
                                     'fuel'         =>$arr[11]);
        }
        
        //crea los array asociativos OOOI
        //por cada tipo de reporte asigna un formato distinto
        function setArrayOOOI()
        {
            //implementarlo
        }
        
        //
        function getArrayMetar()
        {
            return $this->assocArrayMetar;
        }
        
        function getArrayPosRpt()
        {
            return $this->assocArrayPosRpt;
        }
        
        //del array de solicitudes Metar creado
        //se retorna las ciudades de las cuales se quiere
        //conocer las condiciones meteorológicas.
        function getAeropuertosDeReporteMetar()
        {
            return $this->assocArrayMetar['citiesReport'];
        }
        
    }
?>