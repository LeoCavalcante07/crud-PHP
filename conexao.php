<?php

    function conexaoDB(){
        $host = "localhost";
        $database = "dbcontatos2018inf3m";
        $user = "root";
        $password = "bcd127";
        
        
        //FAZ A CONEXÃO COM O BANCO DE DADOS MYSQL, USANDO A BIBLIOTECA mysqli
        //$conexao = mysqli_connect($host,$user,$password,$database

        //SE A CONEXAO FOSSE FEITA COM A BIBLIOTECA mysql_connect:
        //mysql_connect($host,$user,$password)
        //mysql_selectdb

        //var_dump($conexao);        
        
        
        if(!$conexao = mysqli_connect($host,$user,$password, $database)){
            echo('Ocorreu um erro na conexão  com o banco');            
        }        
        
        return $conexao;
    }

?>