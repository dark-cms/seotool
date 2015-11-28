<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <i class="fa fa-dashboard"></i> Dashboard <small><?php echo $projectData['currentProjectURL'];?></small>
                </h1>
                <ol class="breadcrumb">
                    <li class="active">
                        <i class="fa fa-dashboard"></i> Dashboard
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->

        <?php echo $trackedKeywordWarning['top'];?>
        <!-- /.row -->

        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-list fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo $quickInfo['keywords'];?></div>
                                <div>Keywords</div>
                            </div>
                        </div>
                    </div>
                    <a href="/keywords/add/">
                        <div class="panel-footer">
                            <span class="pull-left">Keywords hinzufügen</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-green">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-pause fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo $quickInfo['keywordsTrackedToday'];?></div>
                                <div>Heute aktualisiert</div>
                            </div>
                        </div>
                    </div>
                    <a href="/keywords/index/">
                        <div class="panel-footer">
                            <span class="pull-left">Website-Ranking</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-yellow">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-sitemap fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo ($quickInfo['competition'] - 1);?></div>
                                <div>Konkurrenten</div>
                            </div>
                        </div>
                    </div>
                    <a href="/keywords/competition/">
                        <div class="panel-footer">
                            <span class="pull-left">Ranking der Konkurrenz</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-red">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-line-chart fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo $quickInfo['rankIndexToday'];?></div>
                                <div>Rankingindex</div>
                            </div>
                        </div>
                    </div>
                    <a href="/summary/ranking/">
                        <div class="panel-footer">
                            <span class="pull-left">Verlauf der letzten Tage</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-lg-7">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i> Positionsverteilung für <?php echo $projectData['currentProjectURL'];?></h3>
                    </div>
                    <div class="panel-body">
                        <div id="dashboard-posdis"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i> Backlinkverteilung nach Relationen für <?php echo $projectData['currentProjectURL'];?></h3>
                    </div>
                    <div class="panel-body">
                        <div id="dashboard-relations"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
        <?php echo $trackedKeywordWarning['middle'];?>

        <div class="row">
            <div class="col-lg-4">
                <div class="panel panel-green">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-arrow-circle-o-up fa-fw"></i> Gewinner-Keywords - heute</h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Keyword</th>
                                        <th>Pos.</th>
                                        <th>&#916;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo $winnerTable;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="panel panel-red">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-arrow-circle-o-down fa-fw"></i> Verlierer-Keywords - heute</h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Keyword</th>
                                        <th>Pos.</th>
                                        <th>&#916;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo $loserTable;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-fw fa-dot-circle-o fa-fw"></i> Chancen-Keywords - heute</h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Keyword</th>
                                        <th>Pos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <?php echo $chancesTable;?>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i> Rankingindex für <?php echo $projectData['currentProjectURL'];?> in den letzten 7 Tagen</h3>
                    </div>
                    <div class="panel-body">
                        <div id="dashboard-ranking"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->


    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->
