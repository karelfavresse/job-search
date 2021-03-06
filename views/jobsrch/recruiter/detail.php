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
                <button type="button" class="btn btn-default navbar-btn" onclick="doAction('back')" title="<?php echo lang('button-tip-back-list-recruiter'); ?>" ><span class="glyphicon glyphicon-menu-left"></span> <?php echo lang('button-title-back'); ?></button>
                <span style="width:1em;display:inline-block"></span>
<?php if ($can_update) : ?>
                <button type="button" class="btn btn-default navbar-btn" onclick="doAction('save')" title="<?php echo lang('button-tip-save-recruiter'); ?>" ><span class="glyphicon glyphicon-save"></span> <?php echo lang('button-title-save'); ?></button>
<?php endif; ?>
                <span style="width:1em;display:inline-block"></span>
            </div>
            <div class="collapse navbar-collapse" id="toolbar">
                <ul class="nav navbar-nav">
<?php if ($can_delete) : ?>
                    <li><button type="button" class="btn btn-danger navbar-btn <?php echo $button_state['delete']; ?>" onclick="$('#deleteConfirmBox').modal('show');" title="<?php echo lang('button-tip-delete-recruiter'); ?>" <?php echo $button_state['delete']; ?>><span class="glyphicon glyphicon-trash"></span> <?php echo lang('button-title-delete'); ?></button></li>
<?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

        <div class="row form-group">
            <?php echo lang('label-detail-recruiter-name', 'name', array('class' => 'control-label col-md-1 col-sm-3')); ?>
            <div class="col-sm-5 col-md-3 col-xs-12">
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $detail->name;?>">
            </div>
        </div>
        <div class="row form-group">
            <?php echo lang('label-detail-recruiter-contactname', 'contact_name', array('class' => 'control-label col-md-1 col-sm-3')); ?>
            <div class="col-md-3 col-xs-12 col-sm-5">
                <input type="text" class="form-control" id="contact_name" name="contact_name" value="<?php echo $detail->contact_name;?>">
            </div>
        </div>
        <?php include_once dirname(__DIR__) . '/address/detail.php'; ?>
        <div class="row form-group">
            <?php echo lang('label-detail-recruiter-emailaddress', 'email_address', array('class' => 'control-label col-md-1 col-sm-3')); ?>
            <div class="col-md-3 col-xs-12 col-sm-5">
                <input type="text" class="form-control" id="email_address" name="email_address" value="<?php echo $detail->email_address;?>">
            </div>
        </div>
        <div class="row form-group">
            <?php echo lang('label-detail-recruiter-phonenumber', 'phone_number', array('class' => 'control-label col-md-1 col-sm-3')); ?>
            <div class="col-md-2 col-xs-12 col-sm-5">
                <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo $detail->phone_number;?>">
            </div>
        </div>
<?php if(isset($detail->id)) : ?>
        <div class="row form-group">
            <?php echo lang('label-detail-recruiter-id', 'id', array('class' => 'control-label col-md-1 col-sm-3')); ?>
            <div class="col-md-2 col-xs-12 col-sm-5">
                <input type="text" class="form-control" id="id" value="<?php echo $detail->id;?>" readonly>
            </div>
        </div>
        <div class="row form-group">
            <?php echo lang('label-detail-recruiter-version', 'version', array('class' => 'control-label col-md-1 col-sm-3')); ?>
            <div class="col-md-2 col-xs-12 col-sm-5">
                <input type="text" class="form-control" id="version" value="<?php echo $detail->version;?>" readonly>
            </div>
        </div>
<?php endif; ?>
    </div>
<?php require_once dirname(__DIR__).'/delete_dialog.php'; ?>
