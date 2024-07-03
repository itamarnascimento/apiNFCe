<?php

error_reporting(0);
ini_set("display_errors", 0);
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: authorization');

header('Content-Type: application/json; charset=UTF-8');
$content = file_get_contents('php://input');

empty($content) && die(json_encode(['error' => "Requisicao sem corpo"]));

$content = get_object_vars(json_decode($content));

$url = $content['url'];

$contentPagina = file_get_contents($url);


$contentPaginaTeste = simplexml_load_string($contentPagina);

$det = $contentPaginaTeste->proc->nfeProc->NFe->infNFe->det;
$protudos = array();
$prods = array();

foreach ($det as $prod) {
    $prods['codigo_barras'] = isset($prod->prod->cEAN) ? $prod->prod->cEAN->__toString() : "";
    $prods['codigo_interno'] = isset($prod->prod->cProd) ? $prod->prod->cProd->__toString() : "";
    $prods['descricao'] = isset($prod->prod->xProd) ? $prod->prod->xProd->__toString() : "";
    $prods['qtd'] = floatval($prod->prod->qCom->__toString());
    $prods['valorUnit'] = floatval($prod->prod->vUnCom->__toString());
    $prods['unidade'] = $prod->prod->uCom->__toString();
    $prods['total'] = floatval($prod->prod->vProd->__toString());
    $prods['desconto'] = isset($prod->prod->vDesc) ? floatval($prod->prod->vDesc->__toString()) : 0.00;
    $protudos[] = $prods;
}

echo json_encode($protudos);