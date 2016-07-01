<?php

    require('web-crawler/web_crawler.php');
    
    //http://190.27.249.245/
    $link = "http://190.27.249.248/obs";
    
    $crawler = new Crawler($link);
    
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

echo strlen($metar);

?>

