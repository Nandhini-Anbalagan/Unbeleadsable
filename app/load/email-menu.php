<?php 
    if(file_exists("../head.php")){
        include("../head.php");
        $dynamicFormId = Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN);
    }
    
    if(isset($_GET['page']))
        $page = $_GET['page'];
    
    # Tokenizer container
    $postActionEmail = Tokenizer::add('post-action-email', 20, 'email');
    $postCaseEmailSend = Tokenizer::add('post-case-email', 30, 'send');
    
    $templates = $db->getTemplates();
?>

<div class="col-lg-3 col-md-4">
    <div class="p-20">
        <a class="btn btn-danger btn-rounded btn-custom btn-block waves-effect waves-light" data-toggle="modal" data-target="#compose-modal">Compose</a>
        <div class="list-group mail-list  m-t-20">
            <a href="emails" class="list-group-item b-0 <?php echo $page == "emails" ? "active" : "" ?>"><i class="fa fa-paper-plane-o m-r-10"></i>Sent Mail</a>
            <a href="templates" class="list-group-item b-0 <?php echo $page == "templates" || $page == "email-menu" ? "active" : "" ?>"><i class="fa fa-file-text-o m-r-10"></i>Templates</a>
        </div>
    </div>
</div>

<div id="compose-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content p-0 b-0">
            <div class="panel panel-color panel-primary">
                <div class="panel-heading">
                    <button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true">×</button>
                    <h2 class="panel-title text-center">Compose Email</h2>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" data-parsley-validate="" novalidate>
                        <input type="hidden" name="action" value="<?php echo $postActionEmail; ?>">
                        <input type="hidden" name="case" value="<?php echo $postCaseEmailSend; ?>">
                        <div class="form-group">
                            <label for="to" class="col-lg-2 control-label">To</label>
                            <div class="col-lg-10">
                                <?php if(!isset($_GET['target'])): ?>
                                    <select name="to" class="form-control">
                                        <optgroup label="Groups">
                                            <?php foreach($db->getGroups() as $group): ?>
                                                <option value="<?php echo $group['email_group_id']; ?>"><?php echo $group['group_name']; ?> - [ID: <?php echo $group['email_group_id']; ?>]</option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                        <optgroup label="Leads">
                                            <?php foreach($db->getAgentLeads() as $lead): ?>
                                                <option value="<?php echo $lead['lead_email']; ?>"><?php echo $lead['lead_name']; ?> - <?php echo $lead['lead_email']; ?> - [ID: <?php echo $lead['lead_id']; ?>]</option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                    </select>
                                <?php else: ?>
                                    <input type="text" name="to" class="form-control" value="<?php echo $_GET['target']; ?>" readonly>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="slug"class="col-lg-2 control-label">Template</label>
                            <div class="col-lg-10">
                                <select name="template" class="form-control">
                                    <option value="" selected>Blank</option>
                                    <?php foreach($templates as $template): ?>
                                        <option value="<?php echo $template['content']; ?>"><?php echo $template['name']; ?> (<?php echo $template['slug']; ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label class="col-lg-2 control-label"> Content</label>
                            <div class="col-lg-10">
                                <input id="compose-input" type="hidden" name="content">
                                <textarea id="compose-textarea" class="form-control" placeholder="Content..."></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-8">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">Send</button>
                                <button type="reset" class="btn btn-danger waves-effect waves-light m-l-5 cancel" data-dismiss="modal">Cancel</button>
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
        $('select[name="template"]').select2({
            placeholder: "Choose a template..."
        });
        
        $('select[name="template"]').on('change', function(){
            tinymce.get('compose-textarea').setContent($(this).val());
        });
        
        $('select[name="to"]').select2();
        
        tinymce.init({
            selector: "#compose-textarea",
            theme: "modern",
            height:200,
            menubar: false,
            plugins: [
                "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "save table contextmenu directionality emoticons template paste textcolor"
            ],
            toolbar: "forecolor bold italic | alignleft aligncenter alignright alignjustify | image bullist numlist outdent indent",
            style_formats: [
                {title: 'Bold text', inline: 'b'},
                {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
                {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
                {title: 'Example 1', inline: 'span', classes: 'example1'},
                {title: 'Example 2', inline: 'span', classes: 'example2'},
                {title: 'Table styles'},
                {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
            ],
            setup: function (editor) {
                editor.on('init', function(){
                    editor.setContent($('#compose-input').val());
                });
                
                editor.on('change', function () {
                    $('#compose-input').val(editor.getContent().replace(/</g, '&lt;').replace(/>/g, '&gt;'));
                    editor.save();
                });
            }
        });
        
        <?php if(isset($_GET['target'])): ?>
            $('#compose-modal').modal('show');
            $('.cancel').on('click', function(){
                window.location.href = window.location.href.replace(/(\?target=.*)/i, "");
            });
        <?php endif; ?>
    });
</script>