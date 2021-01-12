<ul class="nav nav-pills nav-fill">

  
  <?php
     if ( $_SESSION["tipo"] == "A" )
      echo "<li><a class='nav-link' href='cad_usuario.php'>Cadastro de Usuários</a></li>";
     else 
        echo "<li><a class='nav-link' href='cad_usuario.php'>Meus dados</a></li>";
    
  ?>

  <li class="nav-item"><a class="nav-link" href="cad_lote.php">Cadastro de Lotes</a></li>

  <?php
     if ( $_SESSION["tipo"] == "A" )
        echo "
          <li class='dropdown'>
            <a href='#' class='dropbtn'>Cadastros Básicos</a>
            <div class='dropdown-content'>
              <a href='cad_racao.php'>Cadastro de Ração</a>
              <a href='cad_fornecedor.php'>Cadastro de Fornecedores</a>
              <a href='cad_doenca.php'>Cadastro de Doenças</a>
              <a href='cad_vacina.php'>Cadastro de Medicamento</a>

            </div>
          </li>";
    
  ?>  
  
  <li class="nav-item"><a  class="nav-link" href="cad_vacinado.php">Cadastro de Suínos Medicados</a></li>
  <li class="nav-item"><a class="nav-link" href="cad_recebimento_racao.php">Recebimento de ração</a></li>
  <li class="nav-item"><a class="nav-link" href="cad_morte.php">Cadastro de mortes</a></li>
  

  <li><a class="nav-link" href="sair.php">Sair</a></li>
  
</ul> 


  
