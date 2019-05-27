<?php

$data = json_decode(file_get_contents("php://input"), TRUE);

if($data['req'] == null)
    $req = $_GET['req'];
else
    $req = $data['req'];



echo $req;

// if(isset($req) && $req=="pegarDadosProfissional"){
//     $nome = $data['nome'];
//     echo json_encode($data);
// }

// if(isset($req) && $req=='testeGet'){
//     echo json_encode(array('nome' => 'Teste get com sucesso!'));
// }