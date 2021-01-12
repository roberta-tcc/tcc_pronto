<?php include_once("validar_sessao.php"); ?>

<!DOCTYPE html>

<html>

<head>
	<title>TCC</title>
	<meta charset="utf-8" />
	<link rel='stylesheet' href="./css/menu.css">	
	<link rel='stylesheet' href="./css/formularios.css">	
	<link rel='stylesheet' href="./css/cad.css">

	
</head>

<body>
	
	<?php 
	    include_once("conectar.php");
	    include_once("funcoes.php");
	    include_once("monta_menu.php"); 
	    
	    $mensagem = "";
	    $tabela = "";
	    
	    /*
	     ==> Declarar uma variável para cada campo da tabela
	    */

	    $id_vacinado  = "";
	    $data         = "";
	    $id_vacina    = "";
	    $id_lote      = "";
	    
	   $id_usuario = $_SESSION["id_usuario"];
	    $tipo = $_SESSION["tipo"];

	    if ($tipo == "C")
	    	$sqlExtra = " and lote.id_usuario = $id_usuario";
	    else
			$sqlExtra = "";

	    $podeAlterar = "";
	    

	    if ( ! isset($_POST["acao"] ) ) {
	        $descr_acao = "Incluir";
		} else {
			 $acao = $_POST["acao"];
			 
			 if ( strtoupper($acao) == "INCLUIR" || 
			      strtoupper($acao) == "SALVAR" ) {
	            /*
	               ==> Atribur os dados recebidos por POST às variáveis (exceto a chave primária)
	            */					  
				$data      = mysqli_real_escape_string($bd, $_POST["data"]);
				$id_vacina = mysqli_real_escape_string($bd, $_POST["id_vacina"]);
				$id_lote   = mysqli_real_escape_string($bd, $_POST["id_lote"]);
			 }
			 
			 if ( strtoupper($acao) == "SALVAR" || 
			      strtoupper($acao) == "EXCLUIR" || 
			      strtoupper($acao) == "BUSCAR") { 
			    
			    /*
	               ==> Atribur a chave primária
	            */
			    $id_vacinado = $_POST["id_vacinado"];
			 }
			 
			 if (strtoupper($acao) == "INCLUIR") {
				
				/*
	               ==> Montar um comando SQL do tipo INSERT declarando todas as colunas e valores 
	                  (observe a necessidade de aspas em campos do tipo caracter e do tipo data.
	            */
			    $sql = "insert into vacinado  
			                 (data, id_vacina, id_lote)
			            values 
			                 ('$data', $id_vacina, $id_lote)";
			    
			    if ( ! mysqli_query($bd, $sql) ) {
					
				    /*
	                    ==> Se sua tabela tiver um campo UNIQUE (ex. login, CPF, nº de matrícula, etc., configure
	                        a mensagem abaixo (a mensagem vai aparecer quando houver uma duplicação de valor não autorizada)
	                */					
					
					if ( mysqli_errno($bd) == 1062 ) {
						$mensagem = "<h3>Prezado usuário o valor escolhido '$id_vacinado' já está sendo utilizado. Escolha outro!</h3>";
					} else {
						$mensagem = "<h3>Ocorreu um erro ao inserir os dados: </h3> <h3>".mysqli_error($bd)."</h3> <h4>".mysqli_errno($bd)."</h4>";
				    }

					$descr_acao = "Incluir";
				
				} else {
			    	$descr_acao = "Salvar";
			    	/*
	                    ==> O comando mysqli_insert_id pega o último valor gerado pelo BD
	                */
			    	$id_vacinado = mysqli_insert_id($bd);
				}
				 
			 } else if (strtoupper($acao) == "SALVAR") {
				 
				 $descr_acao = "Salvar";
				 
				 /*
	               ==> Montar um comando SQL do tipo UPDATE para fazer a atualização dos dados que podem ser alterados (não é obrigatório usar todos)
	                  (observe a necessidade de aspas em campos do tipo caracter e do tipo data.
	            */
				 
				 $sql = " update vacinado  
				          set
				            data      = '$data',
				            id_vacina = $id_vacina,
				            id_lote   = $id_lote 
				            
				          where
				            id_vacinado = $id_vacinado";
				            
				 if ( ! mysqli_query($bd, $sql) ) {
					 
				    /*
	                    ==> Se sua tabela tiver um campo UNIQUE (ex. login, CPF, nº de matrícula, etc., configure
	                        a mensagem abaixo (a mensagem vai aparecer quando houver uma duplicação de valor não autorizada)
	                */					 
					
					if ( mysqli_errno($bd) == 1062 ) {
						$mensagem = "<h3>Prezado usuário o valor escolhido '$id_vacinado' já está sendo utilizado. Escolha outro!</h3>";
					} else {
						$mensagem = "<h3>Ocorreu um erro ao alterar os dados: </h3> <h3>".mysqli_error($bd)."</h3>".$sql. "<h4>".mysqli_errno($bd)."</h4>";
				    }
				}         
			 } else if (strtoupper($acao) == "EXCLUIR") {
				 
				 $descr_acao = "Incluir";
				 
				 /*
	               ==> Ajustar o comando para fazer o delete
	             */
				  
				 $sql = "delete from vacinado where id_vacinado = $id_vacinado";
				 
				 mysqli_query($bd, $sql);
				 
			 } else if (strtoupper($acao) == "BUSCAR") {
				 
				 $descr_acao = "Salvar";
				 
				 /*
	               ==> Ajustar o comando para buscar um único registro a partir da chave primária
	             */
				 
				 $sql = "select * from vacinado where id_vacinado = $id_vacinado";
				 
				 $resultado = mysqli_query($bd, $sql);
				 
				 if ( mysqli_num_rows($resultado) == 1 ) {
					 
					$dados = mysqli_fetch_assoc($resultado);
					
					/*
	                   ==> Atribur os dados recebidos do Banco de Dados às variáveis (inclusive a chave primária)
	                */
	 
					$id_vacinado = $dados["id_vacinado"];
					$data        = $dados["data"];
					$id_vacina   = $dados["id_vacina"];
					$id_lote     = $dados["id_lote"];
				 }
			 }
		}
		
		/*
	        ==> Ajustar o comando para listar todos os dados existentes
	    */		
		$sql_listar = "select vacinado.*, vacina.nome, lote.num_lote, usuario.nome as nome_usuario, nome_doenca
		               from vacinado, vacina, lote, usuario, doenca
		               where vacinado.id_vacina = vacina.id_vacina and
		                     vacinado.id_lote = lote.id_lote and
		                     lote.id_usuario = usuario.id_usuario and
		                     doenca.id_doenca = vacina.id_doenca
		               $sqlExtra	
		               order by data";

		$lista = mysqli_query($bd, $sql_listar);
		
		if ( mysqli_num_rows($lista) > 0 ) {
			
		     /*
	           ==> Definir quais serão as informações que devem ser listadas na tabela 
	               (observe que as duas últimas colunas são os botões de alterar e excluir, se não for possível excluir, retire o botão)
	         */				
			
			$tabela = "<table border=1>";
			$tabela = $tabela."<tr><th>Data</th><th>Medicamento</th><th>Lote</th><th>Alterar</th><th>Excluir</th></tr>";
			
			while ( $dados = mysqli_fetch_assoc($lista)) {
				
			    /*
	                ==> Definir nomes de variáveis que irão receber os dados do BD e listá-los na tabela
	                    Sugestão ... use a letra "v" na frente (estes nomes de variáveis devem ser distintos das variáveis declaradas anteriormente)
	            */	
				
				$vIdVacinado  = $dados["id_vacinado"];
				$vData        = $dados["data"];
				$vData        = date( 'd-m-Y' , strtotime( $vData ) );
				$vIdVacina    = $dados["id_vacina"];
			    $vNomeVacina  = $dados["nome"];
				$vLote        = $dados["id_lote"];
				$vNumLote     = $dados["num_lote"];
				$vNomeUsuario = $dados["nome_usuario"];
				$vNomeDoenca  = $dados["nome_doenca"];
				
				//imagem do botão ALTERAR (ele vai buscar os dados a partir da chave primária e posicioná-los na tela)
				$alterar = "<center><form method='post'>
				               <input type='hidden' name='id_vacinado' value='$vIdVacinado'>
				               <input type='hidden' name='acao' value='BUSCAR'>
				               <input type='image' src='./img/alterar.gif' value='submit'>
				            </form></center> ";
				
				//imagem do botão EXCLUIR (ele vai excluir os dados a partir da chave primária)            
				$excluir = "<center><form method='post'>
				               <input type='hidden' name='id_vacinado' value='$vIdVacinado'>
				               <input type='hidden' name='acao' value='EXCLUIR'>
				               <input type='image' src='./img/excluir.gif' value='submit'>
				            </form></center>";
				            
			    /*
	                ==> Fique atento à ordem dos valores (respeite a mesma ordem dos títulos das colunas)
	            */
				$tabela = $tabela."<tr><td>$vData</td><td>$vNomeVacina - $vNomeDoenca</td><td>$vNumLote - $vNomeUsuario</td><td>$alterar</td><td>$excluir</td></tr>";
				            
			}
			
			$tabela = $tabela."</table>";
			
		} else {
			$tabela = "não há dados para listar";
		}

        /*
	       ==> Utilize essas três variáveis se você quer construir um menu suspenso (comboBox) para escolha de valores
	    */
   	
   		/*$sql_racao  = "select id_racao, concat(nome, ' (', codigo, ')') FROM racao order by nome";*/

		$sql_vacina = "select id_vacina, concat(nome, ' (', nome_doenca,')')  from vacina, doenca where vacina.id_doenca = doenca.id_doenca
			order by nome";
		
		$vacinaOpcoes = montaSelectBD($bd, $sql_vacina, $id_vacina, false);
	    
	    $sql_lote = "select id_lote, concat(num_lote, ' (', usuario.nome,')')  from lote, usuario where usuario.id_usuario = lote.id_usuario and
	        id_lote > 0 $sqlExtra order by num_lote";
		
		$LoteOpcoes = montaSelectBD($bd, $sql_lote, $id_lote, false);

	    mysqli_close($bd);
	?>
	
	<center><h1>Cadastro de Suínos Medicados</h1></center>
	
	<?php echo $mensagem; ?>
	
	<form method="post">
      <fieldset>
	    <center>
	        
	    <label for="data" class="a"><h3>Data:</h3> </label>
	    <input type="date" id="data" name="data" value="<?php echo $data; ?>" <?php echo $podeAlterar; ?> > <br>
	  
	    <label for="id_vacina" class="a"><h3>Medicamento:</h3> </label>
	    <select id="id_vacina" name="id_vacina">
	    	<?php echo $vacinaOpcoes; ?>
	    </select> <br>

	    <label for="id_lote" class="a"><h3>Lote:</h3> </label>
		<select id="id_lote" name="id_lote" <?php echo $podeAlterar; ?> >
	    	<?php echo $LoteOpcoes; ?>
	    </select><br>	    
   
	    <input type="hidden" name="id_vacinado" value="<?php echo $id_vacinado; ?>">
	      <input type='submit' value='Novo' <?php echo $podeAlterar; ?> > 
	  <input type="submit" tabindex="1" name="acao" value="<?php echo $descr_acao; ?>" <?php echo $podeAlterar; ?> >
	</form>  
</center>
	  </fieldset>
	
	   <center><legend><h2>Suínos Medicados Cadastrados</h2></legend> 
	  
	   <?php
	      echo $tabela;
	   ?>
	   
</body>

</html>