<div class="row form-group">
    <div class="col-md-1 col-sm-3 control-label">
        <label for="street">Street:</label>
    </div>
    <div class="col-md-3 col-xs-12 col-sm-5">
        <input type="text" class="form-control" id="street" name="street" value="<?php echo $address->street;?>">
    </div>
    <div class="col-md-1 col-sm-3 control-label">
        <label for="house_number">Number:</label>
    </div>
    <div class="col-md-1 col-xs-12 col-sm-5">
        <input type="text" class="form-control" id="house_number" name="house_number" value="<?php echo $address->house_number;?>">
    </div>
    <div class="col-md-1 col-sm-3 control-label">
        <label for="box_number">Box:</label>
    </div>
    <div class="col-md-1 col-xs-12 col-sm-5">
        <input type="text" class="form-control" id="box_number" name="box_number" value="<?php echo $address->box_number;?>">
    </div>
</div>
<div class="row form-group">
    <div class="col-md-1 col-sm-3 control-label">
        <label for="pobox_number">PO Box:</label>
    </div>
    <div class="col-md-1 col-xs-12 col-sm-5 ">
        <input type="text" class="form-control" id="pobox_number" name="pobox_number" value="<?php echo $address->pobox_number;?>">
    </div>
</div>
<div class="row form-group">
    <div class="col-md-1 col-sm-3 control-label">
        <label for="zip_code">Post Code:</label>
    </div>
    <div class="col-md-1 col-xs-12 col-sm-5">
        <input type="text" class="form-control" id="zip_code" name="zip_code" value="<?php echo $address->zip_code;?>">
    </div>
    <div class="col-md-1 col-sm-3 control-label">
        <label for="locality">Locality:</label>
    </div>
    <div class="col-md-5 col-xs-12 col-sm-5">
        <input type="text" class="form-control" id="locality" name="locality" value="<?php echo $address->locality;?>">
    </div>
</div>
<div class="row form-group">
    <div class="col-md-1 col-sm-3 control-label">
        <label for="country">Country:</label>
    </div>
    <div class="col-md-3 col-xs-12 col-sm-5">
        <input type="text" class="form-control" id="country" name="country" value="<?php echo $address->country;?>">
    </div>
</div>