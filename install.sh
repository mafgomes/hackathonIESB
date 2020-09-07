#!/bin/bash

dest="/usr/local/automacao"
docRoot="/var/www/html"
temp="/tmp/automacao_temp.txt"
hw_init="${dest}/hw-init.sh"

trap 'rm "${temp}"' EXIT ERR INT QUIT HUP

# Copia os fontes PHP para o DocumentRoot do Apache
mkdir -p "${docRoot}/hack" || :
(cd web && tar cpf - .) | \
  (cd "${docRoot}/hack"; tar xpf -)

# Copia os arquivos de configuração
cd config

# Primeiro a configuraçao do sistema de automação em si
mkdir -p /usr/local/automacao || :
(cd automacao && tar cpf - .) | \
  (cd /usr/local/automacao; tar xpf -)

# em seguida a configuração do Apache
cd etc
(tar cpf - apache2) | (cd /etc; tar xpf -)

# Inicializa as portas GPIO do hardware
"${dest}/hw-init.sh"

# Reinicia o Apache com a nova configuração
systemctl restart apache2.service

# Ajusta a inicialização do sistema
ja_no_rc_local=$(grep "${dest}" /etc/rc.local)
if [ -z "${ja_no_rc_local}" ]
then
  cat /etc/rc.local > "${temp}"
  echo "${hw_init}" >> "${temp}"
fi
cat << FIM
	Não se esqueça de editar seu /etc/rc.local
	para se certificar de que a chamada a
	${hw_init}
	não acabou ficando em algum trecho do código
	que possa não ser executado ao se inicializar
	seu sistema.
FIM

ja_no_cron=$(crontab -l root | grep "${dest}")
if [ -z "${ja_no_cron}" ]
then
  crontab -l -u root > "${temp}"
  echo "*/5 *  *   *   *     ${dest}/le_sensores.sh" >> "${temp}"
  crontab -u root "${temp}"
fi
