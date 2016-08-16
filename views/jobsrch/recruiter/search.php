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
            <button type="button" class="btn btn-default navbar-btn" onclick="doAction('search')" title="<?php echo lang('button-tip-search-recruiter'); ?>" ><span class="glyphicon glyphicon-search"></span> <?php echo lang('button-title-search'); ?></button>
            <button type="button" class="btn btn-default navbar-btn" onclick="doAction('reset')" title="<?php echo lang('button-tip-reset-recruiter'); ?>" ><span class="glyphicon glyphicon-erase"></span> <?php echo lang('button-title-reset'); ?></button>
            <span style="width:1em;display:inline-block"></span>
        </div>
        <div class="collapse navbar-collapse" id="toolbar">
            <ul class="nav navbar-nav">
            </ul>
        </div>
    </div>
</nav>

<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

<div class="row form-group">
    <?php echo lang('label-search-recruiter-name', 'name', array('class' => 'control-label col-sm-3 col-md-1')); ?>
    <div class="col-sm-5 col-xs-12">
        <input type="text" class="form-control" id="name" name="name" value="<?php echo $crit->name ;?>">
    </div>
</div>
