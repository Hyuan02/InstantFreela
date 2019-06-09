window.onload = function(){
    var app = new Vue({
        el:"#app",
        data: {  
            titulo: "Aula 01 - Vue JS do jeito ninja!",
            loginCadastro:{username:"", email:"", pass:"", pass2:""},
            empresaCadastro:{nomeEmpresa:"", cnpj:""},
            profissionalCadastro:{nomeProfissional:"", cpf:"", dataNasc:""},
            camposLogin:{email:"", senha:""},
            usuarioLogado:{logado:false, tipo:0, username:"", id_usuario:""},
            aba:0,
            abaProfissionalControle:0,
            abaFreelancerControle:0,
            opcaoProfissional:0,
            oportunidades:[
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
            buscarFreela: (response)=>{
                axios.post('req.php',{
                    req:'buscarDadosFreela',
                    nome: response
                }).then((response)=>{
                    console.log(response.data);
                });
            },
            
            

            login: ()=>{
                console.log('enviando!');
                axios.post('req.php', {
                    req:'login',
                    email: app.camposLogin.email,
                    pass: app.camposLogin.senha
                }).then(function (response){
                    console.log(response.data);
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




