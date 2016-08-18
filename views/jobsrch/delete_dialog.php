<?php /* Copyright 2016 Karel Favresse */ ?>
<input type="hidden" name="do_delete" id="do_delete"/>
<script type="text/javascript">
$(document).ready(function() {
                  $("#deleteConfirmBox").on('hidden.bs.modal', function(){
                                            if($("#do_delete").val()=="true")
                                            doAction('delete');
                                            });
                  });
</script>
<div id="deleteConfirmBox" class="modal fade" role="dialog">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h4 class="modal-title"><?php echo str_replace('{type}', lang("type-$type"), lang('dialog-title-delete')); ?></h4>
</div>
<div class="modal-body">
<p><?php echo str_replace(array('{name}', '{type}'), array($delete_name, lang("type-$type")), lang('dialog-text-delete')); ?>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-danger" id="confirmDeleteButton" data-dismiss="modal" onclick="$('#do_delete').val('true');"><span class="glyphicon glyphicon-trash"></span> <?php echo lang('button-title-delete'); ?></button>
<button type="button" class="btn btn-default" data-dismiss="modal" onclick="$('#do_delete').val('false');"><span class="glyphicon glyphicon-remove"></span> <?php echo lang('button-title-cancel'); ?></button>
</div>
</div>
</div>
</div>