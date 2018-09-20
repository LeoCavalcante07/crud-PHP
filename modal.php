<?php


    require_once('conexao.php');
    $conexao = conexaoDB();

    $codigo = $_GET['idRegistro'];

    $sql = "select * from tblcontatos where codigo =".$codigo;

    $select = mysqli_query($conexao, $sql);

    
    if($rsContatos = mysqli_fetch_array($select)){
        $nome = $rsContatos['nome'];
        $telefone = $rsContatos['telefone'];
        $celular = $rsContatos['celular'];
        $email = $rsContatos['email'];
        $dt_nasc = $rsContatos['data_nascimento'];
        $obs = $rsContatos['obs'];
    }

?>



<html>
    <head>
        <title>Modal</title>
        <script src="js/jquery.js"></script>
        
        <script>
            $(document).ready(function(){
                //function para fechar a modal
                
               $('.fechar').click(function(){
                  $('.container').fadeOut(400);
               });
            });
        </script>
    </head>
    <body>
        <a href="#" class="fechar">Fechar</a>
        <table width="800px" border="1px">
            <tr>
                <td>
                    Nome:
                </td>
                
                <td>
                    <?php echo($nome)?>
                </td>
            </tr>
            
            <tr>
                <td>
                    Telefone:
                </td>
                
                <td>
                    <?php echo($telefone)?>                
                </td>
            </tr>
            
            <tr>
                <td>
                    Celular:
                </td>
                
                <td>
                    <?php echo($celular)?>                
                </td>
            </tr>
            
            <tr>
                <td>
                    Email:
                </td>
                
                <td>
                    <?php echo($email)?>                
                </td>
            </tr>
            
            <tr>
                <td>
                    Data de Nascimento:
                </td>
                
                <td>
                   <?php echo($dt_nasc)?>                
                </td>
            </tr>
            
            <tr>
                <td>
                    obs:
                </td>
                
                <td>
                   <?php echo($obs)?>                
                </td>
            </tr>            
        </table>
    </body>
</html>