<?php

$data = json_decode(file_get_contents("php://input"), TRUE);

$req = $data['req'] ?? $data['req'] ?? null;

if(isset($req) && $req=="pegarDadosFreela"){
    $nome = $data['nome'];
    echo json_encode(array(
        "codigo" => 200,
        "nome" => $nome
    ));
}