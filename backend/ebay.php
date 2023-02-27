<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client;
$ebay = $app['controllers_factory'];
$ebayAppId = $_ENV['EBAY_APP_ID'];
$client = new Client([    
    'base_uri' => 'http://svcs.ebay.com'    
]);
$ebay->get('/find-by-code/{code}/{page}', function ($code, $page) use ($app, $client, $ebayAppId) {  
    if (strlen($code) == 10 || strlen($code) == 13){
        $type = 'ISBN';
    }
    else if (strlen($code) == 12){
        $type = 'UPC';
    }
    else{
        return $app->json(['error' => 'invalid code'], 400);
    }
    if (!is_int(intval($page)) || $page <= 0){
        return $app->json(['error' => 'invalid page'], 400);
    }
$response = $client->request('GET', "/services/search/FindingService/v1?OPERATION-NAME=findItemsByProduct&SERVICE-VERSION=1.0.0&SECURITY-APPNAME=$ebayAppId&RESPONSE-DATA-FORMAT=JSON&REST-PAYLOAD&paginationInput.entriesPerPage=10&productId.@type=$type&productId=$code&paginationInput.pageNumber=$page");    
    return $app->json(json_decode($response->getBody(), true));
});
return $ebay;