#! /usr/bin/env /usr/bin/python3

from sense_hat import SenseHat
sense = SenseHat()

print("""    Umidade: {umidade:>0.1f} %
    Temperatura: {temperatura:>0.1f} °C
    Pressão: {pressao:>0.1f} mBar""".format(**dict(
     umidade     = sense.get_humidity(),
     temperatura = sense.get_temperature(),
     pressao     = sense.get_pressure()
   ))
)
