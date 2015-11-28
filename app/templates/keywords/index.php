<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <i class="fa fa-list"></i> Ranking für <?php echo $projectData['currentProjectURL'];?>
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i>  <a href="/dashboard/index/">Dashboard</a>
                    </li>
                    <li class="active">
                        <i class="fa fa-fw fa-list"></i> Keywordübersicht
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->

        <div class="well">
            <p>Hier siehst du die tabellarische Übersicht deiner Keywords für das Projekt <strong><?php echo $projectData['currentProjectURL'];?></strong> samt der Rankings für die letzten 5 Tage, wobei "-0d" für heute (sofern bereits vorhanden), "-1d" für gestern usw. steht! Neben der besten Position, findet man hier auch die Unterschiede zwischen den Tagen. Keywords lassen sich von hier aus löschen, mit Kommentaren versehen und so weiter! </p>
        </div>

        <div class="alert alert-danger">
            <strong>Wichtig: </strong> Wenn hier viele Keywords geladen werden müssen, kann das generieren einige Sekunden dauern und eine Art "delay" entstehen.
        </div>

        <div class="col-lg-3 text-left">
            <div class="form-group input-group">
                <span class="input-group-btn"><button class="btn btn-default" type="button"><i class="fa fa-search"></i></button></span>
                <input id="search" placeholder="Keyword/URL-Suche..." type="text" class="form-control">
            </div>
        </div>
        <div class="col-lg-push-6 col-lg-3 text-right">
            <a type="button" class="btn btn-primary" href="/keywords/add/"> <i class="fa fa-plus"></i> Keywords hinzufügen</a>
        </div>

        <div class="col-lg-12">
            <div class="table-responsive">
                <table id="searchableTable" class="display table table-bordered table-hover ">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Keyword</th>
                            <th>Aktualisiert</th>
                            <th></th>
                            <th>Best</th>
                            <th>-0d</th>
                            <th><i class="fa fa-long-arrow-left"></i></th>
                            <th>-1d</th>
                            <th><i class="fa fa-long-arrow-left"></i></th>
                            <th>-2d</th>
                            <th><i class="fa fa-long-arrow-left"></i></th>
                            <th>-3d</th>
                            <th><i class="fa fa-long-arrow-left"></i></th>
                            <th>-4d</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $rankingTable;?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.row -->

        <div class="col-lg-12 text-right">
            <a type="button" class="btn btn-primary" href="/keywords/add/"> <i class="fa fa-plus"></i> Keywords hinzufügen</a>
        </div>
        <!-- /.row -->

    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->
