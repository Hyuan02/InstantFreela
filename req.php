<?php

if(json_decode(file_get_contents("php://input"), TRUE) != null){
    $data = json_decode(file_get_contents("php://input"), TRUE);
    $req = $data['req'];
}
else if($_POST != NULL){
    $data = $_POST;
    $req = $data['req'];
}
else{
    $req = $_GET['req'];
}


require_once 'dbconfig.php';
$conexao = "pgsql:host=$host;port=5432;dbname=$db;user=$username;password=$password";

try{
    $conn = new PDO($conexao);
}

catch(PDOException $e){
    echo $e->getMessage();
}

echo json_encode($data);


// if($data['req'] == null)
//     $req = $_GET['req'];
// else
//     $req = $data['req'];



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


if(isset($req) && $req="cadastrarUsuario"){
    $username = $data['username'];
    $email = $data['email'];
    $pass = $data['pass'];
    $nomeProfissional = $data['nomeProfissional'];
    $cpf = $data['cpf'];
    $dataNasc = $data['dataNasc'];
    $ocupacao = $data['ocupacao'];
    if(move_uploaded_file($_FILES['imgUpload']['tmp_name'], "fotosPerfil/".$_FILES['imgUpload']['name']) && move_uploaded_file($_FILES['curriculoUpload']['tmp_name'], "curriculos/".$_FILES['curriculoUpload']['name'])){
        $conn->beginTransaction();
    $sql = sprintf('INSERT INTO login VALUES ((select max(id) from login)+1, :username, :senha, :email)');
    $processo1 = $conn->prepare($sql);
    $processo1->bindValue(':username', $username);
    $processo1->bindValue(':senha', $pass);
    $processo1->bindValue(':email',$email);
    $processo1->execute();
    $conn->commit();

    if($data['opcaoProfissional'] == 1){
        $sql2 = sprintf();
    }
    if($data['opcaoProfissional'] == 2){
        $sql2 = sprintf('INSERT INTO profissional values((select max(id) from login), :curriculo, :ocupacao, :cpf, :imagem_perfil, :nome_profissional, :data_nascimento)');
        $processo2 = $conn->prepare($sql2);
        $processo2->bindValue(':curriculo',"curriculos/".$_FILES['curriculoUpload']['name']);
        $processo2->bindValue(':ocupacao', $ocupacao);
        $processo2->bindValue(':cpf', $cpf);
        $processo2->bindValue(':imagem_perfil',"fotosPerfil/".$_FILES['imgUpload']['name']);
        $processo2->bindValue(':nome_profissional',$nomeProfissional);
        $processo2->bindValue(':data_nascimento', "1997-02-05");
        $processo2->execute();

    }

        echo json_encode(array(
            'codigo' => 200,
            'mensagem' => 'cadastro concluido'
        ));


    }

    
}

// if(isset($req) && $req=='testeGet'){
//     echo json_encode(array('nome' => 'Teste get com sucesso!'));
// }