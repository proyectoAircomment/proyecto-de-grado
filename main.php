<?php

    require('web-crawler/web_crawler.php');
    require('sms/mensajes.php');
    require('sms/database.php');
    require('sms/report_decoder.php');

    $link = "http://190.27.249.248/obs";
    //$sms = new Mensajes();

    //$db = new Database();
    
    //$db->guardar('yo','el','mensaje','ahora');
    
    $crawler = new Crawler($link);
    
    
    $rd = new ReportDecoder();
    echo 'Contenido (formateado) del mensaje: <hr>';
    echo $rd->getContenido();
    echo '<hr> Array que contiene el mensaje METAR desglosado: <hr>';
    $rd->setArray();
    
    echo '<hr>Metars: <hr>';
    foreach($rd->getAeropuertosDeReporte() as $city)
    {
        echo $crawler->conocerMetarDe($city).'<br>';
    }
    
    
    //http://190.27.249.245/
    /*
    
    
    
    $crawler->printTextArray($crawler->getArray());
    echo '<br>';
    $crawler->printTextArray($crawler->getAssocArray());
    echo '<br>';
    $crawler->printArray($crawler->getAssocArray());
    echo '<br>';
    echo 'obtiene el mensaje de SKMR: ';
    $crawler->printArray($crawler->getItemFromAssocArray('SKMR'));
    
    $metar = 'QU ANPOCFC

.DDLXCXA 032259

WXR

FI FC8149/AN HK-4811

DT DDL MTR 032259 M50A

- 001 WXRQ 8149/03 SKBO/SKRG HK-4811

/TYP 1/STA SKRG/STA SKBO';

echo strlen($metar);*/

    

?>

