<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <i class="fa fa-list"></i> Keywords hinzufügen
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i>  <a href="/dashboard/index/">Dashboard</a>
                    </li>
                    <li>
                        <i class="fa fa-fw fa-list"></i> <a href="/keywords/index/">Keywordübersicht</a>
                    </li>
                    <li class="active">
                        <i class="fa fa-plus"></i> Keywords hinzufügen
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->

        <div class="well">
            <p><?php echo $addInformation;?></p>
        </div>

        <form role="form" id="keywordsAdd">

            <input type="hidden" name="currentProjectsParentID" value="<?php echo $projectData['currentProjectParentID'];?>" />
            <div class="form-group">
                <label>Keywords hier zeilenweise eintragen</label>
                <textarea class="form-control" name="keywords" rows="25"></textarea>
            </div>
            <!-- /.row -->

            <div class="col-lg-12 messageHolder">
                <div class="alert">
                    <div id="message"></div>
                </div>
            </div>

            <div class="col-lg-12 text-right">
                <button id="keywordsAdd_submit" type="submit" class="btn btn-primary"><i class="fa fa-save"></i><i class="fa fa-spinner fa-spin"></i> Keywords speichern</button>
            </div>

        </form>
    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->
