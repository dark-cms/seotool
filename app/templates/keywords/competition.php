<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <i class="fa fa-list"></i> Mitbewerberranking 
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i>  <a href="/dashboard/index/">Dashboard</a>
                    </li>
                    <li class="active">
                        <i class="fa fa-fw fa-list"></i> Konkurrenzübersicht
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->


        <div class="well">
            <p>Für den <strong><?php echo $selectedDate;?> </strong> siehst du hier <strong>dein Hauptprojekt</strong> verglichen mit der Konkurrenz. Siehst du noch keine Daten, so bedeutet es, dass der Keyword Crawler dieses Projekt heute noch nicht verarbeitet hat. Hab also etwas Geduld. Beobachtest du nur eine kleine Anzahl an Keywords, lohnt es sich das Tracking über Nacht zu aktivieren - siehe hierzu <a href="/settings/cronjob/">Cronjob-Einstellungen</a>!</p>
        </div>

        <div class="alert alert-danger">
            <strong>Wichtig: </strong> Wenn hier viele Keywords geladen werden müssen, kann das generieren einige Sekunden dauern und eine Art "delay" entstehen.
        </div>

        <div class="col-lg-3">
            <div class="form-group input-group">
                <span class="input-group-btn"><button class="btn btn-default" type="button"><i class="fa fa-search"></i></button></span>
                <input id="search" placeholder="Keyword/URL-Suche..." type="text" class="form-control">
            </div>
        </div>
        <div class="col-lg-3">
            <?php echo $selectedDateHTML;?>
        </div>
        <div class="col-lg-push-3 col-lg-3 text-right">
            <a type="button" class="btn btn-primary" href="/keywords/add/"> <i class="fa fa-plus"></i> Keywords hinzufügen</a>
        </div>

        <div class="col-lg-12">
            <div class="table-responsive">
                <table id="searchableTable" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Keyword</th>
                            <?php echo $tblHeaderCompetiton;?>
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
