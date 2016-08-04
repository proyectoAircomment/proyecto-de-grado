<?php
echo 'servidor funcionando!';


    require('../modules/web-crawler/web_crawler.php');
    require('../modules/sms/mensajes.php');
    require('../models/database.php');
    require('../modules/sms/report_decoder.php');
    require('../modules/viewer_flights/coords_format.php');
    
    $link = "http://190.27.249.248/obs";
    //$sms = new Mensajes();

    //$db = new Database();
    
    //$db->guardar('yo','el','mensaje','ahora');
    
    $mensaje = 'QU ANPOCFC

                                .DDLXCXA 032259
                                
                                WXR
                                
                                FI FC8149/AN HK-4811
                                
                                DT DDL MTR 032259 M50A
                                
                                - 3N01 POSRPT 8138/23 SKBO/SKBQ HK-4818
        
        /UTC 195906/POS N09216W074273/ALT +35133/MCH 749/FOB 00475';
    
    $crawler = new Crawler($link);
    $rd = new ReportDecoder();
    $coords = new CoordsFormat();
?>