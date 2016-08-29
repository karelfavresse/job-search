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
<button type="button" class="btn btn-default navbar-btn" onclick="setActionData();doAction('saveActions')" title="<?php echo lang('button-tip-save-ad-actions'); ?>" ><span class="glyphicon glyphicon-save"></span> <?php echo lang('button-title-save'); ?></button>
<span style="width:1em;display:inline-block"></span>
</div>
<div class="collapse navbar-collapse" id="toolbar">
<ul class="nav navbar-nav">
<li><button type="button" class="btn btn-default navbar-btn" onclick="setActionData();doAction('addAction')" title="<?php echo lang('button-tip-ad-addaction'); ?>" ><span class="glyphicon glyphicon-plus"></span> <?php echo lang('button-title-add'); ?></button></li>
</ul>
</div>
</div>
</nav>

<input type="hidden" id="action_id" name="action_id">
<input type="hidden" id="action_data" name="action_data">
<input type="hidden" id="action_start_page" name="action_start_page">
<script type="text/javascript">
var actionTable;
$(document).ready(function() {
                  actionTable = $('#actions').DataTable({
                                       "lengthMenu" : [[5,10,25,50,100],[5,10,25,50,100]],
                                       "pagingType" : "full_numbers",
                                        "pageLength" : <?php echo $actionPageLength; ?>,
                                        "displayStart" : <?php echo $actionPageStart; ?>,
                                          "columns" : [
                                          {"width" : "14em"},
                                          {"width" : "20em"},
                                          null,
                                          {"width" : "5em"}
                                          ]
                                       });
                  $('.action-datepicker').datepicker({
                                                     autoclose: true,
                                                     todayHighlight: true,
                                                     calendarWeeks: true,
                                                     todayBtn : "linked",
                                                     format: "yyyy-mm-dd",
                                                     orientation: "auto left",
                                                     enableOnReadonly: false,
                                                     showOnFocus: false,
                                                     container: "#actions"
                    });
                  });
function setActionData() {
    var data = actionTable.$('input, select, textarea').serialize();
    $('#action_data').val(data);
    $('#action_start_page').val(actionTable.page.info().page);
}
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
<td>
<div class="input-group date action-datepicker">
<input type="text" class="form-control" id="<?php echo 'action-date-' . $act->id; ?>" name="<?php echo 'action-date-' . $act->id; ?>" value="<?php echo $act->date; ?>">
<div class="input-group-addon">
<span class="glyphicon glyphicon-calendar"></span>
</div></div>
</td>
<td>
    <select class="form-control input-sm" style="width:100%" id="<?php echo 'action-type-' . $act->id; ?>" name="<?php echo 'action-type-' . $act->id; ?>">
<option value="CMMNT" <?php if ($act->type == 'CMMNT') echo 'selected="selected"'; ?> ><?php echo lang('action-type-cmmnt'); ?></option>
<option value="INTRVW" <?php if ($act->type == 'INTRVW') echo 'selected="selected"'; ?> ><?php echo lang('action-type-intrvw'); ?></option>
<option value="RSSENT" <?php if ($act->type == 'RSSENT') echo 'selected="selected"'; ?> ><?php echo lang('action-type-rssent'); ?></option>
<option value="GNRPLY" <?php if ($act->type == 'GNRPLY') echo 'selected="selected"'; ?> ><?php echo lang('action-type-gnrply'); ?></option>
<option value="NEGRPY" <?php if ($act->type == 'NEGRPY') echo 'selected="selected"'; ?> ><?php echo lang('action-type-negrpy'); ?></option>
<option value="INVITE" <?php if ($act->type == 'INVITE') echo 'selected="selected"'; ?> ><?php echo lang('action-type-invite'); ?></option>
    </select>
</td>
<td><textarea class="form-control" style="width:100%" id="<?php echo 'action-comment-' . $act->id; ?>" name="<?php echo 'action-comment-' . $act->id; ?>"><?php echo $act->comment; ?></textarea></td>
<td><a href="#" onclick="$('#action_id').val(<?php echo $act->id; ?>); setActionData();doAction('removeAction');" title="<?php echo lang('button-tip-ad-remove-action'); ?>"><span class="glyphicon glyphicon-remove"></span></a></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
