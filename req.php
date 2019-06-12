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
    if($data['opcaoProfissional'] == 1){
        $conn->beginTransaction();
        $sql = sprintf('INSERT INTO login VALUES ((select max(id) from login)+1, :username, :senha, :email)');
        $processo1 = $conn->prepare($sql);
        $processo1->bindValue(':username', $username);
        $processo1->bindValue(':senha', $pass);
        $processo1->bindValue(':email',$email);
        $processo1->execute();
        $conn->commit();
        $nomeEmpresa = $data['nomeEmpresa'];
        $cnpj = $data['cnpj'];

        $sql2 = sprintf('INSERT INTO contratante VALUES ((select max(id) from login), :cnpj, :nome_empresa)');
        $processo2 = $conn->prepare($sql2);
        $processo2->bindValue(':cnpj',$cnpj);
        $processo2->bindValue(':nome_empresa',$nomeEmpresa);
        $processo2->execute();
    }
    else if($data['opcaoProfissional'] == 2){
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

    }
    echo json_encode(array(
        'codigo' => 200,
        'mensagem' => 'cadastro concluido'
    ));
}

    if(isset($req) && $req == "login"){ // == vai fazer uma comparação, enquanto = vai atribuir
        $email = $data['email'];
        $senha = $data['pass'];
        $catProfissional = 0;
        $infoAdicional = null;
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
       
        if($resultado2){
            $catProfissional = 1;
            $infoAdicional = $resultado2;
        }
        
        $nomeprofissional = $resultado2['nome_profissional'];


        $processo3 = $conn->prepare($sql3);
        $processo3->bindValue(":id", $id);
        $processo3->execute();
        $resultado3 = $processo3->fetch(PDO::FETCH_ASSOC);
        
        if($resultado3){
            $catProfissional = 2;
            $infoAdicional = $resultado3;
        }
        $nomeempresa = $resultado3['nome_empresa'];

    
        echo json_encode(
            array(
                "codigo" => 200,
                "usuario" => $resultado,
                "infoAdicional" => $infoAdicional,
                "categoriaProfissional" => $catProfissional
            )
        );
    }

    if(isset($req) && $req=="obterFreelasGeral"){
        // $freelas = $data['freelas'];
    
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

    if(isset($req) && $req == "obterOportunidadesContratante"){
        $id_contratante = $_GET['id_contratante'];
        $sql = sprintf('SELECT * FROM oportunidade WHERE contratante = :id_contratante');
        $processo = $conn->prepare($sql);
        $processo->bindValue(':id_contratante', $id_contratante);
        $processo->execute();
        $oportunidades = $processo->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(array(
            "codigo" => 200,
            "oportunidades" => $oportunidades
        ));
    }

    if(isset($req) && $req == "obterProfissionaisGeral"){
        $sql = sprintf('SELECT * FROM profissional');
        $processo= $conn->prepare($sql);
        $processo->execute();
        $profissionais = $processo->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(array(
            "codigo" => 200,
            "profissionais" => $profissionais
        ));
    }

    if(isset($req) && $req == "apagarFreelaId"){
        $id_freela = $data['id_freela'];
        $sql = sprintf('Delete  FROM oportunidade where oportunidade.id = :id_freela');
        $processo= $conn->prepare($sql);
        $processo->bindValue(':id_freela',$id_freela);
        $processo->execute();
        echo json_encode(array(
            "codigo" => 200,
            'mensagem'=>"deletou"
        ));
    }

    if(isset($req) && $req=="cadastrarNovoFreela"){
        $titulo = $data['titulo'];
        $descricao = $data['descricao'];
        $remuneracao = $data['remuneracao'];
        // $detalhes = $data['detalhes'];
        $contratante = $data['contratante'];
            if(move_uploaded_file($_FILES['imgFreela']['tmp_name'], "imgFreela/".$_FILES['imgFreela']['name'])){
                $conn->beginTransaction();
            $sql = sprintf('INSERT INTO oportunidade VALUES (4,:contratante, :descricao, :remuneracao, :titulo, :url_img)');
            $processo1 = $conn->prepare($sql);
            $processo1->bindValue(':descricao', $descricao);
            $processo1->bindValue(':remuneracao', $remuneracao);
            // $processo1->bindValue(':detalhes',$detalhes);
            $processo1->bindValue(':contratante', intval($contratante));
            $processo1->bindValue(':titulo',  $titulo);
            $processo1->bindValue(':url_img',"imgFreela/".$_FILES['imgFreela']['name']);
            $processo1->execute();
            $conn->commit();
    
        }
        echo json_encode(array(
            'codigo' => 200,
            'mensagem' => 'cadastro de freela concluido'
        ));
    }

    if(isset($req) && $req =="atualizarFreelaId"){
        $titulo = $data['titulo'];
        $descricao = $data['descricao'];
        $remuneracao = $data['remuneracao'];
        $id_freela = $data['id_freela'];

        $sql = sprintf('UPDATE oportunidade SET titulo = :titulo, descricao = :descricao, remuneracao = :remuneracao where id = :id_freela');
        $processo1 = $conn->prepare($sql);
        $processo1->bindValue(':titulo', $titulo);
        $processo1->bindValue(':descricao', $descricao);
        $processo1->bindValue(':remuneracao',$remuneracao);
        $processo1->bindValue(':id_freela',$id_freela);
        $processo1->execute();

        echo json_encode(
            array(
                'codigo' => 200,
                'mensagem' => 'edicao de freela concluido'
            )
        );
    }


// if(isset($req) && $req=='testeGet'){
//     echo json_encode(array('nome' => 'Teste get com sucesso!'));
// }