<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <i class="fa fa-sitemap"></i> Projekt bearbeiten
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i>  <a href="/dashboard/index/">Dashboard</a>
                    </li>
                    <li>
                        <i class="fa fa-sitemap"></i> <a href="/projects/index/">Projektübersicht</a>
                    </li>
                    <li class="active">
                        <i class="fa fa-edit"></i> Projekt bearbeiten
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->

        <div class="well">
            <p>Die Funktionen zum Bearbeiten sind recht simpel gehalten - du kannst die Internetadressen ändern bzw. Konkurrenten hinzufügen, bearbeiten oder löschen. Das gesamte Projekt lässt sich über die Projektübersicht löschen. Alle Daten inkl. Keywords und Trackings gehen dabei verloren.</p>
        </div>

        <form role="form" id="projectUpdate">

            <div class="col-lg-6">
                <label>Hauptprojekt</label>
                <div class="form-group input-group">
                    
                    <?php echo $mainProjectArea; ?>
                </div>
            </div>

            <div class="col-lg-6">
                <label>Konkurrenzprojekte</label>
                <?php echo $competitionArea; ?>
            </div>
            <!-- /.row -->
            <div class="col-lg-12 messageHolder">
                <div class="alert">
                    <div id="message"></div>
                </div>
            </div>
            
            <div class="col-lg-12 text-right">
                <button id="projectUpdate_submit" type="submit" class="btn btn-primary"><i class="fa fa-save"></i><i class="fa fa-spinner fa-spin"></i> Daten speichern</button>
            </div>

        </form>
    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->
