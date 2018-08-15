<a href="https://www.symcon.de"><img src="https://img.shields.io/badge/IP--Symcon-4.0-blue.svg?style=flat-square"/></a>
<a href="https://www.symcon.de"><img src="https://img.shields.io/badge/IP--Symcon-5.0-blue.svg?style=flat-square"/></a>
<a href="https://styleci.io/repos/112193317"><img src="https://styleci.io/repos/144764045/shield?branch=master" alt="StyleCI"></a>
<br />

# IPS-PiHole
Mit diesem Modul ist es möglich, Pi-hole zu überwachen und steuern.


**Inhaltsverzeichnis**

1. [Funktionsumfang](#1-funktionsumfang)  
2. [Installation](#2-installation) 
3. [Spenden](#3-spenden)

## 1. Funktionsumfang
* Aktvieiren und Deaktiveiren des Dienstes
* Anzeige der Statistik

## 2. Installation

### Einrichtung in IP-Symcon
Github Repository in IP-Symcon über **Kerninstanzen -> Modules -> Hinzufügen** einrichten

`https://github.com/Schnittcher/IPS-PiHole.git` 

### Einrichtung der Instanzen

#### IPS-PiHole
Die Pi-hole Instanz wird im Objektbaum erzeugt.

Feld | Erklärung
------------ | -------------
IP-Adresse des Pi-holes | Hier die IP-Adresse des Pi-hole Servers eintragen
Port des Pi-hole Webinterfaces | Hier wird der Port vom Webinterface angegeben, Default ist 80
API Token des Pi-holes | Im Webinterface unter Settings -> API / Web interface zu finden
IntervalBox | Hier kann de Zeit eingestellt werden, wie oft das Modul die Daten vom Pi-hole abfragt.

## 3. Spenden

Dieses Modul ist für die nicht kommzerielle Nutzung kostenlos, freiweillige Unterstützungen für den Autor werden hier akzeptiert:  

<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=EK4JRP87XLSHW" target="_blank"><img src="https://www.paypalobjects.com/de_DE/DE/i/btn/btn_donate_LG.gif" border="0" /></a>