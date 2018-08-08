<?php
    if(file_exists("../head.php")){
        include("../head.php");
        $dynamicFormId = Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN);
    }
    
    $groups = $db->getGroups();
?>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default m-t-20">
            <div class="panel-body p-0">
                <div class="table-responsive p-20">
                    <table class="table table-striped m-0 m-b-10">
                        <thead>
                            <tr>
                                <th width="25%">Name</th>
                                <th width="45%">Emails</th>
                                <th width="10%" class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($groups) > 0): ?>
                                <?php foreach($groups as $group): ?>
                                    <tr>
                                        <td><?php echo $group['group_name']; ?></td>
                                        <td><?php echo $group['group_emails']; ?></td>
                                        <td class="text-right">
                                            <a href="#" data-toggle="modal" data-target="#edit-group-modal" data-id="<?php echo $group['email_group_id'] ?>" title="Edit Group" class="on-default edit-row"><i class="fa fa-pencil m-r-5"></i></a>
                                            <a href="#" data-id="<?php echo $group['email_group_id']?>" class="on-default remove-row delete"><i class="fa fa-trash-o m-r-10"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr class="text-center">
                                    <td colspan="3">No group to display...</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="edit-group-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content p-0 b-0">
            <div class="panel panel-color panel-primary">
                <div class="panel-heading">
                    <button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true">×</button>
                    <h2 class="panel-title text-center">Edit Group</h2>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" data-parsley-validate="" novalidate>
                        <input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-email-group-page', 30, 'email'); ?>">
                        <input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-email-group-page-update', 30, 'update-group'); ?>">
                        <input type="hidden" name="id" value="-1">

                        <div class="form-group">
                            <label for="name" class="col-lg-2 control-label">Name</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" required="" data-parsley-length="[5,150]" id="name" name="name" placeholder="Name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="slug"class="col-lg-2 control-label">Emails</label>
                            <div class="col-lg-10">
                                <div class="tags-default">
                                    <input name="emails" type="text" value="" data-role="tagsinput" placeholder="Add emails"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-8">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                                <button type="reset" class="btn btn-danger waves-effect waves-light m-l-5" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#edit-group-modal').on('show.bs.modal', function(e){
            $('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-email-group-page', 30, 'email'); ?>">'
                + '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-email-group-page-get', 30, 'get-group'); ?>">'
                + '<input type="hidden" name="id" value="' + $(e.relatedTarget).data('id') + '">');
            $('#<?php echo $dynamicFormId; ?>').submit();
            $('#<?php echo $dynamicFormId; ?>').empty();
        });
        
        $('body').on('click', '.group-container .delete', function(e){
        	e.preventDefault();
        	var id = $(this).data('id');
        
        	swal({   
        		title: "Are you sure?",   
        		text: "You will not be able to recover this group!",   
        		type: "warning",   
        		showCancelButton: true,   
        		confirmButtonColor: "#DD6B55",
        		confirmButtonText: "Yes, delete it!",   
        		closeOnConfirm: false
        	}, function(){
        		$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").append(
        			'<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-email-group-page', 30, 'email'); ?>">'
        			+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-email-group-page-delete', 30, 'delete-group'); ?>">'
        			+ '<input type="hidden" name="id" value="' + id + '">');
        		$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").submit();
        		$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").empty();
        	});
        });
    });
</script>