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
                <button type="submit" class="btn btn-default navbar-btn" onclick="$('#action').val('search')" title="Search recruiters" data-toggle="tooltip" data-container="body" data-placement="auto bottom"><span class="glyphicon glyphicon-search"></span> Search</button>
                <button type="submit" class="btn btn-default navbar-btn" onclick="$('#action').val('reset')" title="Reset search criteria" data-toggle="tooltip" data-container="body" data-placement="auto bottom"><span class="glyphicon glyphicon-erase"></span> Reset</button>
                <span style="width:1em;display:inline-block"></span>
            </div>
            <div class="collapse navbar-collapse" id="toolbar">
                <ul class="nav navbar-nav">
                    <li><button type="submit" class="btn btn-default navbar-btn" onclick="$('#action').val('new')" title="Create a new recruiter" data-toggle="tooltip" data-container="body" data-placement="auto bottom"><span class="glyphicon glyphicon-plus"></span> New</button></li>
                </ul>
            </div>
        </div>
    </nav>

    <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

    <div class="container-fluid form-horizontal" id="searchpanel">
        <div class="row form-group">
            <label for="name" class="control-label col-sm-3 col-md-1">Name:</label>
            <div class="col-sm-5 col-xs-12">
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $crit->name ;?>">
            </div>
        </div>
        <div class="row form-group">
            <label for="maxrows" class="control-label col-sm-3 col-md-1">Max Rows:</label>
            <div class="col-sm-2 col-xs-12">
                <input type="text" class="form-control" id="maxrows" name="maxrows" value="<?php echo $crit->maxrows ;?>">
            </div>
        </div>
    </div>


</form>