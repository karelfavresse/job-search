<?php /* Copyright 2016 Karel Favresse */ ?>
<?php require_once dirname(__DIR__).'/messages.php'; ?>

    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#toolbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <button type="button" class="btn btn-default navbar-btn" onclick="doAction('back')" title="<?php echo lang('button-tip-back-search-recruiter'); ?>" ><span class="glyphicon glyphicon-menu-left"></span> <?php echo lang('button-title-back'); ?></button>
                <button type="button" class="btn btn-default navbar-btn" onclick="doAction('refresh')" title="<?php echo lang('button-tip-refresh-recruiter'); ?>" ><span class="glyphicon glyphicon-refresh"></span> <?php echo lang('button-title-refresh'); ?></button>
                <span style="width:1em;display:inline-block"></span>
            </div>
            <div class="collapse navbar-collapse" id="toolbar">
                <ul class="nav navbar-nav">
<?php if ($can_create) : ?>
                    <li><button type="button" class="btn btn-default navbar-btn" onclick="doAction('create')" title="<?php echo lang('button-tip-new-recruiter'); ?>" ><span class="glyphicon glyphicon-plus"></span> <?php echo lang('button-title-new'); ?></button></li>
<?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <input name="detail_id" id="detail_id" type="hidden"></input>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#list').DataTable({
                                 "lengthMenu" : [[5,10,25,50,100],[5,10,25,50,100]],
                                 "pagingType" : "full_numbers",
                                 "searching" : false,
                                 "processing" : true,
                                 "serverSide" : true,
                                 "ajax" : {
                                    "url" : $("#form").attr('action') + '/listdata',
                                    "type" : "POST"
                                 },
                                 "columns" : [
                                    {"data" : "name"},
                                    {"data" : "contactname"},
                                    {"data" : "address", "orderable" : false},
                                    {"data" : "emailaddress", "orderable" : false},
                                    {"data" : "phonenumber"},
                                    {"data" : "action", "orderable" : false}
                                 ]
            });
        } );
    </script>
    <table id="list" class="table table-striped table-bordered table-responsive" width="100%">
        <thead>
            <tr>
                <th><?php echo lang('list-header-recruiter-name'); ?></th>
                <th><?php echo lang('list-header-recruiter-contactname'); ?></th>
                <th><?php echo lang('list-header-recruiter-address'); ?></th>
                <th><?php echo lang('list-header-recruiter-emailaddress'); ?></th>
                <th><?php echo lang('list-header-recruiter-phonenumber'); ?></th>
                <th><?php echo lang('list-header-action'); ?></th>
            </tr>
        </thead>
    </table>
