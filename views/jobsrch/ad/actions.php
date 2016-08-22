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
<button type="button" class="btn btn-default navbar-btn" onclick="doAction('back')" title="<?php echo lang('button-tip-back-list-ad'); ?>" ><span class="glyphicon glyphicon-menu-left"></span> <?php echo lang('button-title-back'); ?></button>
<span style="width:1em;display:inline-block"></span>
<button type="button" class="btn btn-default navbar-btn" onclick="doAction('saveActions')" title="<?php echo lang('button-tip-save-ad-actions'); ?>" ><span class="glyphicon glyphicon-save"></span> <?php echo lang('button-title-save'); ?></button>
<span style="width:1em;display:inline-block"></span>
</div>
<div class="collapse navbar-collapse" id="toolbar">
<ul class="nav navbar-nav">
<li><button type="button" class="btn btn-default navbar-btn" onclick="doAction('addAction')" title="<?php echo lang('button-tip-ad-newaction'); ?>" ><span class="glyphicon glyphicon-plus"></span> <?php echo lang('button-title-new'); ?></button></li>
</ul>
</div>
</div>
</nav>

<script type="text/javascript">
$(document).ready(function() {
                  $('#actions').DataTable({
                                       "lengthMenu" : [[5,10,25,50,100],[5,10,25,50,100]],
                                       "pagingType" : "full_numbers",
                                          "pageLength" : 10
                                       });
                  });
</script>
<table id="actions" class="table table-striped table-bordered table-responsive" width="100%">
<thead>
<tr>
<th><?php echo lang('list-header-adaction-date'); ?></th>
<th><?php echo lang('list-header-adaction-type'); ?></th>
<th><?php echo lang('list-header-adaction-comment'); ?></th>
<th><?php echo lang('list-header-action'); ?></th>
</tr>
</thead>
<tbody>
<?php foreach ( $actions as $act ) : ?>
<tr>
<td><?php echo $act->date; ?></td>
<td><?php echo $act->type; ?></td>
<td><?php echo $act->comment; ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
