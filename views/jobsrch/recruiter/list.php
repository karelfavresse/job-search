<?php /* Copyright 2016 Karel Favresse */ ?>
<?php echo form_open('jobsrch/recruiter', array('id' => 'form')); ?>

    <input type="hidden" name="action" id="action"/>

    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#toolbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <button type="submit" class="btn btn-default navbar-btn" onclick="$('#action').val('back')" title="<?php echo lang('button-tip-back-search-recruiter'); ?>" data-toggle="tooltip" data-container="body" data-placement="auto bottom"><span class="glyphicon glyphicon-menu-left"></span> <?php echo lang('button-title-back'); ?></button>
                <button type="submit" class="btn btn-default navbar-btn" onclick="$('#action').val('refresh')" title="<?php echo lang('button-tip-refresh-recruiter'); ?>" data-toggle="tooltip" data-container="body" data-placement="auto bottom"><span class="glyphicon glyphicon-refresh"></span> <?php echo lang('button-title-refresh'); ?></button>
                <span style="width:1em;display:inline-block"></span>
            </div>
            <div class="collapse navbar-collapse" id="toolbar">
                <ul class="nav navbar-nav">
<?php if ($can_create) : ?>
                    <li><button type="submit" class="btn btn-default navbar-btn" onclick="$('#action').val('new')" title="<?php echo lang('button-tip-new-recruiter'); ?>" data-toggle="tooltip" data-container="body" data-placement="auto bottom"><span class="glyphicon glyphicon-plus"></span> <?php echo lang('button-title-new'); ?></button></li>
<?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <input name="detail_id" id="detail_id" type="hidden"></input>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#list').DataTable();
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
<?php if ( isset($list) ) : ?>
        <tbody>
<?php foreach ($list as $entry) : ?>
            <tr>
                <td><?php echo $entry->name; ?></td>
                <td><?php echo $entry->contact_name; ?></td>
<?php if ( isset ( $addrList[$entry->id] ) ) : ?>
                <td><?php echo $addrList[$entry->id]; ?></td>
<?php else : ?>
                <td></td>
<?php endif; ?>
                <td><?php echo $entry->email_address; ?></td>
                <td><?php echo $entry->phone_number; ?></td>
                <td>
                    <a href="#" onclick="$('#action').val('edit');$('#detail_id').val('<?php echo $entry->id;?>');form.submit();" data-toggle="tooltip" data-container="body" data-placement="auto bottom" title="<?php echo lang('button-tip-edit-recruiter'); ?>"><span class="glyphicon glyphicon-pencil"></span></a>
                </td>
            </tr>
<?php endforeach; ?>
        </tbody>
<?php endif; ?>
    </table>

</form>