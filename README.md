# seotool

1. Kram runterladen und entpacken
2. per FTP/SSH hochladen
3. das ganze muss unter einer Subdomain laufen, sollte ja für niemanden ein Problem sein
4. app/settings.php anpassen (DB-Daten und Logindaten)
5. cron/seotracker.pl anpassen (DB-Daten); es empfiehlt sich die perl-Datei umzubennen, so dass man sie nicht einfach so runterladen kann - oder eben gescheite serversettings, das das generell verboten ist! Da stehen eure DB Daten drin!
6. per SSH (! Kein Webcronjob oder ähnliches !) Cronjob so einrichten, dass er zur vollen Stunde die perl-Datei startet 
7. dump.sql in die gewählte mysql db importieren
8. per SSH über composer alle Abhängigkeiten installieren (Slim, ...)
9. Autoload dumpen mit Composer Befehl: "composer dumpautoload -o" 
10. wie hier beschrieben, eure htaccess o.ä. anpassen (http://www.slimframework.com/docs/start/web-servers.html) 
11. hoffen, dass ich hier nicht vergessen habe
12. Einloggen, Projekte anlegen, Keywords anlegen und bis nächsten Tag warten.

# screenshot
<img src="http://i.imgur.com/zIh8Ezf.png">
