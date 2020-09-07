<?php
  $DEBUG=0;
  require_once("gpio_defs.php");
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
  <head>
    <meta charset="utf-8">
    <title> Automação </title>
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
  <body>
    <iframe id="nada"></iframe>
    <!--
      Para esconder o iframe usado, podemos usar o estilo a seguir.
      Em princípio, não usamos este estilo para poder demonstar
      o que está rolando "por baixo dos panos".

      <iframe id="nada" style="display:none;"></iframe>
    -->

    <form method="get" action="/hack/rele.php" id="aciona">
      <input type='hidden' id='ligar' name='ligar' value='0'/>
      <input type='hidden' id='porta' name='porta' value='0'/>
<?php
    if( $DEBUG ) {
      echo("<pre>\n");
      print_r($gpio_conf);
      echo("</pre>\n");
    }

    $reles = $gpio_conf['reles'];
    $i = count($reles) - 1;

    while( --$i >= 0 ) {
      $rele = $reles[$i];

      if( $DEBUG ) {
	echo("<pre>\n");
	print_r($rele);
	echo("</pre>\n");
      }

      // Resolve um bug do interpretador PHP do Raspberry Pi,
      // em que o foreach repete o penúltimo elemento do array
      // no lugar em que deveria ser o último
      if( $rele['num'] == 99 )
	break;

      // Cria o checkbox para o relé
      echo('        <input type="checkbox" ');
      echo('onclick="comanda(this.id, this.checked);" ');
      echo('id="Rele' . $rele['num'] . '"');
      // Verifica se o rele em questão está ativo
      // repare que, a depender do tipo, NA ou NF, 
      // a definição de "ativo" é invertida
      if( exec($gpio_conf['read'] . $rele['num']) != $rele['tipo'] )
	echo(' checked');
      echo(">\n");

      echo('        <label for="Rele' . $rele['num'] . '"> ');
      echo($rele['nome']);
      echo(" </label><br>\n");
    }
?>
    </form>
    <pre>
    <?php exec("./sensores.py"); ?>
    </pre>
  </body>
</html>
<!-- vim: set syntax=php ts=2 sw=2 ai ic sts=2 sr noet -->
