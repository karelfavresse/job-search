<div class="row form-group">
    <label for="street" class="col-md-1 control-label">Street:</label>
    <div class="col-md-3 col-xs-11">
        <input type="text" class="form-control" id="street" name="street" value="<?php echo $address->street;?>">
    </div>
    <label for="house_number" class="col-md-1 control-label">Number:</label>
    <div class="col-md-1 col-xs-11">
        <input type="text" class="form-control" id="house_number" name="house_number" value="<?php echo $address->house_number;?>">
    </div>
    <label for="box_number" class="col-md-1 control-label">Box:</label>
    <div class="col-md-1 col-xs-11">
        <input type="text" class="form-control" id="box_number" name="box_number" value="<?php echo $address->box_number;?>">
    </div>
</div>
<div class="row form-group">
    <label for="pobox_number" class="col-md-1 control-label">PO Box:</label>
    <div class="col-md-1 col-xs-11">
        <input type="text" class="form-control" id="pobox_number" name="pobox_number" value="<?php echo $address->pobox_number;?>">
    </div>
</div>
<div class="row form-group">
    <label for="zip_code" class="col-md-1 control-label">Post Code:</label>
    <div class="col-md-1 col-xs-11">
        <input type="text" class="form-control" id="zip_code" name="zip_code" value="<?php echo $address->zip_code;?>">
    </div>
    <label for="locality" class="col-md-1 control-label">Locality:</label>
    <div class="col-md-3 col-xs-11">
        <input type="text" class="form-control" id="locality" name="locality" value="<?php echo $address->locality;?>">
    </div>
</div>
<div class="row form-group">
    <label for="country" class="col-md-1 control-label">Country:</label>
    <div class="col-md-3 col-xs-11">
        <input type="text" class="form-control" id="country" name="country" value="<?php echo $address->country;?>">
    </div>
</div>