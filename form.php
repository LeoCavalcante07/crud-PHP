<?php

    //obs: 
    //usar o global em frente as váriaveis, faz com que a variavel seja visivel na programação, em outros arquivos, vc pode usar a mesma variavel



    //inclui o arquivo que faz a conexao com o banco
    require_once('conexao.php');
    


    //As váriaveis globais são conhecidas como variaveis de sessão, ela é gravada no navegador e efica disponivel enquanto sessão durar
    session_start();//ativa o uso das variaveis de sessao

    //variavel sessao = pode ser usado enquanto o navegador estiver aberto(sessão), guarda qualquer tipo de dado, desde variaveis a matru=izes

    // variavel do tipo global = pode ser usada fora da função, porém morre quando a página é atualizada ela morre obs: a pagina atualiza


    //chama a função que estabelece a conexão com o banco de dados
    $conexao = conexaoDB();




//declaração das variaveis que são utilizadas no editar, se não dá problema no html
    $nome = null;
    $email = null;
    $telefone = null;
    $celular = null;
    $dtNasc = null;
    $obs = null;

    $botao = "Inserir";



    


    if(isset($_GET["btnSalvar"])){
        $nome = $_GET["txtNome"];
        $email = $_GET["txtEmail"];
        $telefone = $_GET["txtTelefone"];
        $celular = $_GET["txtCelular"];
        $dtNasc = $_GET["txtDtNasc"];
        $obs = $_GET["txtObs"];
        
        //O comando explode quebra uma String em um array de dados, nesse caso o que dividiu os indices foi o "/"
        $data = explode("/", $dtNasc); //$data é um array 
                
        $dtNasc = $data[2]."-".$data[1]."-".$data[0];
        
        if($_GET["btnSalvar"] == "Inserir"){
            $sql= "
                insert into tblcontatos(nome, email, telefone, celular, data_nascimento, obs)
                values(
                    '".$nome."','".$email."', '".$telefone."', '".$celular."', '".$dtNasc."', '".$obs."' 
                );
                ";
        }else if($_GET["btnSalvar"] == "Editar"){
            $sql = "update tblcontatos set nome = '".$nome."', email = '".$email."', telefone = '".$telefone."', celular = '".$celular."', data_nascimento = '".$dtNasc."', obs = '".$obs."' where codigo = ".$_SESSION['id']." ;";
        }
        
        
        
        
//        executa a query m=no banco
       mysqli_query($conexao, $sql);
        
        //Redireciona para a pagina inicial
        header('location:form.php');
        
    }


    //a variavel modo é enviada para a URL através do link da imagem na tabela de consulta
    //sera usado para sabrse vai excluir, editar ou pesquisar
    if(isset($_GET['modo'])){
        
        $modo = $_GET['modo'];
        $id = $_GET['id'];
        
        if($modo == 'excluir'){
            
            $sql = "delete from tblcontatos where codigo =".$id."; ";
            mysqli_query($conexao, $sql);
            header("location:form.php");

        }else if($modo == 'buscar'){
            
            $_SESSION['id'] = $id;//criando uma variavel de sessão para ser utilizado no update, e ela não 'morrer' no submit
            
            $botao = "Editar";
            $sql = "select * from tblcontatos where codigo = ".$id.";";
            
            $select = mysqli_query($conexao, $sql);
            
            //a matriz rsConsulta recebe o select feito no banco convertido para array por causa do mysqli_fetch_array
            if($rsConsulta = mysqli_fetch_array($select)){
                $nome = $rsConsulta['nome'];
                $telefone = $rsConsulta['telefone'];
                $celular = $rsConsulta['celular'];
                $email = $rsConsulta['email'];
                $dtNasc = $rsConsulta['data_nascimento'];
                
                //a função date formata um padrao de data. O primeiro parametro é o formato que vc quer, o segundo é a data que vc quer transformar. A data vem como String, e nesse caso se necessita estar no tipo time para usar na função
                $dtNasc = date('d/m/Y', strtotime($dtNasc));
                $obs = $rsConsulta['obs'];
            }
            
        }
    }
    




?>


<!DOCTYPE html>
<html>
    <head>
        <link href="css/style.css" rel="stylesheet" type="text/css">
        
        <script src="js/jquery.js"></script>
        <script>
