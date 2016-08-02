<?php

    require('simple_html_dom.php');
    
    class Crawler
    {
        private $metars, $assoc_metar, $url;
        
        public function __construct($url="http://190.27.249.248/obs")
        {
            $this->metars = Array();
            $this->assoc_metar = Array();
            $this->url = $url;
            $this->createAssocArray();
        }
        
        //Obtiene el contenido de una pagina web
        function get_content($tag)
        {
            $html = file_get_html($this->url);
            //clases content y primary_content
            foreach(($html->find('#content #primary_content '.$tag)) as $i=>$metar)
             {
                 $this->metars[] = $metar;
             }
        }
        
        //crea el array asociativo de metars según la ciudad
        function createAssocArray()
        {
            $this->get_content('div');
            foreach($this->metars as $row)
            {
                $long = strlen ( $row );
                $estacion = substr($row,11,4);
                $mensaje = substr($row,16,$long-43);
                $this->assoc_metar[$estacion] = $mensaje;
            }
        }
        
        //obtiene array de metars indexados numéricamente
        function getArray()
        {
            return $this->metars;
        }
        
        //obtiene array de metars indexados asociativamente
        function getAssocArray()
        {
            return $this->assoc_metar;
        }
        
        //obtiene el mensaje de la consulta de una ciudad específica
        function conocerMetarDe($item)
        {
            return $this->assoc_metar[$item];
        }
        
        function printTextArray($array)
        {
            foreach ($array as $value) 
            {
                echo $value;
                echo "<br>";
            }
        }
        
        //imprime el array pasado en el parámetro
        function printArray($array)
        {
            var_dump($array);
        }

        public function __destruct()
        {
            echo '<hr>memoria liberada!<hr>';
        }
    }

?>