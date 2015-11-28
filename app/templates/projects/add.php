<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <i class="fa fa-sitemap"></i> Projekt hinzufügen
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i>  <a href="/dashboard/index/">Dashboard</a>
                    </li>
                    <li>
                        <i class="fa fa-sitemap"></i> <a href="/projects/index/">Projektübersicht</a>
                    </li>
                    <li class="active">
                        <i class="fa fa-plus"></i> Projekt hinzufügen
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->

        <div class="well">
            <p>Hier kannst du dein Projekt sowie dessen Konkurrenz schnell und einfach eintragen. Bitte achte darauf, dass du alle Projektadressen <strong>mit http:// bzw. https:// einträgst</strong>. Vergisst du das, kannst du davon ausgehen, dass die Ergebnisse falsch sein werden. Als Beispiel: trägst du nur "domain.de" ein, und befindet sich "tolle-domain.de" vor dir im Ranking, so wird diese erkannt und verwendet. Verwendest du allerdings "http://domain.de" passiert das nicht.</p>
        </div>

        <form role="form" id="projectAdd">

            <div class="col-lg-6">
                <label>Hauptprojekt</label>
                <div class="form-group input-group">
                    <span class="input-group-addon"><i class="fa fa-link"></i></span><input name="project" class="form-control" placeholder="URL zu deinem Projekt">
                </div>
            </div>

            <div class="col-lg-6">
                <label>Konkurrenzprojekte</label>
                <div class="form-group input-group">
                    <span class="input-group-addon"><i class="fa fa-link"></i></span><input name="comp1" class="form-control" placeholder="URL zu Konkurrent 1">
                </div>
                <div class="form-group input-group">
                    <span class="input-group-addon"><i class="fa fa-link"></i></span><input name="comp2" class="form-control" placeholder="URL zu Konkurrent 2">
                </div>
                <div class="form-group input-group">
                    <span class="input-group-addon"><i class="fa fa-link"></i></span><input name="comp3" class="form-control" placeholder="URL zu Konkurrent 3">
                </div>
                <div class="form-group input-group">
                    <span class="input-group-addon"><i class="fa fa-link"></i></span><input name="comp4" class="form-control" placeholder="URL zu Konkurrent 4">
                </div>
                <div class="form-group input-group">
                    <span class="input-group-addon"><i class="fa fa-link"></i></span><input name="comp5" class="form-control" placeholder="URL zu Konkurrent 5">
                </div>
            </div>
            <!-- /.row -->
            
            <div class="col-lg-12 messageHolder">
                <div class="alert">
                    <div id="message"></div>
                </div>
            </div>

            <div class="col-lg-12 text-right">
                <button id="projectAdd_submit" type="submit" class="btn btn-primary"><i class="fa fa-save"></i><i class="fa fa-spinner fa-spin"></i> Daten speichern</button>
            </div>

        </form>
    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->
