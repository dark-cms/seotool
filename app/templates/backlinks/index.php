<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <i class="fa fa-link"></i> Backlinks
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i>  <a href="/dashboard/index/">Dashboard</a>
                    </li>
                    <li class="active">
                        <i class="fa fa-fw fa-link"></i> Backlinkübersicht
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->

        <div class="well">
            <p>Hier siehst du die tabellarische Übersicht deiner Keywords für das <strong>Hauptprojekt</strong> samt der Rankings für die letzten 5 Tage, wobei "-0d" für heute (sofern bereits vorhanden), "-1d" für gestern usw. steht! Neben der besten Position, findet man hier auch die Unterschiede zwischen den Tagen. Keywords lassen sich von hier aus löschen, mit Kommentaren versehen und so weiter! </p>
        </div>

        <div class="col-lg-3 text-left">
            <div class="form-group input-group">
                <span class="input-group-btn"><button class="btn btn-default" type="button"><i class="fa fa-search"></i></button></span>
                <input id="search" placeholder="URL-Suche..." type="text" class="form-control">
            </div>
        </div>
        <div class="col-lg-push-6 col-lg-3 text-right">
            <a type="button" class="btn btn-primary" href="/backlinks/add/"> <i class="fa fa-plus"></i> Backlinks hinzufügen</a>
        </div>

        <div class="col-lg-12">
            <div class="table-responsive">
                <table id="searchableTable" class="display table table-bordered table-hover ">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Linkquelle</th>
                            <th>Linkziel</th>
                            <th>Linktyp</th>
                            <th>Typ der Quelle</th>
                            <th>Relation</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $backlinkTable;?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.row -->

        <div class="col-lg-12 text-right">
            <a type="button" class="btn btn-primary" href="/backlinks/add/"> <i class="fa fa-plus"></i> Backlinks hinzufügen</a>
        </div>
        <!-- /.row -->

    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->
