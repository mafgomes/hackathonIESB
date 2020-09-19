# hackathonIESB
Automação usando Raspberry Pi feita para o hackathon da pós do IESB

## Ideia
Projeto de automação residencial

O app móvel aciona, via chamadas a uma API web, as portas de um
microcontrolador do tipo Raspberry Pi, e este, por sua vez, aciona
relés em uma placa controladora.

Os relés podem estar ligados a luzes da casa, aparelhos elétricos,
ou outros dispositivos.

Pode-se usar também os sensores opcionais do Raspberry Pi para
monitorar o ambiente. Sensores para temperatura, umidade, e
pressão atmosférica foram implementados, e a mesma placa
Sense HAT usada oferece diversos outros sensores.

### Agendamento
Ao invés de simplesmente ligar ou desligar dispositivos
conectados aos relés, o sistema pode ser configurado também
para agendar o acionamento dos relés, com base em dias do
mês/ano, dias da semana, e horários para ligar e para desligar.

### Expansão futura

#### Mais sensores
O hardware utilizado possui sensores que não foram aproveitados
neste projeto.  Por só requerer alterações ao software, talvez
fosse interessante utilizar estes sensores adicionais ou o
joystick integrado ao Sense HAT.

Sensores como giroscópio, acelerômetro e magnetômetro (bússola)
talvez pudessem ser utilizados em áreas sujeitas a terremotos e
gerar um alerta remoto para o morador, informando que sua casa
está sendo afetada.

O joystick talvez pudesse ser utilizado no controle de luzes,
ligando ou desligando luzes à nossa volta. Integrado aos
sensores, que poderiam determinar o posicionamento das luzes,
relativamente ao usuário, ligá-las apontando em sua direção
com o joystick poderia ser um efeito interessante.

Além do hardware já instalado, muito mais está disponível no
mercado e possuem interface relativamente simples.
Sensores para vazamento de gás, vazão de água, nível de
luminosidade ambiente, dentre outros, poderiam ser integrados
ao projeto sem muita complicação.

Acender luzes ao escurecer, alarmar vazamentos, dentre outras
utilizações, se tornariam possíveis com tais modificações.

## Instalação
Para instalar, clone o repositório com o comando

	git clone https://github.com/mafgomes/hackathonIESB.git

Em seguida:

	cd hackathonIESB
	sudo ./install.sh

## Configuração
Após a instalação, vá ao diretório /usr/local/automacao,
com o comando

	cd /usr/local/automacao

e edite o arquivo hw-config.txt, para refletir a configuração
do seu hardware. A principal configuração a ser feita é
a descrição de quais pinos da interface GPIO estão ligados
aos relés e, opcionalmente, que nome você pretende atribuir
para as cargas acionadas por cada um dos relés.

Mas nesse arquivo também se pode efetuar muitas das
configurações disponíveis na interface gráfica, como
acionamento de relés; ou respostas automatizadas a
leituras de sensores, como temperatura, umidade, e pressão.

#### ATENÇÃO
É muito importante que você leia o arquivo de configuração
hw-config.txt, ainda que não pretenda configurar o sistema
de imediato, pois ele contém boa parte da documentação sobre
este sistema!

Em particular, este arquivo descreve o mecanismo pelo qual
é possível fazer o agendamento de acionamentos ou desligamentos
de relés, tanto periodicamente quanto uma única vez, ou mesmo
em resposta à leitura de sensores.

## Hardware
Para iniciantes que não têm habilidade com solda, recomenda-se
o uso de um protoboard, também conhecido como breadboard.
Trata-se de uma placa com furação para que se insira os
componentes, e com contatos entre estes furos, num padrão que
facilita a montagem de circuitos.
Uma descrição mais completa de protoboards, e de como usá-los
pode ser baixada de

http://www.uel.br/pessoal/ernesto/arduino/00_Protoboard.pdf

Além do protoboard, você também precisará dos chamados jumpers,
que nada mais são que fios com contatos em suas extremidades,
de tamanho ideal para serem inseridos na furação do protoboard,
e em conectores dos componentes.
Os jumpers podem ser macho-macho, macho-fêmea, ou fẽmea-fêmea,
indicando o tipo de contatos presentes em suas extremidades.

Mas como saber o que ligar em quê? As seções a seguir
explicam as ligações a serem feitas para as duas funcionalidades
principais desse sistema: comando de relés e sensores da
placa Sense Hat.

### Sense Hat
O esquema de ligação do
Raspberry Pi com um Sense Hat, que é uma parte desse projeto,
está bem documentado em:

https://pinout.xyz/pinout/sense_hat

E, para quem tem curiosidade de saber exatamente como é
o circuito completo do tal Sense Hat, o diagrama lógico
desse produto pode ser baixado em

