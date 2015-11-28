<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <i class="fa fa-bar-chart-o"></i> Zusammenfassung <small>Verarbeitete Keywords für <?php echo $projectData['currentProjectURL'];?></small>
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i>  <a href="/dashboard/index/">Dashboard</a>
                    </li>
                    <li class="active">
                        <i class="fa fa-fw fa-bar-chart-o"></i> Verarbeitete Keywords
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->

        <div class="well">
            <p>Hier kannst du beobachten, wie viele Keywords pro Tag verarbeitet wurden. Du erkennst hier zum einen, ob alle Keywords, die im Projekt gespeichert wurden, auch wirklich vom Cronjob verarbeitet wurden und zum anderen siehst du, wenn du neue Keywords hinzufügst oder löschst.</p>
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
                        <div id="summary-keywords"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->

    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->
