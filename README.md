# hackathonIESB
Automação usando Raspberry Pi feita para o hackthon da pós do IESB

## Ideia:
Projeto de automação residencial

O app móvel aciona, via chamadas a uma API web, as portas de um
microcontrolador do tipo Raspberry Pi, e este, por sua vez, aciona
relés em uma placa controladora.

Os relés podem estar ligados a luzes da casa, aparelhos elétricos,
ou outros dispositivos.

Pode-se usar também os sensores opcionais do Raspberry Pi para
monitorar o ambiente. Sensores para temperatura, umidade, pressão,
vazamento de gás, vazão de água, dentre outros, estão disponíveis
no mercado.

Para instalar, clone o repositório com o comando

	git clone https://github.com/mafgomes/hackathonIESB.git

Em seguida:

	cd hackathonIESB
	sudo ./install.sh

## Hardware:
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


