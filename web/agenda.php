<?php
  $DEBUG=0;
  require_once("gpio_defs.php");
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
  <head>
    <meta charset="utf-8">
    <title> <?php
      $porta = $_REQUEST['porta'];
      unset($rele);
      foreach($gpio_conf['reles'] as $rele) {
	if( $rele['num'] == $porta )
	  break;
      }
      if( $rele['num'] != $porta ) {
        $back = 1;
	echo('Porta desconhecida');
      } else
	echo('Agendamento para ' . $rele['nome']);
    ?> </title>
    <script>
      function comanda(rele, ligado) {
	var valor;
	var iframe = document.getElementById('nada');

	if( ligado )
	  valor = 0;
	else
	  valor = 1;

	iframe.src = "/hack/rele.php?ligar=" 
	  + valor + "&porta=" + rele;
      }
    </script>
  </head>
  <body<?php if( $back == 1 )
    echo(' onload="window.history.back();" ');
  ?>>
    <?php if( $DEBUG ) { ?>
      <iframe id="nada"></iframe>
    <?php } else { //if( $DEBUG ) ?>
      <!--
	Para esconder o iframe usado, podemos usar esse estilo.
      -->
      <iframe id="nada" style="display:none;"></iframe>
    <?php } //if( $DEBUG ) ?>

    <form method="post" action="/hack/rele.php" id="aciona">
      <input type='hidden' id='ligar' name='ligar' value='0'/>
      <input type='hidden' id='porta' name='porta' value='0'/>
<?php
    if( $DEBUG ) {
      echo("<pre>\n");
      print_r($gpio_conf);
      echo("</pre>\n");
    }

    unset($agenda);
    foreach($rele['agenda'] as $agenda) {

      if( $DEBUG ) {
	echo("<pre>\n");
	print_r($agenda);
	echo("</pre>\n");
      }

      unset($key); unset($val);
      foreach($agenda['def'] as $key => $val) {
	echo('<label>' . $key );
	echo(' <input type="text" id="');
	echo($agenda['id'] . $key);
	echo('">');
	echo($val);
	echo("</input></label>\n");
      }
      echo("<br>\n");
      // Cria o checkbox para o dia da semana
      echo('        <input type="checkbox" ');
      echo('onclick="agendar(this.id, this.checked);" ');
      echo('id="ag' . $agenda['nome'] . '"');
      // Verifica se o rele em questão está ativo
      // repare que, a depender do tipo, NA ou NF, 
      // a definição de "ativo" é invertida
      if( exec($gpio_conf['read'] . $rele['num']) != $rele['tipo'] )
	echo(' checked');
      echo(">\n");

      echo('        <label for="ag' . $agenda['nome'] . '"> ');
      echo($agenda['nome']);
      echo(" </label><br>\n");
    }
?>
    </form>
  </body>
</html>
<?php // vim: set syntax=php ts=2 sw=2 ai ic sts=2 sr noet ?>
