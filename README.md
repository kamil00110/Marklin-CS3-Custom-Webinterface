# Märklin CS3 Custom Webinterface Project

The project is a work in progress. Bugs probebly won't be fixed. Everyone that wants to add features is allowed to help in this project. Should be Windows/Linux compatible, but it was only tested on Windows.

## What it does now?

- Sending and reciving Websocket packages to and from the CS3.
- Reading and displaying train images, infos and showing the speed, direction and functions in realtime.
- Stopping and Starting the CS3.
- Controlling the speed, direction and functions of the trains.
- Controlling Magnets.
- Reading and Controlling S88 contacts.
- One click rooting the CS3
- Comunicating with Net Cat servern on the CS3.
- Compiling modyfied update files.
- Automaticly scanning for the CS3 WebSocket server.

A Custom programming language will be developed which is based on Märklin PAPS and PHP.

## How to use?

1. Connect the CS3 to the local network.
2. Get the IP of the CS3(optional).
3. Install an Apache and PHP server (XAMPP is a recomended simple program for this) and replace the files in htdocs folder.
4. Start the server and access the webinterface througt the browser under http://localhost/index.php or http://127.XXX.XXX.XXX/index.php
5. Optional (Install WSL with a Distro like Kali to compile custom updates)


UI is still not resizeble. But will when all the UI is finished.
![WebSocket Client - Google Chrome 24 06 2024 22_51_52](https://github.com/kamil00110/Marklin-CS3-Custom-Webinterface/assets/68923965/643de64b-25fd-44fb-a0cb-cce0b6c81fe7)



## Known important commands and adresses:

### Commands:

### starts and stops the CS3:
```
42["event_data","{\"cs3\":{\"state\":\"0 or 1\"}}"]
```
### sets switches: 
```
42["event_data","{\"mag\":{\"id\":\"8\",\"state\":\"1 or 128\"}}"]
```
### changes train direction:
```
42["event_data","{\"lok\":{\"name\":\"132#20439-1\",\"dir\":\"1 or 0\"}}"]
```
### changes train speed:
```
42["event_data","{\"lok\":{\"name\":\"132#20439-1\",\"speed\":\"0 - 1000\"}}"]
```
### uses train functions:
```
42["event_data","{\"lok\":{\"name\":\"132#20439-1\",\"func\":\"1\",\"state\":\"1 or 0\"}}"]
```
### changes s88 state:
```
42["event_data","{\"s88\":{\"oldstate\":\"2\",\"s88kontakt\":\"1\",\"s88kennung\":\"1\",\"state\":\"1 or 0\"}}"]
```

### Adresses:

```
http://CS3 IP/app/api/loks/ XXX.png, mag, mags, devs, prefs, gbs, info, filemanager, automatics, helps, system
http://CS3 IP/config/geraet.vrs, lokomotive.cs2, fahrstrassen.cs2, gleisbild.cs2, magnetartikel.cs2
http://CS3 IP/config/app/assets/fct/ XXX.svg
```

## Planned features:

- camera train controll
- PHP based programming language
- track builder
- onboard sound playback
- automatic mode
- train and mag editor
