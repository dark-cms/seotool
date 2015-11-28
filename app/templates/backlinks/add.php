<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <i class="fa fa-fw fa-link"></i> Backlinks <small>hinzufügen</small>
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i>  <a href="/dashboard/index/">Dashboard</a>
                    </li>
                    <li>
                        <i class="fa fa-fw fa-link"></i> <a href="/backlinks/index/">Backlinkübersicht</a>
                    </li>
                    <li class="active">
                        <i class="fa fa-plus"></i> Backlink hinzufügen
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->

        <div class="well">
            <p>Dieser Bereich erlaubt es schnell und einfach Backlinks für dein Projekt anzulegen. Die Quelle (Verlinker) zeigt auf deine Seite (Verlinkter), zusätzlich kannst du die Art des Links sowie den Quellentyp als auch die Relation angeben. Diese Daten werden im Dashboard zusammengefasst, so dass es sich lohnt hier gewissenhaft zu sein.</p>
        </div>

        <form role="form" id="backlinkAdd">
            <div class="row">
                <div class="col-lg-8">
                    <div class="form-group">
                        <label>Linkquelle</label>
                        <input name="linkSource" class="form-control">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label>Linktext</label>
                        <input name="linkText" class="form-control">
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="form-group">
                        <label>Linkziel</label>
                        <input name="linkTo" class="form-control">
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Projektadresse</label>
                                <select name="project" class="form-control">
                                    <?php echo $projectListSelect;?>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Linktyp</label>
                                <select name="linkType" class="form-control">
                                    <?php echo $linkTypes;?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Typ der Quelle</label>
                                <select name="linkSourceType" class="form-control">
                                    <?php echo $linkSourceTypes;?>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Relation</label>
                                <select name="linkRelation" class="form-control">
                                    <?php echo $linkRelations;?>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-lg-5">
                    <div class="form-group">
                        <label>Kommentar zum Backlink</label>
                        <textarea name="linkComment" class="form-control" rows="5"></textarea>
                    </div>
                </div>

            </div>

            <div class="col-lg-12 messageHolder">
                <div id="backlinkAlert" class="alert">
                    <div id="message"></div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 text-right">
                    <button id="backlinkAdd_submit" type="submit" class="btn btn-primary"><i class="fa fa-save"></i><i class="fa fa-spinner fa-spin"></i> Backlink speichern</button>
                </div>
            </div>
        </form>
    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->
