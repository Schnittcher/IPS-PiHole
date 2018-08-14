# IPS-PiHole
Mit diesem Modul ist es möglich, Pi-hole zu überwachen und steuern.


**Inhaltsverzeichnis**

1. [Funktionsumfang](#1-funktionsumfang)  
2. [Installation](#2-installation) 

## 1. Funktionsumfang
* Aktvieiren und Deaktiveiren des Dienstes
* Anzeige der Statistik

## 2. Installation

### Einrichtung in IP-Symcon
Github Repository in IP-Symcon über **Kerninstanzen -> Modules -> Hinzufügen** einrichten

`https://github.com/Schnittcher/IPS-PiHole.git` 

### Einrichtung der Instanzen

#### IPS-PiHole``
Die Pi-hole Instanz wird im Objektbaum erzeugt.

Feld | Erklärung
------------ | -------------
IP-Adresse des Pi-holes | Hier die IP-Adresse des Pi-hole Servers eintragen
Port des Pi-hole Webinterfaces | Hier wird der Port vom Webinterface angegeben, Default ist 80
API Token des Pi-holes | Im Webinterface unter Settings -> API / Web interface zu finden
IntervalBox | Hier kann de Zeit eingestellt werden, wie oft das Modul die Daten vom Pi-hole abfragt.