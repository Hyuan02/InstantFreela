window.onload = function(){
    var app = new Vue({
        el:"#app",
        data: {  
            titulo: "Aula 01 - Vue JS do jeito ninja!",
            loginCadastro:{username:"", email:"", pass:"", pass2:""},
            empresaCadastro:{nomeEmpresa:"", cnpj:""},
            profissionalCadastro:{nomeProfissional:"", cpf:"", dataNasc:"", ocupacao:""},
            camposLogin:{email:"", senha:""},
            freelaBuscado:"",
            usuarioLogado:{logado:false, tipo:0, username:"", id_usuario:""},
            freelaEditar:{titulo:"",funcao:"",remuneracao:""},
            novaOportunidade:{titulo:"",funcao:"",remuneracao:""},
            imgUpload:'',
            curriculoUpload:'',
            modalAtivo:false,
            conteudoModal:{},
            oportunidadesContratante:{},
            profissionais:{},
            aba:0,
            abaProfissionalControle:0,
            abaFreelancerControle:0,
            opcaoProfissional:0,
            editarFreela:false,
            oportunidadesGerais:[
                {titulo:"Web Design", descricao:"Design de um site em wordpress"},
                {titulo:"Ilustração", descricao:"Character Model de um Personagem"},
                {titulo:"Web Design", descricao:"Design de um site em wordpress"},
                {titulo:"Ilustração", descricao:"Character Model de um Personagem"},
                {titulo:"Web Design", descricao:"Design de um site em wordpress"},
                {titulo:"Ilustração", descricao:"Character Model de um Personagem"},
                {titulo:"Web Design", descricao:"Design de um site em wordpress"},
                {titulo:"Ilustração", descricao:"Character Model de um Personagem"},
                {titulo:"Web Design", descricao:"Design de um site em wordpress"},
                {titulo:"Ilustração", descricao:"Character Model de um Personagem"},
                {titulo:"Ilustração", descricao:"Character Model de um Personagem"},
                {titulo:"Web Design", descricao:"Design de um site em wordpress"},
                {titulo:"Ilustração", descricao:"Character Model de um Personagem"},
                {titulo:"Web Design", descricao:"Design de um site em wordpress"},
                {titulo:"Ilustração", descricao:"Character Model de um Personagem"},
            ],
            usuario:2
        },
        methods:{
            cadastrarProfissional: ()=>{
                console.log('Você fez uma requisição!');
                console.log(app.profissionalCadastro.nome);
                axios.post('req.php',{
                    req: 'pegarDadosProfissional',
                    nome: app.profissionalCadastro.nome,
                    username: app.profissionalCadastro.username,
                    email: app.profissionalCadastro.email,
                    telefone: app.profissionalCadastro.telefone,
                    password: app.profissionalCadastro.pass
                }).then((response)=>{
                    console.log(response);
                    // if(response.data.codigo == 200){
                    //     alert('O nome é: ' + response.data.nome);
                    // }

                });
            },

            cadastrarFreela: ()=>{
                console.log('Requisicao cadastrar freela');
                axios.post('req.php', {
                    req:'pegarDadosFreela',
                    titulo: app.freelaCadastro.titulo,
                    descricao: app.freelaCadastro.descricao,
                    remuneracao: app.freelaCadastro.remuneracao,
                    detalhes: app.freelaCadastro.detalhes,
                    urlImg: app.freelaCadastro.urlImg
                }).then((response)=>
            {
                console.log(response);
                    if(response.data.codigo == 200){
                        alert('O titulo é: ' + response.data.nome);
                    }
            });
            },
            buscarFreela: ()=>{
                console.log(app.freelaBuscado);
                axios.post('req.php',{
                    req:'buscarDadosFreela',
                    nome: app.freelaBuscado
                }).then((response)=>{
                    console.log(response.data);
                    app.oportunidadesGerais = response.data.resultado;                    
                });
            },
            
            cadastrarUsuario: ()=>{
                console.log("Cadastrando Usuario!");
                var dados = {};
                var form = new FormData();
                console.log(app.curriculoUpload);
                console.log(app.imgUpload);
                if(app.loginCadastro.pass != app.loginCadastro.pass2){
                    alert('Senha incorreta!');
                    return;
                }
                form.append('req','cadastrarUsuario');
                form.append('username', app.loginCadastro.username);
                form.append('email', app.loginCadastro.email);
                form.append('pass', app.loginCadastro.pass);
                form.append('opcaoProfissional',app.opcaoProfissional);

                dados.username = app.loginCadastro.username;
                dados.email = app.loginCadastro.email;
                dados.pass = app.loginCadastro.pass;
                if(app.opcaoProfissional == 1){
                    form.append('nomeEmpresa', app.empresaCadastro.nomeEmpresa);
                    console.log(app.empresaCadastro.cnpj.replace(/(\.|\/|\-)/g,""));
                    form.append('cnpj',app.empresaCadastro.cnpj.replace(/(\.|\/|\-)/g,""));
                }
                else if(app.opcaoProfissional == 2){
                    dados.nomeProfissional = app.profissionalCadastro.nomeProfissional;
                    dados.cpf = app.profissionalCadastro.cpf;
                    dados.dataNasc = app.profissionalCadastro.dataNasc;

                    form.append('nomeProfissional', app.profissionalCadastro.nomeProfissional);
                    form.append('cpf',app.profissionalCadastro.cpf.replace(/(\.|\/|\-)/g,""));
                    form.append('dataNasc',app.profissionalCadastro.dataNasc);
                    form.append('imgUpload', app.imgUpload);
                    form.append('ocupacao', app.profissionalCadastro.ocupacao);
                    form.append('curriculoUpload', app.curriculoUpload);
                }
                axios.post('req.php', form, {
                    headers:{
                        'Content-Type':'multipart/form-data'
                    }
                }).then(function (response){
                    if(response.data.codigo == 200){
                        alert("Usuário Cadastrado!");
                        app.loginCadastro.username = app.loginCadastro.email = app.loginCadastro.pass = app.loginCadastro.pass2 = "";
                        app.profissionalCadastro.nomeProfissional = app.profissionalCadastro.cpf = app.profissionalCadastro.dataNasc = app.profissionalCadastro.ocupacao = "";
                        app.empresaCadastro.nomeEmpresa = app.empresaCadastro.cnpj = "";
                        app.opcaoProfissional = 0;
                    }
                });
            },
            

            login: ()=>{
                console.log('enviando!');
                axios.post('req.php', {
                    req:'login',
                    email: app.camposLogin.email,
                    pass: app.camposLogin.senha
                }).then(function (response){
                    if(response.data.codigo == 200){
                        app.usuarioLogado.logado = true;
                        app.usuarioLogado.tipo = response.data.categoriaProfissional;
                        app.usuarioLogado.username = response.data.usuario.nome_usuario;
                        app.usuarioLogado.id_usuario = response.data.usuario.id;
                        if(response.data.categoriaProfissional == 2){
                            app.usuarioLogado.cnpj = response.data.infoAdicional.cnpj;
                            app.usuarioLogado.nomeEmpresa = response.data.infoAdicional.nomeEmpresa;
                        }
                        else if(response.data.categoriaProfissional == 1){
                            console.log('prosseguindo');
                        }
                    }
                });
            },

            retornarFreelas: ()=>{
                console.log('a retornar');
                axios.get('req.php', {
                    params:{
                        req: 'obterFreelasGeral'
                    }
                }).then(function (response){
                    console.log(response.data);
                    app.oportunidadesGerais = response.data.oportunidades;
                });
            },

            inserirCurriculo: ()=>{
                app.curriculoUpload = document.querySelector('#curriculoProfissional').files[0];
                console.log(app.curriculoUpload);
            }, 
            inserirImagem: ()=>{
                app.imgUpload = document.querySelector('#fotoProfissional').files[0];
                // console.log(app.imgUpload);
            },
            inserirImagemFreela: ()=>{
                app.imgUpload = document.querySelector('#cadastroFreelaImg').files[0];
                console.log(app.imgUpload);
            },
            abrirModal: (oportunidade)=>{
                app.modalAtivo = true;
                app.conteudoModal = oportunidade;
            },
            fecharModal:()=>{
                app.modalAtivo = false;
                
            },

            obterOpotunidadesContratante:()=>{
                axios.get('req.php', {
                    params:{
                        req: 'obterOportunidadesContratante',
                        id_contratante: app.usuarioLogado.id_usuario
                    }
                }).then(function (response){
                    app.oportunidadesContratante = response.data.oportunidades;
                });
                },
            obterProfissionais:()=>{
                axios.get('req.php',{
                    params:{
                        req:'obterProfissionaisGeral'
                    }
                }).then(function (response){
                    app.profissionais = response.data.profissionais;
                });
            },

            cadastrarNovoFreela:()=>{
                console.log(app.imgUpload);
                var form = new FormData();
                form.append('req', 'cadastrarNovoFreela');
                form.append('contratante',app.usuarioLogado.id_usuario);
                form.append('titulo',app.novaOportunidade.titulo);
                form.append('descricao',app.novaOportunidade.funcao);
                form.append('remuneracao',app.novaOportunidade.remuneracao);
                form.append('imgFreela',app.imgUpload);
                axios.post('req.php', form, {
                    headers:{
                        'Content-Type':'multipart/form-data'
                    }
                }).then(function (response){
                    console.log(response.data);
                });
            },
            apagarFreela:(idItem)=>{
                console.log(idItem);
                if(confirm("Deseja apagar mesmo o freela?")){
                    axios.post('req.php',{
                        req:"apagarFreelaId",
                        id_freela:idItem
                    }).then((response) =>{
                        console.log(response.data);
                    });
                }
            
            },

            editarFreelaUnico:(item)=>{
                app.freelaEditar.idItem = item.id;
                app.freelaEditar.titulo = item.titulo;
                app.freelaEditar.funcao = item.descricao;
                app.freelaEditar.remuneracao = item.remuneracao;
                app.editarFreela = true;
            },

            editarAntigoFreela:()=>{
                console.log(app.freelaEditar);
                axios.post('req.php',{
                    req:'atualizarFreelaId',
                    id_freela:app.freelaEditar.idItem,
                    titulo: app.freelaEditar.titulo,
                    descricao: app.freelaEditar.funcao,
                    remuneracao: app.freelaEditar.remuneracao
                }).then(response=>{
                    console.log(response.data);

                    this.alert("Freela editado!");

                    app.editarFreela = false;
                    app.obterOpotunidadesContratante();
                    this.abaProfissionalControle = 1;
                });
            }
        }
    });
    


    function trocaBackground(){
        let bgArray = [{imagem:'img/bg1.jpg'},{imagem:'img/bg2.jpg'},{imagem:'img/bg3.jpg'}];
        let elementoBg = document.getElementById('inicial');
        let numero = randomizarInteiro(0,3);
        // console.log("url('" + bgArray[numero] + "')");
        elementoBg.style.backgroundImage = "url('" + bgArray[numero].imagem + "')";
    }
    function randomizarInteiro(min,max){  
        return random = Math.floor(Math.random() * (+max - +min)) + +min; 
    }

    setInterval(trocaBackground,10000);
}




