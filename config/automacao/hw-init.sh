#!/usr/bin/env /bin/bash

baseDir="/usr/local/automacao"
configFile="${baseDir}/hw-config.txt"
cmd_gpio="/usr/bin/gpio -g"

num=0

while read linha
do
  semComentario=${linha/\#*/}
  semComentario=${semComentario## *}

  : $((++num))

  if [ -z "${semComentario}" ]
  then
    continue
  fi
  set $(echo ${linha})

  porta="$1"
  tipo="$2"

  shift; shift
  nome="$*"
  if [ -z "$nome" ]; then
    nome="Rele${porta}"
  fi

  if [ "$tipo" = "NA" ]; then
    tipo=0
  elif [ "$tipo" = "NF" ]; then
    tipo=1
  else
    echo "Linha ${num}: tipo desconhecido para o relé '$nome': '$tipo'" > /dev/stderr
    continue
  fi

  # Configura a porta GPIO como saída
  ${cmd_gpio} mode $porta output
  # Inicializa a porta no estado desligado (inverso de seu tipo)
  ${cmd_gpio} write $porta $((1 - tipo))
done < "${configFile}"
