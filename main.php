<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

function buscar_ofertas(){
    $httpClient = new Client();
    $crawler = new Crawler();
    $link = "https://www.mercadolivre.com.br/ofertas#nav-header";


    try{
        $resposta = $httpClient->request('GET', $link, ['verify' => false]);
    }
    catch(GuzzleException $e){
        echo "Erro ao acessar o site.";
        exit();
    }


    $html = $resposta->getBody();

    $crawler->addHtmlContent($html);
    $produtos = $crawler->filter('a.poly-component__title');
    $precos = $crawler->filter('div.poly-price__current');

    $maxProdutos = $produtos->count();
    $maxPrecos = $precos->count();

    if($maxProdutos == $maxPrecos){
        for($i = 0; $i < $maxProdutos; $i++){
            echo str_repeat("-", 150) . PHP_EOL;
            echo $produtos->eq($i)->text() ."|". PHP_EOL .  $precos->eq($i)->text() . PHP_EOL;
        }
    }
    else{
        echo "Erro na leitura de produtos e preços.";
        exit();
    }

}

echo "---Principais ofertas do dia " . date('d/m/Y') . " ---" . PHP_EOL;

buscar_ofertas();

