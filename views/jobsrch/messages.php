<?php
    $msgl = UIMessage::getErrorMessages();
    if($msgl != NULL) :
    foreach($msgl as $msg) : ?>
<div class="alert alert-danger">
<strong><?php echo lang('label-error'); ?></strong> <?php echo $msg; ?>
</div>
<?php
    endforeach;
    endif;?>
<?php
    $msgl = UIMessage::getWarningMessages();
    if($msgl != NULL) :
    foreach($msgl as $msg) : ?>
<div class="alert alert-warning">
<strong><?php echo lang('label-warning'); ?></strong> <?php echo $msg; ?>
</div>
<?php
    endforeach;
    endif;?>
<?php
    $msgl = UIMessage::getSuccessMessages();
    if($msgl != NULL) :
    foreach($msgl as $msg) : ?>
<div class="alert alert-success">
<strong><?php echo lang('label-success'); ?></strong> <?php echo $msg; ?>
</div>
<?php
    endforeach;
    endif;?>
<?php
    $msgl = UIMessage::getInfoMessages();
    if($msgl != NULL) :
    foreach($msgl as $msg) : ?>
<div class="alert alert-info">
<strong><?php echo lang('label-info'); ?></strong> <?php echo $msg; ?>
</div>
<?php
    endforeach;
    endif;?>
<?php
    UIMessage::clearAll();
    ?>
