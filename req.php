<?php

$data = json_decode(file_get_contents("php://input"), TRUE);

require_once 'dbconfig.php';
$conexao = "pgsql:host=$host;port=5432;dbname=$db;user=$username;password=$password";

try{
    $conn = new PDO($conexao);
}

catch(PDOException $e){
    echo $e->getMessage();
}


if($data['req'] == null)
    $req = $_GET['req'];
else
    $req = $data['req'];



echo $req;

if(isset($req) && $req=="pegarDadosProfissional"){
    $nome = $data['nome'];
    $email = $data['email'];
    $telefone = $data['telefone'];
    $password = $data['password'];
    $username = $data['username'];

    $conn->beginTransaction();
    $sql = sprintf("INSERT INTO profissionais VALUES (:nome, :username, :email, :password, :telefone)");
    $processo = $conn->prepare($sql);
    $processo->bindValue(":nome", $nome);
    $processo->bindValue(":email", $email);
    $processo->bindValue(":telefone", $telefone);
    $processo->bindValue(":username", $username);
    $processo->bindValue(":password", $password);
    $processo->execute();
    $conn->commit();
}

if(isset($req) && $req=="buscarDadosFreela"){
    $nome = $data['nome'];
    $busca = $nome;
    $conn->beginTransaction();
    $sql = sprintf("SELECT * FROM freelas where titulo like :nome ");
    $processo = $conn->prepare($sql);
    $processo->bindValue(":nome","%%".$nome."%%");
    $processo->execute();
    $resultado = $processo->fetch(PDO::FETCH_ASSOC);

    echo json_encode(
        array(
            "codigo" => 200,
            "resultado" => $resultado
        )
    );
}

// if(isset($req) && $req=='testeGet'){
//     echo json_encode(array('nome' => 'Teste get com sucesso!'));
// }