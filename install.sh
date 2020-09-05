#!/bin/bash

dest="/usr/local/automacao"
docRoot="/var/www/html"

# Copia os fontes PHP para o DocumentRoot do Apache
mkdir -p ${docRoot}/hack
tar cpf - gpio_defs.php index.php rele.php |
  (cd ${docRoot}/hack; tar xpf -)

# Copia os arquivos de configuração
cd config

# Primeiro a configuraçao do sistema de automação em si
mkdir -p /usr/local/automacao
tar cpf - hw-config.txt hw-init.sh | \
  (cd /usr/local/automacao; tar xpf -)

# em seguida a configuração do Apache
tar cpf - apache2 | (cd /etc; tar xpf -)

# Inicializa as portas GPIO do hardware
${dest}/hw-init.sh

# Reinicia o Apache com a nova configuração
systemctl restart apache2.service

# Ajusta a inicialização do sistema
echo "Você quer trocar o arquivo de inicialização de seu sistema por esse?"
echo "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-"
cat etc/rc.local
echo "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-"
echo '?> '
read resp
if [ "$resp" = 's' ||  "$resp" = 'S' ||  "$resp" = 'y' ||  "$resp" = 'Y' ]
then
  cp etc/rc.local /etc/rc.local
else
  echo "Ok, mas não se esqueça de editar seu rc.local conforme este modelo!
fi
