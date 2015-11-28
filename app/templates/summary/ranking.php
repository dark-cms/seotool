<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <i class="fa fa-bar-chart-o"></i>  Zusammenfassung <small>Rankingindex für <?php echo $projectData['currentProjectURL'];?></small>
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i>  <a href="/dashboard/index/">Dashboard</a>
                    </li>
                    <li class="active">
                        <i class="fa fa-fw fa-bar-chart-o"></i> Einzelranking
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->

        <div class="well">
            <p>Hier ist der Rankingindex für das Projekt <?php echo $projectData['currentProjectURL'];?> zu sehen. Das Interval kann eingestellt werden, zur Auswahl stehen 7 Tage bis 2 Jahre. Der Rankingindex ist der Durchschnittswert aller aufgezeichneten Positionen für jeden Tag. Ist ein Keyword nicht in den SERPs, wird seine Position als 100 angenommen - und geht somit negativ in die Rechnung ein.</p>
        </div>

        <div class="row">
            <div class="col-lg-3">
                <div class="form-group">
                    <select id="dateSelecter" class="form-control">
                        <?php echo $datePicker;?>
                    </select>
                </div>        </div>
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i> <?php echo $projectData['currentProjectURL'];?> </h3>
                    </div>
                    <div class="panel-body">
                        <div id="summary-ranking"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->

        <div class="alert alert-success">
            <i class="fa fa-question-circle"></i> <strong>Die beiden roten Linien im Diagramm</strong> markieren den Bereich zwischen Position 1 und 25. Dein durchschnittliches Ranking sollte möglichst klein sein, das bedeutet gleichzeitig, dass die Anzahl von Keywords mit sehr guten Plätzen hoch ist. Die Berechnung des Rankingindex ist recht einfach: Wenn zwei Keywords getrackt werden, davon eines auf Position 5 und das andere auf Position 30 ist, ist die Summe 35. Das wird durch die Anzahl an Keywords geteilt - so entsteht der Index. In dem Fall wäre es also 17,5!
        </div>

        <div class="alert alert-danger">
            <i class="fa fa-info-circle"></i> <strong>Rechenleistung bzw. Ausführungsdauer:</strong> Je größer das gewählte Intervall, desto länger dauert es, die Daten aus der Datenbank zu aggregieren und aufzubereiten. Dazu kommt das Rendern des Graphen - unter Umständen kann es also dazu kommen, dass dein Server mit einem "Maximum execution time of XX seconds exceeded" abbricht. Hier hilft leider nur das Anpassen der Server- und PHP-Einstellungen.
        </div>

    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->
