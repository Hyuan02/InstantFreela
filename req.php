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
    $conn->beginTransaction();
    $sql = sprintf("SELECT * FROM oportunidade where LOWER(titulo) like LOWER(:nome) ");
    $processo = $conn->prepare($sql);
    $processo->bindValue(":nome","%%".$nome."%%");
    $processo->execute();
    $resultado = $processo->fetchAll(PDO::FETCH_ASSOC);

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

    if(isset($req) && $req=="obterFreelasGeral"){
        $freelas = $data['freelas'];
    
        $conn->beginTransaction();
    
        $sql = sprintf("SELECT * FROM oportunidade");
        $processo = $conn->prepare($sql);
        $processo->execute();
        $resultado = $processo->fetchAll(PDO::FETCH_ASSOC);

        $oportunidades = $resultado;
        

        echo json_encode(array(
            "codigo" => 200,
            "oportunidades" => $oportunidades
        ));
    }


// if(isset($req) && $req=='testeGet'){
//     echo json_encode(array('nome' => 'Teste get com sucesso!'));
// }