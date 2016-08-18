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
<button type="button" class="btn btn-default navbar-btn" onclick="doAction('search')" title="<?php echo lang('button-tip-search-ad'); ?>" ><span class="glyphicon glyphicon-search"></span> <?php echo lang('button-title-search'); ?></button>
<button type="button" class="btn btn-default navbar-btn" onclick="doAction('reset')" title="<?php echo lang('button-tip-reset-ad'); ?>" ><span class="glyphicon glyphicon-erase"></span> <?php echo lang('button-title-reset'); ?></button>
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
<?php echo lang('label-search-ad-title', 'title', array('class' => 'control-label col-sm-3 col-md-1')); ?>
<div class="col-sm-5 col-xs-12">
<input type="text" class="form-control" id="title" name="title" value="<?php echo $crit->title ;?>">
</div>
</div>
<div class="row form-group">
<?php echo lang('label-search-ad-url', 'url', array('class' => 'control-label col-sm-3 col-md-1')); ?>
<div class="col-sm-5 col-xs-12">
<input type="text" class="form-control" id="url" name="url" value="<?php echo $crit->url ;?>">
</div>
</div>
<div class="row form-group">
<?php echo lang('label-search-ad-vdabreference', 'vdabreference', array('class' => 'control-label col-sm-3 col-md-1')); ?>
<div class="col-sm-5 col-xs-12">
<input type="text" class="form-control" id="vdabreference" name="vdabreference" value="<?php echo $crit->vdab_reference ;?>">
</div>
</div>
<div class="row form-group">
<?php echo lang('label-search-ad-company', 'company', array('class' => 'control-label col-sm-3 col-md-1')); ?>
<div class="col-sm-5 col-xs-12">
<input type="text" class="form-control" id="company" name="company" value="<?php echo $crit->company ;?>">
</div>
</div>
<div class="row form-group">
<?php echo lang('label-search-ad-contactname', 'contact_name', array('class' => 'control-label col-sm-3 col-md-1')); ?>
<div class="col-sm-5 col-xs-12">
<input type="text" class="form-control" id="contact_name" name="contact_name" value="<?php echo $crit->contact_name ;?>">
</div>
</div>
