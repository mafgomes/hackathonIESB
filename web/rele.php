<?php
  require_once("gpio_defs.php");
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
  <head>
    <meta charset="utf-8">
  </head>
  <body>
<?php
  // O que será retornado (em princípio, nenhum erro)
  $retorno = "";

  // Número da porta GPIO onde o relé está ligado
  $porta = $_REQUEST['porta'];

  // Crítica a entrada, para não permitir nada além de dígitos
  $porta = preg_replace("/\D/", '', $porta);

  // Verifica se a porta é uma das portas em uso
  unset($rele);
  foreach( $gpio_conf['reles'] as $rele ) {
    if( $rele['num'] == $porta )
      break;
  }
  if( $rele['num'] != $porta ) {
    $retorno = "Porta inválida.";
  }

  // Parâmetro indicando se é para ligar (1) ou desligar(0) o relé
  $ligar = $_REQUEST['ligar'];

  // Se o parâmetro for '-', inverte o estado atual:
  if( $ligar === "-" ) {
    $ligar = (exec($gpio_conf['read'] . $porta) == "0") ? "1" : "0";
  } else {
    if( $ligar == 1 )
      $ligar = $rele['tipo'];
    else
      $ligar = 1 - $rele['tipo'];
  }

  echo("porta: $porta<br/>ligar: $ligar<br/>\n");

  // Crítica a entrada, para não permitir nada além de dígitos
  $ligar = preg_replace("/\D/", '', $ligar);

  // echo("porta: $porta<br/>ligar: $ligar<br/>\n");

  // Ligar só pode ser 0 ou 1; qualquer outro valor é convertido
  if( ! is_numeric($ligar) || ($ligar != 0 && $ligar != 1) ) {
    $retorno = "No parâmetro 'ligar', use apenas 1 ou 0, "
    . "significando ligar (1) ou desligar (0)";

  // Se já houver algo a retornar, os parâmetros não estão ok
  } else if( strlen($retorno) == 0 ) {
    // Tudo parece estar ok; vamos comandar o relé

    // Monta o comando com os parâmetros fornecidos
    $cmd = $gpio_conf['write'] . "$porta $ligar 2> /dev/null";
    echo("$cmd<br/>\n");

    // Executa o comando
    system($cmd, $retorno);
  }

  // Só está tudo ok se $retorno for numérico e igual a zero
  if( is_numeric($retorno) && $retorno == 0 )
    echo("OK");
  else
    echo("ERRO: $retorno");
?>
  </body>
</html>
