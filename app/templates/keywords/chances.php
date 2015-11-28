<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <i class="fa fa-list"></i> Chancen-Keywords für <?php echo $projectData['currentProjectURL']?>
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i> <a href="/dashboard/index/">Dashboard</a>
                    </li>
                    <li>
                        <i class="fa fa-list"></i> <a href="/keywords/index/">Keywordübersicht</a>
                    </li>
                    <li class="active">
                        <i class="fa fa-fw fa-dot-circle-o"></i> Chancen-Keywords
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->

        <div class="well">
            <p>Bei Chancen-Keywords handelt es sich um Keywords auf den Plätzen 4 bis 25 zu finden sind. Du siehst vor dir die Chancen-Keywords für die Website <strong><?php echo $projectData['currentProjectURL'];?></strong>. Versuch die Seiten innerhalb der Website noch weiter zu optimieren. Oft kann man mit kleinen Optimierungen einige Plätze nach vorne rutschen.</p>
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
                <table id="searchableTable" class="display table table-bordered table-hover ">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Chancen-Keyword</th>
                            <th>Deine Position</th>
                            <th>Bester<br /><small>Konkurrent</small></th>
                            <th>Schlechtester<br /><small>Konkurrent</small></th>
                            <th>Rankende URL</th>
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
