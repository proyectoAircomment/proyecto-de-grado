<?php
    class ReportDecoder
    {
        private $mensaje, $tipo;
        private $cabecera,$contenido;
        private $arrayTipoMetar,$assocArrayMetar;
        
        public function __construct($mensaje = 'QU ANPOCFC

                                .DDLXCXA 032259
                                
                                WXR
                                
                                FI FC8149/AN HK-4811
                                
                                DT DDL MTR 032259 M50A
                                
                                - 001 WXRQ 8149/03 SKBO/SKRG HK-4811
                                
                                /TYP 1/STA SKRG/STA SKBO')
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
        
        function setArray()
        {
            $arr = preg_split("/[\s,]+/",$this->contenido);
            switch ($arr[1]) 
            {
                case 'WXRQ':
                    $this->setArrayMetar();
                    break;
                
                default:
                    //HACER ALGO
                    break;
            }
            
        }
        
        function setArrayMetar()
        {
            $citiesReport = preg_split("/[\s,]+/",trim(str_replace('STA','',strstr($this->contenido,'STA'))));
            $arr = preg_split("/[\s,]+/",$this->contenido);
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
        
        //falta implementarlo correctamente
        function getAeropuertosDeReporte()
        {
            return $this->assocArrayMetar['citiesReport'];
        }
        
        function administrarReportes($tipo)
        {
            
        }
        
        function sacarCiudadesdeEminsionDeAcuerdoAlTipo()
        {
            //falta implementarlo
        }
        
        
        
        
        
    }
?>