<html>
    <head>
        <title><?php echo $title ?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/pdfmake-0.1.18/dt-1.10.12/af-2.1.2/b-1.2.1/b-colvis-1.2.1/b-html5-1.2.1/b-print-1.2.1/cr-1.3.2/fc-3.2.2/fh-3.1.2/kt-2.1.2/r-2.1.0/rr-1.1.2/sc-1.4.2/se-1.2.0/datatables.min.css"/>
        <script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/pdfmake-0.1.18/dt-1.10.12/af-2.1.2/b-1.2.1/b-colvis-1.2.1/b-html5-1.2.1/b-print-1.2.1/cr-1.3.2/fc-3.2.2/fh-3.1.2/kt-2.1.2/r-2.1.0/rr-1.1.2/sc-1.4.2/se-1.2.0/datatables.min.js"></script>
        <script>
            $(document).ready(function(){
                  $('[data-toggle="tooltip"]').tooltip();
                  });
        </script>
    </head>
    <body style="margin-top:5em;">
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?php echo site_url('jobsrch'); ?>">Job Search</a>
                </div>
                <div class="collapse navbar-collapse" id="navbar">
                    <ul class="nav navbar-nav">
                        <li><a href="<?php echo site_url('jobsrch/recruiter'); ?>">Recruiters</a></li>
                        <li><a href="#">Page 2</a></li>
                        <li><a href="#">Page 3</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <h1><?php echo $title ?></h1>
<?php
    $msgl = UIMessage::getErrorMessages();
    if($msgl != NULL) :
        foreach($msgl as $msg) : ?>
<div class="alert alert-danger">
<strong>Error</strong> <?php echo $msg; ?>
</div>
<?php
    endforeach;
    endif;?>
<?php
    $msgl = UIMessage::getWarningMessages();
    if($msgl != NULL) :
        foreach($msgl as $msg) : ?>
<div class="alert alert-warning">
<strong>Warning</strong> <?php echo $msg; ?>
</div>
<?php
    endforeach;
    endif;
    ?>
<?php
    $msgl = UIMessage::getSuccessMessages();
    if($msgl != NULL) :
        foreach($msgl as $msg) : ?>
<div class="alert alert-success">
<strong>Success</strong> <?php echo $msg; ?>
</div>
<?php
    endforeach;
    endif;
    ?>
<?php
    $msgl = UIMessage::getInfoMessages();
    if($msgl != NULL) :
        foreach($msgl as $msg) : ?>
<div class="alert alert-info">
<strong>Info</strong> <?php echo $msg; ?>
</div>
<?php
    endforeach;
    endif;
    ?>
<?php
    UIMessage::clearAll();
    ?>
