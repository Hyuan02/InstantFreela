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


if(isset($req) && $req=="cadastrarUsuario"){
    $username = $data['username'];
    $email = $data['email'];
    $senha = $data['pass'];

    $conn->beginTransaction();

    $sql = sprintf("INSERT INTO login VALUES ((select max(id) from login)+1, :username, :senha, :email)");
    $processo = $conn->prepare($sql);
    $processo->bindValue(":username", $username);
    $processo->bindValue(":email", $email);
    $processo->bindValue(":senha", $senha);
    $processo->execute();
    $conn->commit(); //sobrescrever o banco salvo

    echo json_encode(array(
        "codigo" => 200,
        "mensagem" => "Tudo Legal"
    ));

}
    if(isset($req) && $req=="login"){ // == vai fazer uma comparação, enquanto = vai atribuir
        $email = $data['email'];
        $senha = $data['pass'];
        
        //$conn->beginTransaction();
        
        $sql = sprintf("SELECT nome_usuario, id FROM login where email like :email AND senha like :senha");
        //usa-se like aqui pois são strings
        //se tem : na frente são as nossas variáveis
        //A partir do usuário a gente vai descobrir o tipo dele (profissional ou contratante) 

        $processo = $conn->prepare($sql);
        $processo->bindValue(":email", $email);
        $processo->bindValue(":senha", $senha);
        $processo->execute();
        $resultado = $processo->fetch(PDO::FETCH_ASSOC);

        $id = $resultado['id']; //retirando do resultado a coluna que nos interessa

        $sql2 = sprintf("SELECT * FROM profissional WHERE profissional.id=:id");
        $sql3 = sprintf("SELECT * FROM contratante WHERE contratante.id=:id"); 
        //Verificando se ele está na tabela de profissionais ou contratantes pra que a gente possa exibir a tela correta de usuário após o login

        $processo2 = $conn->prepare($sql2);
        $processo2->bindValue(":id", $id);
        $processo2->execute();
        $resultado2 = $processo2->fetch(PDO::FETCH_ASSOC);
        
        $nomeprofissional = $resultado2['nome_profissional'];


        $processo3 = $conn->prepare($sql3);
        $processo3->bindValue(":id", $id);
        $processo3->execute();
        $resultado3 = $processo3->fetch(PDO::FETCH_ASSOC);
        
        $nomeempresa = $resultado3['nome_empresa'];

    
        echo json_encode(
            array(
                "codigo" => 200,
                "mensagem" => $resultado,
                "mensagem2" => $nomeprofissional,
                "mensagem3" => $nomeempresa
            )
        );
    }


// if(isset($req) && $req=='testeGet'){
//     echo json_encode(array('nome' => 'Teste get com sucesso!'));
// }