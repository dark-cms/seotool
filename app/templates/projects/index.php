<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <i class="fa fa-sitemap"></i> Projektübersicht
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i>  <a href="/dashboard/index/">Dashboard</a>
                    </li>
                    <li class="active">
                        <i class="fa fa-sitemap"></i> Projektübersicht
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->

        <div class="well">
            <p>In der Projektübersicht siehst du alle Projekte, die du angelegt hast. In der gleichen Reihe werden die von dir angelegten Konkurrenten ebenfalls angezeigt. Außerdem findest du die gesamte Anzahl an Keywords, die für dieses Keywpord inkl. der Konkurrenten getrackt wird. Auf der rechten Seite kannst du die Projekte jeweils bearbeiten bzw. komplett löschen. Mit dem Löschen werden alle Daten des Projekts komplett gelöscht - das gilt auch für die Konkurrenz und Keyworddaten.</p>
        </div>
        
        <div class="col-lg-3">
            <div class="form-group input-group">
                <span class="input-group-btn"><button class="btn btn-default" type="button"><i class="fa fa-search"></i></button></span>
                <input id="search" placeholder="Projekt suchen..." type="text" class="form-control">
            </div>
        </div>
        <div class="col-lg-push-6 col-lg-3 text-right">
            <a type="button" class="btn btn-primary" href="/projects/add/"> <i class="fa fa-plus"></i> Projekt hinzufügen</a>
        </div>
        
        <div class="col-lg-12">
            <div class="table-responsive">
                <table id="searchableTable" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Hauptprojekt</th>
                            <th>Konkurrenz</th>
                            <th>Keywords</th>
                            <th>Hinzugefügt</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $projectsTable; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.row -->
        
        <div class="col-lg-12 text-right">
                <a type="button" class="btn btn-primary" href="/projects/add/"> <i class="fa fa-plus"></i> Projekt hinzufügen</a>
        </div>
        <!-- /.row -->
        
    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->
