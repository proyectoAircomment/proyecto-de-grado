<?php

    require('web-crawler/web_crawler.php');
    
    //http://190.27.249.245/
    $link = "http://190.27.249.248/obs";
    
    $crawler = new Crawler($link);
    
    $crawler->createAssocArray();
    
    $crawler->printTextArray($crawler->getArray());
    echo '<br>';
    $crawler->printTextArray($crawler->getAssocArray());
    echo '<br>';
    $crawler->printArray($crawler->getAssocArray());
    echo '<br>';
    echo 'obtiene el mensaje de SKMR: ';
    $crawler->printArray($crawler->getItemFromAssocArray('SKMR'));

?>

