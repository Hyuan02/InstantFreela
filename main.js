window.onload = function(){
    var app = new Vue({
        el:"#app",
        data: {  
            titulo: "Aula 01 - Vue JS do jeito ninja!",
            profissionalCadastro:{nome:"", username:"", email:"", telefone:"", pass:"", pass2:""},
            freelaCadastro: {titulo:"", descricao:"", remuneracao:"", detalhes:"", urlImg:""},
            aba:0,
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
                var novoForm = new FormData();
                novoForm.append('nome',app.profissionalCadastro.nome);
                novoForm.append('usuario',app.profissionalCadastro.usuario);
                novoForm.append('email',app.profissionalCadastro.email);
                novoForm.append('telefone',app.profissionalCadastro.telefone);
                novoForm.append('senha',app.profissionalCadastro.pass);
                axios.post('req.php',{
                    req: 'pegarDadosFreela',
                    nome: app.profissionalCadastro.nome,
                    username: app.profissionalCadastro.username,
                    email: app.profissionalCadastro.email,
                    telefone: app.profissionalCadastro.telefone,
                    pass: app.profissionalCadastro.pass
                }).then((response)=>{
                    console.log(response);
                    if(response.data.codigo == 200){
                        alert('O nome é: ' + response.data.nome);
                    }
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




