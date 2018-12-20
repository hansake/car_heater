# car_heater
Controls outlet to electric car heater using Domoticz

An electric car heater outlet is controlled to make the car warm at a specified time.
The car heater is started based on the outside temperature.

Domoticz is used for the control logic of supervising the outside temperature and
switching on and off the power to the car heater outlet. The control is done with
a dzVents script.

The user interface is done with .php code. This code interacts with Domoticz using
the JSON API (https://www.domoticz.com/wiki/Domoticz_API/JSON_URL%27s).

A web server executing the .php code is required.

The Domoticz installation and web server is tested on Raspberry Pi.




