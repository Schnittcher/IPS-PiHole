[![Version](https://img.shields.io/badge/Symcon-PHPModul-red.svg)](https://www.symcon.de/service/dokumentation/entwicklerbereich/sdk-tools/sdk-php/)
![Version](https://img.shields.io/badge/Symcon%20Version-4.3%20%3E-blue.svg)
[![License](https://img.shields.io/badge/License-CC%20BY--NC--SA%204.0-green.svg)](https://creativecommons.org/licenses/by-nc-sa/4.0/)
[![StyleCI](https://styleci.io/repos/144764045/shield?style=flat)](https://styleci.io/repos/144764045)
<br />

# PiHole
Mit diesem Modul ist es möglich, Pi-hole zu überwachen und steuern.


**Inhaltsverzeichnis**

1. [Funktionsumfang](#1-funktionsumfang)  
2. [Installation](#2-installation) 
3. [Spenden](#3-spenden)
3. [Lizenz](#4-lizenz)

## 1. Funktionsumfang
* Aktvieiren und Deaktiveiren des Dienstes
* Anzeige der Statistik

## 2. Installation

### Einrichtung in IP-Symcon
Github Repository in IP-Symcon über **Kerninstanzen -> Modules -> Hinzufügen** einrichten

`https://github.com/Schnittcher/IPS-PiHole.git` 

### Einrichtung der Instanzen

#### PiHole
Die Pi-hole Instanz wird im Objektbaum erzeugt.

Feld | Erklärung
------------ | -------------
IP-Adresse des Pi-holes | Hier die IP-Adresse des Pi-hole Servers eintragen
Port des Pi-hole Webinterfaces | Hier wird der Port vom Webinterface angegeben, Default ist 80
API Token des Pi-holes | Im Webinterface unter Settings -> API / Web interface zu finden
IntervalBox | Hier kann de Zeit eingestellt werden, wie oft das Modul die Daten vom Pi-hole abfragt.

## 3. Spenden

Dieses Modul ist für die nicht kommzerielle Nutzung kostenlos, Schenkungen als Unterstützung für den Autor werden hier akzeptiert:  

<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=EK4JRP87XLSHW" target="_blank"><img src="https://www.paypalobjects.com/de_DE/DE/i/btn/btn_donate_LG.gif" border="0" /></a>

## 4. Lizenz

[CC BY-NC-SA 4.0](https://creativecommons.org/licenses/by-nc-sa/4.0/) 