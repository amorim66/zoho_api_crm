<?php
class conexao {
    // Coloque aqui as Informações do Banco de Dados
   // var $host = "localhost"; // Nome ou IP do Servidor
  //  var $user = "futuroha_userLoj"; // Usuário do Servidor MySQL
  //  var $senha = "MRZ0HK8RdgQD"; // Senha do Usuário MySQL
  //  var $dbase = "futuroha_admin"; // Nome do seu Banco de Dados

}

$servidor = "localhost";//Geralmente é localhost mesmo
$nome_usuario = "aizpartsgabrielb_admin";//Nome do usuário do mysql
$senha_usuario = "y1NgEpKeruXnliyeHI"; //Senha do usuário do mysql
$nome_do_banco = "aizpartsgabrielb_loja"; //Nome do banco de dados

$conecta1 = mysql_connect("$servidor", "$nome_usuario", "$senha_usuario", TRUE) or die (mysql_error());
$banco1 = mysql_select_db("$nome_do_banco",$conecta1) or die (mysql_error());

?>
