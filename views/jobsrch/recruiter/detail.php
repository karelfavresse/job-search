<?php /* Copyright 2016 Karel Favresse */ ?>
<?php echo form_open('jobsrch/recruiter'); ?>

    <input type="hidden" name="action" id="action"/>

    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#toolbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <button type="submit" class="btn btn-default navbar-btn" onclick="$('#action').val('back')" title="Go back to list page" data-toggle="tooltip" data-container="body" data-placement="auto bottom"><span class="glyphicon glyphicon-menu-left"></span> Back</button>
                <span style="width:1em;display:inline-block"></span>
                <button type="submit" class="btn btn-default navbar-btn" onclick="$('#action').val('save')" title="Save this recruiter" data-toggle="tooltip" data-container="body" data-placement="auto bottom"><span class="glyphicon glyphicon-save"></span> Save</button>
                <span style="width:1em;display:inline-block"></span>
            </div>
            <div class="collapse navbar-collapse" id="toolbar">
                <ul class="nav navbar-nav">
                    <li><button type="button" class="btn btn-danger navbar-btn <?php echo $button_state['delete']; ?>" onclick="$('#deleteConfirmBox').modal();" title="Delete this recruiter" data-toggle="tooltip" data-container="body" data-placement="auto bottom" <?php echo $button_state['delete']; ?>><span class="glyphicon glyphicon-trash"></span> Delete</button></li>
                </ul>
            </div>
        </div>
    </nav>

    <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

    <div class="container-fluid form-horizontal" id="detail">
        <div class="row form-group">
            <label for="name" class="col-md-1 control-label">Name:</label>
            <div class="col-md-3 col-xs-11">
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $detail->name;?>">
            </div>
        </div>
        <div class="row form-group">
            <label for="contact_name" class="col-md-1 control-label">Contact Name:</label>
            <div class="col-md-3 col-xs-11">
                <input type="text" class="form-control" id="contact_name" name="contact_name" value="<?php echo $detail->contact_name;?>">
            </div>
        </div>
        <?php include_once dirname(__DIR__) . '/address/detail.php'; ?>
        <div class="row form-group">
            <label for="email_address" class="col-md-1 control-label">Email Address:</label>
            <div class="col-md-3 col-xs-11">
                <input type="text" class="form-control" id="email_address" name="email_address" value="<?php echo $detail->email_address;?>">
            </div>
        </div>
        <div class="row form-group">
            <label for="phone_number" class="col-md-1 control-label">Phone Number:</label>
            <div class="col-md-2 col-xs-11">
                <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo $detail->phone_number;?>">
            </div>
        </div>
<?php if(isset($detail->id)) : ?>
        <div class="row form-group">
            <label for="id" class="col-md-1 control-label">ID:</label>
            <div class="col-md-2 col-xs-11">
                <input type="text" class="form-control" id="id" value="<?php echo $detail->id;?>" readonly>
            </div>
        </div>
<?php endif; ?>
        <div class="row form-group">
            <label for="version" class="col-md-1 control-label">Version:</label>
            <div class="col-md-2 col-xs-11">
                <input type="text" class="form-control" id="version" value="<?php echo $detail->version;?>" readonly>
            </div>
        </div>
    </div>

    <div id="deleteConfirmBox" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete Recruiter</h4>
                </div>
                <div class="modal-body">
                    <p>Delete Recruiter &quot;<?php echo $detail->name; ?>&quot; ?</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger" onclick="$('#action').val('delete')"><span class="glyphicon glyphicon-trash"></span> Delete</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
                </div>
            </div>
        </div>
    </div>
</form>