<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <i class="fa fa-wrench"></i> Einstellungen <small>Allgemein</small>
                </h1>
                <ol class="breadcrumb">
                    <li class="active">
                        <i class="fa fa-wrench"></i> Einstellungen
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->


        <div class="row">

            <div class="col-lg-6">
                <form id="settingsUpdate" method="POST">
                    <div class="form-group">
                        <label>Ausführungszeigen</label>
                        <input class="form-control" value="<?php echo $settings['cronjobHours']?>" type="text" name="timing">
                        <p class="help-block">Empfehlung: <strong>0,1,2,3,4,5,6,7,8,9,10</strong></p>
                    </div>
                    <div class="form-group">
                        <label>Pause zwischen Requests</label>
                        <p class="help-block">Variabel:</p>
                        <input class="form-control" value="<?php echo $settings['pauseVariable']?>" type="text" name="pause_variable">
                        <p class="help-block">Statisch:</p>
                        <input class="form-control" value="<?php echo $settings['pauseStatic']?>" type="text" name="pause_static">
                        <p class="help-block">Empfehlung: Variabel auf <strong>45</strong> und Statisch auf <strong>45</strong> setzen</p>
                    </div>
                    <div class="form-group">
                        <label>Standard-Projekt</label>
                        <select name="defaultProject" class="form-control">
                            <option>Kein Projekt ausgewählt</option>
                            <?php echo $projectList;?>
                        </select>
                    </div>
                    <button type="submit" id="settingsUpdate_submit" class="btn btn-default btn-success"><i class="fa fa-save"></i><i class="fa fa-spinner fa-spin"></i> Einstellungen speichern</button>
                </form>
            </div>

            <div class="col-lg-6">
                <div class="alert alert-info">
                    <strong>Ausführungszeigen:</strong> Im Hintergrund prüft ein PERL Cronjob immer zur vollen Stunde, ob es Keywords gibt, die zur jeweiligen Stunde aktualisiert werden soll. Das Tool verteilt alle Keywords gleichmäßig auf die angegebene Anzahl von Stunden.
                </div>
                <div class="alert alert-warning">
                    <strong>Pause zwischen Requests:</strong> Zwischen jeder Abfrage an Google wird einige Sekunden gewartet. Der erste Wert ist ein Maximum und variiert zwischen 0 und dem angegebenen Wert. Der statische Wert wird hinzuaddiert. Schnelle Abfragen garantieren, dass Google den Crawler für Stunden blockiert!<br />
                    Beide Werte sollten sicherheitshalber über 45 Sekunden liegen. Bei richtig vielen Keywords, kann man auch auf jeweils 30 runtergehen. Je kleiner der Wert, desto größer die Wahrscheinlichkeit, dass Google den Crawler sperrt.
                </div>
                <div class="alert alert-info">
                    <strong>Standard-Projekt:</strong> Löschst du ein Projekt, das du auf aktiv gesetzt hast, wird das hier genannte Standard-Projekt beim nächsten Seiten Aufruf gesetzt. Wenn kein Standard-Projekt gesetzt wurde, kommt das erste in der Datenbank.
                </div>
            </div>


            <div class="col-lg-12 messageHolder">
                <br />
                <div id="settingsAlert" class="alert">
                    <div id="message"></div>
                </div>
            </div>

        </div>

        <br />


        <div class="well">
            <h3>Allgemeine Empfehlung zu Ausführungszeiten und Pausen</h3>
            <p>Die Einstellungen müssen bzw. sollten von Zeit zu Zeit angepasst werden. Wichtig ist zu wissen, dass Google etwa 60-70 Requests pro Stunde mit einem Crawler dieser Art erlaubt. Das bedeutet, dass sich maximal 60-70 Keywords pro Stunde aktualisieren lassen. Schickt man die Abfragen zu schnell - also direkt hintereinander an Google - wird es kritisch. <strong>Das mag Google gar nicht!</strong></p>
            <p><strong>Was bedeutet das für dich?</strong> Je nach Anzahl an Keywords, musst du deine Werte anpassen! Hier eine kleine Beispielrechnung:</p>
            <p><strong>Die Annahmen:</strong></p>
            <p>1. Du trackst aktuell 250 Keywords pro Tag.</p>
            <p>2. Die Ausführungszeiten hast du angegeben mir: "0,1,2,3,4,5,6,7,8,9,10".</p>
            <p>3. Die Wartezeit zwischen jedem Request ist angeben mit 45 (variabel) und 45 (statisch).</p>
            <p><strong>Die Rechnung:</strong></p>
            <p>Der Crawler startet also zu den vollen Stunden zwischen 0 und 10 Uhr. Das sind 11 Starts. Teilt man die Anzahl der Keywords durch die Starts (250/11) kommt man auf gerundet 23 Keywords pro Stunde. Zwischen jedem Keyword wird (0-45)+45 Sekunden, also minimal 45 und maximal 90 Sekunden gewartet. Bei 23 Keywords dauert EINE Ausführung des Crawlers 23*45 bzw 23*90 Sekunden. Dazu kommt noch die reine Ausführungsuzeit, sagen wir etwa 1,5 Sekunden pro Keyword (Speichern, Verarbeiten,...) und wir sind bei minimal 1070 bzw. maximal 2105 Sekunden. In Minuten sind das minimal 17,9 bzw maximal 35. Diese beiden Werte MÜSSEN unter einer Stunde (60 Minuten) sein, damit sich die Crawler-Prozesse nicht überschneiden.</p>
            <p><strong>Die Schlussfolgerung:</strong></p>
            <p>Alles bestens. Das geht ganz gut auf. Du könntest sogar die Variante etwas erhöhen, damit die allgemeine Ausführung im Bereich von 45 Minuten pro Crawlerstart liegt.</p>
        </div>

        <div class="well">
            <h3>Tipps</h3>
            <p>Crawlt nicht jedes Keyword. Konzentriert euch pro Projekt auf die wichtigsten 30-40 - das reicht vollkommen für Insights aus. Optimalerweise müssen eure Keywords nicht um 11 Uhr morgens alle getrackt sein oder ihr habt relativ wenig Keywords. Passt eure Ausführungszeiten entsprechend an - beispielsweise. "0,2,4,6,8,10,12". Die Abstände zwischen den Ausführungszeiten sind so groß, dass Google hier nicht meckern wird. Rechnet es aber trotzdem durch.</p>
            <p><strong>Meine Einstellungen: </strong> Ich tracke etwa 200 Keywords pro Tag und lasse sie stündlich zwischen 0 und 11 Uhr aktualisieren. Die Wartezeit zwischen den Requests liegt bei (0 - 60) + 45 Sekunden. Also eine recht lange Zeit. Seit Monaten läuft das so problemlos und zuverlässig. Es gehen aber auch deutlich striktere Werte - zwischenzeitlich hab ich etwa 700 Keywords pro Tag problemlos getrackt - da wurde dann über den gesamten Tag verteilt und zwischen den Requests etwa (0-25) + 25 Sekunden eingeplant. Der Nachteil bei so etwas ist, dass man für den aktuellen Tag nie vollständige Daten hat. Dann wartet man aber eben auf den nächsten Tag und wertet "gestern" aus.</p>
        </div>

    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->
