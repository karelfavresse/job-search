<?php /* Copyright 2016 Karel Favresse */ ?>
<?php
    // Do login check
    require_once dirname(dirname(__DIR__)) . '/libraries/jobsrch/Authentication_library.php';
    require_once dirname(dirname(__DIR__)) . '/libraries/jobsrch/Authorization_library.php';
?>
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
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js"></script>
        <link rel="stylesheet" href="/ci/select2-bootstrap.css">
        <script>
            $(document).ready(function(){
                  $('[data-toggle="tooltip"]').tooltip();
                  });
        </script>
        <script type="text/javascript">
            function doAction($action) {
                $("#action").val($action);
                $form = $("#form");
                $.post( $form.attr('action'), $form.serialize(),
                    function(data) {
                       $content = $("#content");
                       $content.empty();
                       $content.append(data);
                    }
                       ).fail(function (){
                              document.location.href = '<?php echo site_url('jobsrch/login'); ?>';
                              });
            }
        </script>
    </head>
    <body style="margin-top:3em;">
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?php echo site_url('jobsrch'); ?>"><?php echo lang('navbar-brand-name'); ?></a>
                </div>
                <div class="collapse navbar-collapse" id="navbar">
<?php if (Authentication_library::is_logged_in()) : ?>
                    <ul class="nav navbar-nav">
<?php if (Authorization_library::is_authorized('view_ads')) : ?>
                        <li><a href="<?php echo site_url('jobsrch/ad'); ?>"><?php echo lang('navbar-adslink-name'); ?></a></li>
<?php endif; ?>
<?php if (Authorization_library::is_authorized('view_recruiters')) : ?>
                        <li><a href="<?php echo site_url('jobsrch/recruiter'); ?>"><?php echo lang('navbar-recruiterslink-name'); ?></a></li>
<?php endif; ?>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><p class="navbar-text"><?php echo Authentication_library::current_user(); ?></p></li>
                        <li><a href="<?php echo site_url('jobsrch/logout'); ?>"><span class="glyphicon glyphicon-log-in"></span> <?php echo lang('navbar-logout'); ?></a></li>
                    </ul>
<?php endif; ?>
                </div>
            </div>
        </nav>
        <div class="container-fluid form-horizontal" id="detail">
            <h1><?php echo $title ?></h1>
<?php echo form_open('jobsrch/' . $controller_name, array('id' => 'form')); ?>
<input type="hidden" name="action" id="action">
<div id="content">