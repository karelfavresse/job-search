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
                <button type="submit" class="btn btn-default navbar-btn" onclick="$('#action').val('search')" title="<?php echo lang('button-tip-search-recruiter'); ?>" data-toggle="tooltip" data-container="body" data-placement="auto bottom"><span class="glyphicon glyphicon-search"></span> <?php echo lang('button-title-search'); ?></button>
                <button type="submit" class="btn btn-default navbar-btn" onclick="$('#action').val('reset')" title="<?php echo lang('button-tip-reset-recruiter'); ?>" data-toggle="tooltip" data-container="body" data-placement="auto bottom"><span class="glyphicon glyphicon-erase"></span> <?php echo lang('button-title-reset'); ?></button>
                <span style="width:1em;display:inline-block"></span>
            </div>
            <div class="collapse navbar-collapse" id="toolbar">
                <ul class="nav navbar-nav">
                    <li><button type="submit" class="btn btn-default navbar-btn" onclick="$('#action').val('new')" title="<?php echo lang('button-tip-new-recruiter'); ?>" data-toggle="tooltip" data-container="body" data-placement="auto bottom"><span class="glyphicon glyphicon-plus"></span> <?php echo lang('button-title-new'); ?></button></li>
                </ul>
            </div>
        </div>
    </nav>

    <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

    <div class="container-fluid form-horizontal" id="searchpanel">
        <div class="row form-group">
            <?php echo lang('label-search-recruiter-name', 'name', array('class' => 'control-label col-sm-3 col-md-1')); ?>
            <div class="col-sm-5 col-xs-12">
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $crit->name ;?>">
            </div>
        </div>
        <div class="row form-group">
            <?php echo lang('label-search-maxrows', 'maxrows', array('class' => 'control-label col-sm-3 col-md-1')); ?>
            <div class="col-sm-2 col-xs-12">
                <input type="text" class="form-control" id="maxrows" name="maxrows" value="<?php echo $crit->maxrows ;?>">
            </div>
        </div>
    </div>


</form>