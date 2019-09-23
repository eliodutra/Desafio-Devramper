<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <script src="/bootstrap/js/jquery-3.4.0.js"></script>
        <script src="/bootstrap/js/bootstrap.js"></script>
        <link rel="stylesheet" href="/bootstrap/css/bootstrap.css">
        
        <title>DEVRAMPER</title>
        
        <?php
           function erroVolta( $texto ) {
              echo "<script>";
              echo "  alert('" . $texto . "');";
              echo "  history.back();";
              echo "</script>";
              die;
           }
           function alerta( $texto ) {
              echo "<script>";
              echo "  alert('" . $texto . "');";
              echo "</script>";
           }
           function alertaAbre( $texto, $link ) {
              echo "<script>";
              echo "  alert('" . $texto . "');";
              echo "  window.location.href = " . $link . ";";
              echo "</script>";
              die;
           }
           
           function montaForm( $aCampos, $link, $sOper, $linkExclui, $titulo ) {
           
                  if( !empty($titulo) ) {
                     echo '<div class="container-fluid">';
                     echo '<h1>' . $titulo . '</h1>';
                     echo '<form method POST action="' . $link . '">';
                     echo 'Busca: ';
                     echo '<input type=hidden name="oper"  id="oper"  value="busca">';
                     echo '<input type=text   name="busca" id="busca" >';
                     echo ' <input type=submit value=" ok ">';
                     echo '</form><hr>';
                     echo '</div>';
                 }

                 echo '<div class="container-fluid">';
                 echo '<form method="POST" action="' . $link . '">';
                 echo '<input type=hidden name="oper"  id="oper"  value="' . $sOper . '">';
                 echo '<div class="row" style="margin-left: 0px; margin-right: 0px;">';

                 foreach($aCampos as $cpo){

                     echo '<div class="col-md-'. $cpo['size'] .' col-sm-'. $cpo['size'] .' col-xs-12 position-label" style="white-space: nowrap;">';
                 
                     if( isSet($cpo['label']) ) {
                         echo '<label class="control-label padding-label"> ' . $cpo['label'] . ' </label>';
                     }
                     if( $cpo['type'] == 'select' ) {
                         echo '<select name="' . $cpo['field'] . '" id="' . $cpo['field'] . '"  class="form-control estilo-select " style="padding: 3px; border: 1px solid #f56e1e;"';
                         if( isSet($cpo['ajx']) ) {
                             echo ' ' . $cpo['ajx'][0];  // evento ex. onchage
                         }
                         echo '>';
                         
                         $tCpo = count($cpo['options']);
                         for( $nC = 0; $nC < $tCpo; $nC++ ) {
                              echo '<option value="' . $cpo['options'][$nC][0] . '"';
                              if( isSet($cpo['value']) ) {
                                  if( $cpo['options'][$nC][0] == $cpo['value'] ) {
                                      echo ' selected ';
                                  }
                              }
                              echo '>' . $cpo['options'][$nC][1] . '</option>';
                         } 
                         echo '</select>';
                         
                         if( isSet($cpo['ajx']) ) {
                             // echo 'evento: '. $cpo['ajx'][0];
                             echo ' ' . $cpo['ajx'][1];  // script do evento 
                         }

                     } else if( $cpo['type'] == 'textarea' ) {
                     
                         echo '<textarea class="form-control caixa_texto" name="' . $cpo['field'] . '" id="' . $cpo['field'] . '" style="border: 1px solid #f56e1e;"';
                         if( isSet($cpo['edit']) ) {
                             if( !$cpo['edit'] ) {
                                 echo ' readonly ';
                             }
                         }
                         if( isSet($cpo['rows']) ) {
                             echo ' rows=' . $cpo['rows'];
                         }
                         if( isSet($cpo['ajx']) ) {
                             echo ' ' . $cpo['ajx'][0];  // evento ex. onchage
                         }
                         echo '>';  // fecha tag input
                         if( isSet($cpo['value']) ) {
                             echo $cpo['value'];
                         }
                         echo '</textarea>';  // fecha tag 
                         if( isSet($cpo['ajx']) ) {
                             // echo 'evento: '. $cpo['ajx'][0];
                             echo ' ' . $cpo['ajx'][1];  // script do evento 
                         }
                     
                     } else if( $cpo['type'] == 'html' ) {
                         if( isSet($cpo['value']) ) {
                             echo $cpo['value'];
                         }

                     } else {  // if( $cpo['type'] == 'text' or $cpo['type'] == 'textarea' or $cpo['type'] == 'hidden' or $cpo['type'] == 'date') {
                         echo '<input  class="form-control caixa_texto" type="' . $cpo['type'] . '" name="' . $cpo['field'] . '" id="' . $cpo['field'] . '"';
                         
                         if( isSet($cpo['edit']) ) {
                             if( !$cpo['edit'] ) {
                                 echo ' readonly ';
                             }
                         }
                         if( isSet($cpo['value']) ) {
                             echo ' value="' . $cpo['value'] . '"';
                         }

                         echo ' style="border: 1px solid #f56e1e;';
                         if( isSet($cpo['style']) ) {
                             echo $cpo['style'];
                             alerta('com style' .  $cpo['field'] .': '.  $cpo['style']);
                         } else {
                             alerta('sem style' .  $cpo['field']);
                         }
                         echo '"';
                         
                         if( isSet($cpo['ajx']) ) {
                             echo ' ' . $cpo['ajx'][0];  // evento ex. onchage
                         }
                         echo '>';  // fecha tag input
                         
                         if( isSet($cpo['ajx']) ) {
                             // echo 'evento: '. $cpo['ajx'][0];
                             echo ' ' . $cpo['ajx'][1];  // script do evento 
                         }
                     

                     }
                     echo '</div>';   // col-md
                 }
                 
                 echo ' <input type=submit class="btn btn-success" value="' . $sOper . '">';
                 echo '</form>';
                 
                 if( !empty($linkExclui) ) 
                     echo $linkExclui;
                     
                 echo '</div>';   // row    
                 echo '<hr size=2 />';
                 echo '</div>';   // container-fluid
           }

        ?>
    </head>
    
    <body style="color:#f56e1e ; background:white">'
       <div class="container-fluid">
           <img src="img/devramper.png">
           <br />
       </div>