//            o $ é para dizer que é jquery e não javascript
            
            // $("elemento que será usado").evento(function(){o que vai acontecer})
            $(document).ready(function(){ //é acionado assim que a pagina abre, todo jquery começa assim
                
                //function que abre a janela modal
                $(".visualizar").click(function(){
                   
                    //efeito para aparecer os elemento:
                    //toogle, slideToggle, slideDown, fadeIn, fadeOut, slideUp
                    $(".container").fadeIn(400);
                    
                });
                
            });
            
            
            
            //function que carrega a pafina modal.php dentro da div modal do form.php
            
            /*idItem que é passado no onclick do link que chama a modal*/
            function modal(idItem){    
                    
                // a bilioteca ajax força um POST ou GET para uma pagina, sem precisa atualizar a pagina
                
                $.ajax({
                    type: "GET", //type - especifica o metodo
                    url: "modal.php", //url - especifica a página que será requisitada
                    data:{idRegistro:idItem}, //data - cria variaveis que serão submetidas (GET/POST) para a página requisitada
                    
                    
                    //success - caso toda a requisição seja realizada com exito, então a function do succes será chamada e através do parametro dados, iremos descarregar a div (modal)  no conteudo de dados
                    
                    success: function(dados){ 
                        //alert(dados); - para ver os erros, caso haja
                        
                        $('.modal').html(dados);
                    }
                })
            }
            
            
            
        </script>
        
        <script>
            function validar(caracter, tipoBloqueio, campo){
                document.getElementById(campo).style="background-color: white;";
                var nome;
                
                //verifica qual navegador está sendo usado
                if(window.event){
                    //charCode pega o numero ascii do elemento clicado 
                    var caracter = caracter.charCode; 
                }else{
                    //charCode pega o numero ascii do elemento clicado 
                    var caracter = caracter.which;
                }
                
                
                
                if(tipoBloqueio == "number"){
                    //Bloqueio de números
                    if(caracter >= 48 && caracter <=57){
                        
                        document.getElementById(campo).style="background-color: red;";
                        
                        //nesse caso o return false volta para a caixa de texto que chama a função cancela a ação da tecla
                        return false;                        
                    }     
                    
                }else if(tipoBloqueio == "caracter"){
                    //Bloqueio de números
                    if(caracter < 48 || caracter >57){
                        document.getElementById(campo).style="background-color: red;";
                        
                        //nesse caso o return false volta para a caixa de texto que chama a função cancela a ação da tecla
                        return false;
                    }                       
                }
                

                
                
                
            }
        </script>
        
    </head>
    
    <body>
<!--        
    type para formulário em html 5
        tel, date, month, week, email, range, number, color, url

-->
        
<!--        Códif=go para gerar a tela da modal -->
        <div class="container">
            <div class="modal">
            
            </div>
        </div>
        
        
                                                
        <div class="caixaPrincipal">
            <div class="caixaForm">
                <form action="form.php" method="get">
                    <div class="caixa_inputs_esquerda">
                        <p>Nome: <input type="text" id="txtNome" name="txtNome" value="<?php echo($nome) ?>" onkeypress="return validar(event, 'number', this.id)" required></p>
                        <p>Telefone: <input type="text" id="txtTelefone"  value="<?php echo($telefone) ?>" name="txtTelefone" onkeypress="return validar(event, 'caracter', this.id)" required></p>
                        <p>Dt.Nasc: <input type="text" name="txtDtNasc" placeholder="ex: xx-xx-xxxx"  value="<?php echo($dtNasc) ?>"></p>
                    </div>

                    <div class="caixa_inputs_direita">
                        <p>Email: <input type="email" name="txtEmail"  value="<?php echo($email) ?>"></p>
                        
<!--                        a tag pattern serve para fazer as expressões regulares-->
                        <p>Celular: <input type="text" name="txtCelular" placeholder="ex. 000 90000-0000" pattern="[0-9]{3} [0-9]{5}-[0-9]{4}"  value="<?php echo($celular) ?>"></p>
                        Obs: <textarea name="txtObs" cols="10" rows="4"><?php echo($obs) ?></textarea>
                    </div>

                    <p><input type="submit" name="btnSalvar" value="<?php echo($botao);?>"></p>
                </form>   


            </div> 
            
            <div class="caixaConsulta">
                <div class="caixaConsulta_titulo">
                    Consulta de Contatos
                </div>
                
                <div class="caixaConsulta_tabela">
                    <table width="100%" border="1px">
                        <tr>
                            <td width="100px">Nome</td>
                            <td width="150px">Telefone</td>
                            <td width="300px">Celular</td>
                            <td width="100px">Email</td>
                            <td width="100px">DtNasc</td>
                            <td width="300px">Opções</td>
                        </tr>

                        
                        <?php
                            $sql = "select * from tblcontatos order by codigo desc";
                        
                        //executa o comando no banco e guarda o retorno na variavel select
                            $select = mysqli_query($conexao, $sql);
                        
                        
                        //mysqli_fetch_array
                        //mysqli_fetch_assoc
                        //mysqli_fetch_object
                        //Convertem o resultado do banco em um formato conhecido para o PHP
                        //poder extrair as informações
                            while($rsContatos = mysqli_fetch_array($select)){
                                
                            
                        ?>                        
                        <tr>
                            <td><?php echo($rsContatos['nome']) ?></td>
                            <td><?php echo($rsContatos['telefone']) ?></td>
                            <td><?php echo($rsContatos['celular']) ?></td>
                            <td><?php echo($rsContatos['email']) ?></td>
                            <td><?php echo($rsContatos['data_nascimento']) ?></td>  
                            <td>
<!--                                link do excluir-->
                                <a href="form.php?modo=excluir&id=<?php echo($rsContatos['codigo'])?>">
                                    <img src="imagens/delete.png">
                                </a>
                                
<!--                                link do editar  -->
                                <a href="form.php?modo=buscar&id=<?php echo($rsContatos['codigo'])?>">
                                    <img src="imagens/edit.png">
                                </a>
                                
                                <!-- link do visualizar  -->
                                <a href="#" class="visualizar" onclick="modal(<?php echo($rsContatos['codigo']) ?>)">
                                    <img src="imagens/search.png">
                                </a>
                            </td>
                        </tr>         
                        
                        
                        
                        <!--
                        <a href="form.php?id=<?php echo($rsContatos['codigo'])?>">
                            a imagem que será o link
                        </a>    
                        -->
                        
                        
                        <?php
                            } //fecha o bloco do while
                        ?>
                        
                    </table>
                </div>
            </div>
        </div>


    </body>
</html>