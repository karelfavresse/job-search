<?php /* Copyright 2016 Karel Favresse */ ?>
<?php echo form_open('jobsrch/login'); ?>

<input type="hidden" name="action" id="action"/>

<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

    <div class="row form-group">
        <?php echo lang('label-login-name', 'name', array('class' => 'control-label col-md-1 col-sm-3')); ?>
        <div class="col-sm-5 col-md-3 col-xs-12">
            <input type="text" class="form-control" id="name" name="name" value="<?php echo    $login_name; ?>">
        </div>
    </div>
    <div class="row form-group">
        <?php echo lang('label-login-pwd', 'pwd', array('class' => 'control-label col-md-1 col-sm-3')); ?>
        <div class="col-sm-5 col-md-3 col-xs-12">
            <input type="password" class="form-control" id="pwd" name="pwd" value="<?php echo   $login_pwd; ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-md-1 col-sm-3"></div>
        <div class="col-md-11 col-sm-9 col-xs-12">
            <button type="submit" class="btn btn-primary" onclick="$('#action').val('login')"><span class="glyphicon glyphicon-log-in"></span> <?php echo lang('button-title-login'); ?></button>
        </div>
    </div>
</form>