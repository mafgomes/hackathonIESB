<?php
  $config_file = file("/usr/local/automacao/hw-config.txt");

  $gpio_conf = array(
    // Comando para atuar sobre as portas GPIO
    'write' => "gpio -g write ",

    // Comando para ler o estado atual das portas GPIO
    'read' => "gpio -g read ",

    // Array dos relés configurados no sistema
    'reles' => array(
      // Cada elemento é, em si, um array,
      // consistindo nos seguintes campos:

      // 'num' => numero da porta GPIO que comanda este rele

      // 'tipo' => tipo de conexao ao rele:
      // 0: NA (normalmente aberto)
      // 1: NF (normalmente fechado)

      // 'nome' => Nome dado pelo usuário à carga deste relé
    )
  );

  $line_num = 0;
  unset($config_line);
  unset($spl);

  foreach( $config_file as $config_line ) {
    ++$line_num;

    // Retira os comentários da linha
    $config_line = preg_replace("/#.*/", "", $config_line);

    // Retira os espaços do começo da linha
    $config_line = preg_replace("/^\s*/", "", $config_line);

    // Retira os espaços do final da linha
    $config_line = preg_replace("/\s*$/", "", $config_line);

    // Se a linha ficou em branco, passa para a próxima
    if( preg_match("/^$/", $config_line) )
      continue;

    // Quebra a linha nos espaços em branco
    $spl = preg_split("/\s\s*/", $config_line);

    if( count($spl) < 2 ) {
      echo "Config, linha $line_num: erro de sintaxe!";
      continue;
    }

    // Número da porta GPIO conectada ao relé
    if( is_numeric($spl[0]) )
      array_push($gpio_conf['reles'], array(
	'num' => 0 + array_shift($spl)
      ));
    else {
      echo "Config, linha $line_num: número inválido!";
      continue;
    }

    $rele = &$gpio_conf['reles'][count($gpio_conf['reles'])-1];

    // Como estão ligados os pinos do relé:
    switch( array_shift($spl) ) {
      case 'NA':
	$rele['tipo'] = 1;
	break;
      case 'NF':
	$rele['tipo'] = 0;
	break;
      default:
	echo "Config, linha $line_num: Tipo desconhecido!";
	array_pop($gpio_conf['reles']);
	continue 2;
    }

    // Nome a ser atribuído a este relé
    if( isset($spl[0]) )
      // Nome dado pelo usuário
      $rele['nome'] = implode(' ',$spl);
    else
      // Nome genérico
      $rele['nome'] = "Relé da porta GPIO " . $rele['num'];

    // Agenda de liga/desliga do relé
    $rele['agenda'] = array(
      array(
        'nome'  => 'segunda',
        'ativa' => 0,
	'ini'   => '00:00:00',
	'fim'   => '00:00:00'
      ),
      array(
        'nome'  => 'terça',
        'ativa' => 0,
	'ini'   => '00:00:00',
	'fim'   => '00:00:00'
      ),
      array(
        'nome'  => 'quarta',
        'ativa' => 0,
	'ini'   => '00:00:00',
	'fim'   => '00:00:00'
      ),
      array(
        'nome'  => 'quinta',
        'ativa' => 0,
	'ini'   => '00:00:00',
	'fim'   => '00:00:00'
      ),
      array(
        'nome'  => 'sexta',
        'ativa' => 0,
	'ini'   => '00:00:00',
	'fim'   => '00:00:00'
      ),
      array(
        'nome'  => 'sábado',
        'ativa' => 0,
	'ini'   => '00:00:00',
	'fim'   => '00:00:00'
      ),
      array(
        'nome'  => 'domingo',
        'ativa' => 0,
	'ini'   => '00:00:00',
	'fim'   => '00:00:00'
      ),
    );

  }

  unset($config_file);
?>
<!-- vim: set syntax=php sw=2 ai ic sts=2 sr noet -->
