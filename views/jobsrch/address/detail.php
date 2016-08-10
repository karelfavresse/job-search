<div class="row form-group">
    <?php echo lang('label-detail-address-street', 'street', array('class' => 'control-label col-md-1 col-sm-3')); ?>
    <div class="col-md-3 col-xs-12 col-sm-5">
        <input type="text" class="form-control" id="street" name="street" value="<?php echo $address->street;?>">
    </div>
    <?php echo lang('label-detail-address-housenumber', 'house_number', array('class' => 'control-label col-md-1 col-sm-3')); ?>
    <div class="col-md-1 col-xs-12 col-sm-5">
        <input type="text" class="form-control" id="house_number" name="house_number" value="<?php echo $address->house_number;?>">
    </div>
    <?php echo lang('label-detail-address-boxnumber', 'box_number', array('class' => 'control-label col-md-1 col-sm-3')); ?>
    <div class="col-md-1 col-xs-12 col-sm-5">
        <input type="text" class="form-control" id="box_number" name="box_number" value="<?php echo $address->box_number;?>">
    </div>
</div>
<div class="row form-group">
    <?php echo lang('label-detail-address-poboxnumber', 'pobox_number', array('class' => 'control-label col-md-1 col-sm-3')); ?>
    <div class="col-md-1 col-xs-12 col-sm-5 ">
        <input type="text" class="form-control" id="pobox_number" name="pobox_number" value="<?php echo $address->pobox_number;?>">
    </div>
</div>
<div class="row form-group">
    <?php echo lang('label-detail-address-zipcode', 'zip_code', array('class' => 'control-label col-md-1 col-sm-3')); ?>
    <div class="col-md-1 col-xs-12 col-sm-5">
        <input type="text" class="form-control" id="zip_code" name="zip_code" value="<?php echo $address->zip_code;?>">
    </div>
    <?php echo lang('label-detail-address-locality', 'locality', array('class' => 'control-label col-md-1 col-sm-3')); ?>
    <div class="col-md-5 col-xs-12 col-sm-5">
        <input type="text" class="form-control" id="locality" name="locality" value="<?php echo $address->locality;?>">
    </div>
</div>
<div class="row form-group">
    <?php echo lang('label-detail-address-country', 'country', array('class' => 'control-label col-md-1 col-sm-3')); ?>
    <div class="col-md-3 col-xs-12 col-sm-5">
        <input type="text" class="form-control" id="country" name="country" value="<?php echo $address->country;?>">
    </div>
</div>