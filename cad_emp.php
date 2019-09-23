<?php
    session_start();
   
    ini_set('display_errors', 1);
    include 'conecta_sql.inc';
    include 'head.inc';

    $oper = '';
    if(isSet($_POST['oper'])) {
       $oper = $_POST['oper'];
    } else if(isSet($_GET['oper'])) {
       $oper = $_GET['oper'];
    }
    // echo '<br />oper: [' . $oper . ']'; 

    
    if( $oper == 'acessa') {
        $cnpj  = $_GET['cnpj'];
        $sql = '';
        $sql = $sql . 'SELECT * from empresa where (cnpj = "'    . $cnpj . '" )';
        echo "sql: [" . $sql . "]";
                    
        $resultado = mysqli_query($conexao, $sql );
        $linhas    = mysqli_num_rows( $resultado );
        
        if( $linhas == 0 ) {
            echo '<br />Registro não encontrado ';
        }
    
    } elseif( $oper == 'busca') {
    
        $busca  = $_GET['busca'];
        $sql = '';
        $sql = $sql . 'SELECT * from empresa where (cnpj LIKE "%' . $busca . '%" or ';
        $sql = $sql .                              'nome LIKE "%' . $busca . '%")'   ;
        echo $sql;
                    
        $resultado = mysqli_query($conexao, $sql );
        $linhas    = mysqli_num_rows( $resultado );
        
        if( $linhas > 1 ) {
            echo '<br />Registro(s) encontrado(s):<br />';
            while( $linha  = mysqli_fetch_assoc($resultado) ) {
                   echo '<br /><a href="cad_emp.php?oper=acessa&cnpj=' . $linha['cnpj'] . '">' . $linha['cnpj'] . ' - ' . $linha['nome'] . '</a>';
            }

        } else if( $linhas == 1 ) {
            while( $linha  = mysqli_fetch_assoc($resultado) ) {
        
                   echo '<meta http-equiv="refresh" content="0; url=cad_emp.php?oper=acessa&cnpj=' . $linha['cnpj'] . '">';
                   die;
            }

        } else {
             echo '<br />Nenhum Registro encontrado ';
        }
        echo '<br />';
        die;
    }

    $aForm = array(
             array("label" => "CNPJ"    , "type" => "text"    , "field" => "cnpj"    , "edit" => true, "size" =>   3 ),
             array("label" => "CNAE"    , "type" => "text"    , "field" => "cnae"    , "edit" => true, "size" =>   2 ),
             array("label" => "Nome"    , "type" => "text"    , "field" => "nome"    , "edit" => true, "size" =>   6 ),
             array("label" => "Endereço", "type" => "textarea", "field" => "endereco", "edit" => true, "size" =>  11 ),
    );
    

    if($oper == 'grava') {

       $sql = 'UPDATE empresa set ';

       foreach( $aForm as $aCampos ) {
                if( $aCampos['field'] != 'cnpj' ) 
                    $sql = $sql . $aCampos['field'] . ' = "' . mysqli_real_escape_string($conexao, $_POST[$aCampos['field']]) . '", ' ; 
       }
       $sql =  substr($sql, 0, strlen($sql)-2);
       
       $sql = $sql . ' where cnpj = "' . $_POST['cnpj' ] . '"';
       echo "sql: [" . $sql . "]";
       
       mysqli_query($conexao, $sql );
       
       $linhas    = mysqli_affected_rows( $conexao );
       echo '<br />Você alterou ' . $linhas . ' registro';
       
    } else if($oper == 'novo') {

       $cnpj  = $_POST['cnpj'];
       $nome  = $_POST['nome'];
       $sql = '';
       $sql = $sql . 'SELECT * from empresa where (cnpj ="' . $cnpj . '" or ';
       $sql = $sql .                              'nome ="' . $nome . '")'   ;
       echo $sql;
                   
       $resultado = mysqli_query($conexao, $sql );
       $linhas    = mysqli_num_rows( $resultado );
       
       if( $linhas > 0 ) {
           alerta("Empresa já cadastrada!");
           
       } else {
    
           $sql = 'INSERT INTO empresa (';
    
           foreach( $aForm as $aCampos ) {
                    $sql = $sql . $aCampos['field'] . ', ' ; 
           }
           $sql =  substr($sql, 0, strlen($sql)-2) . ') VALUES (';
    
           foreach( $aForm as $aCampos ) {
                    $sql = $sql . '"' . $_POST[$aCampos['field']] . '", ' ; 
           }
           $sql = substr($sql, 0, strlen($sql)-2) . ')';
           echo "sql: [" . $sql . "]";
           
           mysqli_query($conexao, $sql );
           
           $linhas    = mysqli_affected_rows( $conexao );
           echo '<br />Você incluiu ' . $linhas . ' registro';
           if( $linhas <= 0 ) {
               alerta("Operacao não autorizada !");
           }
       }

    } else if($oper == 'apaga') {

       $cnpj  = $_GET['cnpj'];
       
       $sql = 'DELETE FROM empresa ';
       $sql = $sql . ' where cnpj = "' . $cnpj . '"';
       
       mysqli_query($conexao, $sql );
       
       $linhas    = mysqli_affected_rows( $conexao );
       echo '<br />Você excluiu ' . $linhas . ' registro';
       
    }
    
    montaForm($aForm, 'cad_emp.php', 'novo', '', 'Cadastro de Empresas');  

    if( $oper != "acessa" ) {
        $sql = 'SELECT * from empresa';
        $resultado = mysqli_query($conexao, $sql );
        $linhas    = mysqli_num_rows( $resultado );
    }
    
    if($linhas > 0) {
        
        while( $linha  = mysqli_fetch_assoc($resultado) ) { 
       
          $cnpj  = $linha['cnpj' ];

          $nLinForm = count($aForm);
           for($l = 0; $l < $nLinForm; $l++) {
               $aForm[$l]['value'] = $linha[$aForm[$l]['field']] ;
           }
          
          $formExclui = '<form action=cad_emp.php method=get id=exclui name=formexclui>'                            .
                        '<input type=hidden name=oper      id=oper     value=apaga>'                               .
                        '<input type=hidden name=cnpj      id=cnpj     value="' . $cnpj . '">'                .
                        '<input type=submit class="btn btn-danger"     value=exclui>' .
                        '</form>';

          
          montaForm($aForm, 'cad_emp.php', 'grava', $formExclui, '');

        } 
    }                     
    echo '<br />Num. regs.: ' . $linhas;
        
    include 'foot.inc';
?>
