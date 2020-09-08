#!/usr/bin/env /bin/bash

# Define o diretório de trabalho e
# os nomes dos arquivos envolvidos:

automacaoDir="/usr/local/automacao"
agora="${automacaoDir}/sensores.txt"
temp="${automacaoDir}/temp_sensores.txt"
historico="${automacaoDir}/hist_sensores.txt"
sensores="${automacaoDir}/sensores.py"

# Faz as coisas de forma um pouco mais
# trabalhosa para que os arquivos estejam
# sempre íntegros, e não lhes falte
# informação em momento algum, já que
# a qualquer instante o servidor web pode
# requisitar o conteúdo dos arquivos

# Obtém a data e hora correntes:
dth=$(date +"%sX%A, %d/%m/%Y, %H:%M")
ts="${dth/X*/}"
dth="${dth/*X/}"

# Obtém as informações correntes:
echo "${dth}" > "${temp}"
"${sensores}" >> "${temp}"
mv "${temp}" "${agora}"

# Mantém o histórico das informações:
[ -f "${historico}" ] && cp "${historico}" "${temp}"
echo "${ts}" >> "${temp}"
tail -3 "${agora}" | sed -e 's/[^0-9.]//g' >> "${temp}"
mv "${temp}" "${historico}"