https://www.raspberrypi.org/documentation/hardware/sense-hat/images/Sense-HAT-V1_0.pdf

Embora ambas as páginas estejam em inglês, como são mais
gráficas que textuais, e a primeira inclusive destaca
os pinos utilizados, não deve haver dificuldade em conectar
os pinos correspondentes, principalmente sabendo-se que o
conector do Sense Hat foi projetado para encaixar-se
perfeitamente ao conector do Raspberry Pi.

Só não se esqueça de plugar, além das linhas de sinal,
também as linhas de alimentação (5V, 3.3V, e terra), que
figuram no diagrama em inglês, respectivamente, como
"5V Power", "3V3 Power", e "Ground".

Quaisquer das linhas de alimentação podem ser utilizadas,
e eu recomendaria o uso de mais de uma, para o caso de
haver mau contato em alguma delas. Ou seja, plugue mais
de uma linha "5V Power" do conector do Raspberry Pi à
sua correspondente no conector do Sense Hat, fazendo o
mesmo para as linhas "3V3 Power" e "Ground".

### Comando de relés
Talvez a principal funcionalidade desse sistema seja o
comando de relés a partir da web.

Para permitir isso, utilizamos uma placa de relés de
8 canais, projetada para uso com microcontroladores
da linha Arduino. Nada impede, porém, que seja empregada
uma placa com um número maior ou menor de canais.

A placa empregada parece ser um clone exato da placa
da Sunfounder, inclusive utilizando os mesmos componentes.
A documentação da placa da Sunfounder pode ser encontrada
nesta página:

http://wiki.sunfounder.cc/index.php?title=8_Channel_5V_Relay_Module

Nela, cada relé tem a especificação de
suportar cargas drenando até 10A, em tensões de até 250VAC,
mas isso pode variar de placa para placa. É bom que você
confira a sua placa, para saber seus limites.

Muito embora a especificação desta placa seja para
acionamento dos relés com sinais de 5V, os 3.3V fornecidos
pelas portas GPIO do Raspberry Pi são mais que suficientes,
para acioná-los de forma confiável, desde que a alimentação
dos demais chips da placa seja suprida pela linha de 5V,
e essa está presente no conector de E/S do Raspberry.

Caso a sua placa de relés não tenha essa característica
de poder ser acionada com sinais de 3.3V, você talvez
tenha que inserir uma plaquinha conversora DC-DC, de
3.3V para 5V, com tantos canais quantos desejar utilizar
da sua placa de relés.

A descrição da função de cada pino da placa de relés
usualmente vem impressa na própria placa, como "Gnd",
ou seja, o terra, "In1" até "In8", que são as entradas
para acionar os relés, e "Vcc", onde deve ser conectada
a alimentação, vinda da linha "5V Power" do Raspberry Pi. 
Caso sua placa não traga a identificação impressa,
consulte a documentação que a acompanha, ou o site
do fabricante na Internet.

Utilizando o mesmo esquema de pinagem do Raspberry em

https://pinout.xyz/pinout/sense_hat

podemos identificar as linhas de 5V, terra, e diversos
pinos GPIO não utilizados pelo Sense Hat. Nesta parte,
tudo o que temos de fazer é identificar pinos do GPIO
não utilizados pelo Sense Hat, e conectá-los às entradas
In1, In2, ... In8 da placa de relés.

Exatamente qual pino do GPIO é conectado a qual
das entradas InX da placa de relés é irrelevante,
já que isso será configurado no arquivo hw-config.txt,
como explicado na seção de configuração, acima.
O importante é que não sejam usados pinos GPIO que
já estejam em uso pelo Sense Hat.

Ou seja, seguindo a numeração do site pinout.xyz,
poderemos utilizar quaisquer dos pinos
4, 14, 15, 17, 18, 27, 22, 10, 9, 11, 7, 0, 1, 5, 6,
12, 13, 19, 16, 26, 20 ou 21
(a ordem dos números aqui é para facilitar a
identificação no diagrama do site).

Como se pode ver, além de usar o Sense Hat, ainda é
possível acionar diretamente até 22 relés com esse
esquema. Desejando comandar mais dispositivos, o
projeto teria que ser adaptado, talvez utilizando-se
uma placa SIO ou I2C para fazer a multiplexação de
canais por endereços lógicos, mas isso tornaria o
software mais complexo.

Outra possibilidade seria usar um CI com registrador
de deslocamento de bits (shift register), tal como o
74HC595, que receberia sequencialmente os bits de
comando dos relés, e os manteria num buffer.
Esta possibilidade também aumentaria um pouco a
complexidade do software e do hardware, mas permitiria
o controle de praticamente qualquer número de relés,
limitado apenas pela energia disponível para alimentar
todo o circuito, a velocidade do processador, e a
velocidade de resposta que se deseja para o acionamento
de cada relé.
