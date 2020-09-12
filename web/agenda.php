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
    <hr>
<?php
      // passthru("./sensores.py");
      //
      // Ler diretamente os sensores não funcionou,
      // já que a biblioteca para ler sensores é bugada
      // e tenta criar arquivos e diretórios no $HOME do
      // usuário que a invoca. Como o usuário em questão
      // é o usuário do Apache para acessar o sistema de
      // arquivos (www-data), isso não funciona.
      //
      // Solução: ler os sensores periodicamente,
      // via cron, guardando os valores num arquivo, e
      // exibindo-os aqui. Além desse arquivo, o script
      // rodado pelo cron mantém ainda hist_sensores.txt,
      // que guarda o histórico dos valores.
      // Precisa ser feito um mecanismo para evitar que
      // o crescimento do arquivo hist_sensores.txt não
      // acabe lotando o sistema de arquivos, e impeça
      // o bom funcionamento do sistema operacional.
      //
      $dados = file('/usr/local/automacao/sensores.txt');
      unset($dado);
      $dado = array_shift($dados);
      echo("Dados de $dado:<br>\n");
      // Mais tarde, pode ser interessante:
      //echo(date_create_from_format('U', $dado).format(
      //  'L, d/m/Y, H:i') . ":<br>\n");
      foreach( $dados as $dado ) {
	echo("&nbsp; &nbsp; $dado<br>\n");
      }
?>
  </body>
</html>
<?php // vim: set syntax=php ts=2 sw=2 ai ic sts=2 sr noet ?>
