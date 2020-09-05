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
