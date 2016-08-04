<?php
    class CoordsFormat
    {
        private $lat, $lon;
        
        function CoordsFormat()
        {
            $this->lat = 0;
            $this->lon = 0;
        }
        
        //Se encarga de convertir coordenadas de latitud GMS en coordenadas decimales
        //recibe como parámetros la direccion (N,S) y un vector que contiene 
        //los valores G, M, S
        function setLat($dir,$dms)
        {
            $lat = $dms['d'] + ($dms['m']/60) + ($dms['s']*6/3600);
            if(strcmp($dir,'S') == 0)
            {
                $this->lat = $lat * (-1);
            }else
            {
                $this->lat = $lat;
            }
        }
        
        function getLat()
        {
            return $this->lat;
        }
        
        //Se encarga de convertir coordenadas de longitud GMS en coordenadas decimales
        //recibe como parámetros la direccion (E,W) y un vector que contiene 
        //los valores G, M, S
        function setLon($dir,$dms)
        {
            $lon = $dms['d'] + ($dms['m']/60) + ($dms['s']*6/3600);
            if(strcmp($dir,'W') == 0)
            {
                $this->lon = $lon * (-1);
            }else
            {
                $this->lon = $lon;
            }
        }
        
        function getLon()
        {
            return $this->lon;
        }
    }
?>