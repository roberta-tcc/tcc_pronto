<?php
   
   if (isset($_GET["erro"]) && $_GET["erro"] == "1")
        $mensagem = "<br><span style='color: red;'>Usuário ou senha 
                       incorretos tente novamente</span>";
   else {
	  
	  if (isset($_GET["erro"]) && $_GET["erro"] == "2")
	    $mensagem = "<br><span style='color: green;'>Sua sessão 
	       expirou ou você está tentando acessar uma página
	       sem autorização! </span>";
	  else 
	     $mensagem = "";
   }
?>

<html>

<head>
	<title>Curso CRUD</title>
	<meta charset="utf-8" />

	<link rel='stylesheet' href="./css/index.css">
	<link rel='stylesheet' href="./css/formularios.css">


</head>

<body>
	
	<center>
	<form action="login.php" method="post">
	
	<fieldset>
	   <h1>Login</h1> 
	   <label for="login"><h3>Nome de Usuário:</h3></label> 
	   <input type="text" id="login" name="login">
	   <br>
	   <br>
	   
	   <label for="senha"><h3>Senha:</h3></label>
	   <input type="password" id="senha" name="senha"><br><br>
	   
	   <?php echo $mensagem; ?>

	   <input type="submit" value="Entrar">

	 </fieldset>
	</form>
	</center>
	
</body>

</html>
