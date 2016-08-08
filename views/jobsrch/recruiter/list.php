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
                <button type="submit" class="btn btn-default navbar-btn" onclick="$('#action').val('back')" title="Go back to search criteria" data-toggle="tooltip" data-container="body" data-placement="auto bottom"><span class="glyphicon glyphicon-menu-left"></span> Back</button>
                <button type="submit" class="btn btn-default navbar-btn" onclick="$('#action').val('refresh')" title="Refresh search results" data-toggle="tooltip" data-container="body" data-placement="auto bottom"><span class="glyphicon glyphicon-refresh"></span> Refresh</button>
                <span style="width:1em;display:inline-block"></span>
            </div>
            <div class="collapse navbar-collapse" id="toolbar">
                <ul class="nav navbar-nav">
                    <li><button type="submit" class="btn btn-default navbar-btn" onclick="$('#action').val('new')" title="Create a new recruiter" data-toggle="tooltip" data-container="body" data-placement="auto bottom"><span class="glyphicon glyphicon-plus"></span> New</button></li>
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
                <th>Name</th>
                <th>Contact Name</th>
                <th>Address</th>
                <th>Email Address</th>
                <th>Phone Number</th>
                <th>Action</th>
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
                    <a href="#" onclick="$('#action').val('edit');$('#detail_id').val('<?php echo $entry->id;?>');form.submit();" data-toggle="tooltip" data-container="body" data-placement="auto bottom" title="Edit this Recruiter"><span class="glyphicon glyphicon-pencil"></span></a>
                </td>
            </tr>
<?php endforeach; ?>
        </tbody>
<?php endif; ?>
    </table>

</form>