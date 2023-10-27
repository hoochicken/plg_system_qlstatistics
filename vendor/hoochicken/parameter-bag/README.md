# parameter-bag

Administrate parameters in a collection, setters (setInt, setString ...) and getters (getInt, getString ...)

~~~php
$numClients = 10;
$parameterBag = new ParameterBag();
$parameterBag->set('number_of_clients', $numClients);

vardump($parameterBag->getInt('number_of_clients'));
// (int)10

vardump($parameterBag->getString('number_of_clients'));
// (string)'10'

vardump($parameterBag->getBool('number_of_clients'));
// (bool)true
~~~
