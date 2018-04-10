<!-- <div class="pull-right"><a href=""><i class="fa fa-pencil" aria-hidden="true"></i></a></div> -->
<h2 class="pull-left"><?php echo $lead['name'] ?></h2>
<div class="clearfix"></div>

<div class="row">
	<div class="col-sm-4">
		<div class="dropdown">
			<button type="button" class="btn btn-block btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $sta ?> <span class="caret"></span></button>
			<ul class="dropdown-menu status">
				<?php 
				foreach ($status as $s){
					$active = ($lead['status'] == $s['id'])?'active':'';
					echo '<li class="'.$active.'"><a href="javascript:void(0)" data-status="'.$s['id'].'" data-lead="'.$lead['id'].'">'.$s['name_en'].'</a></li>'; 
				} 
				?>
			</ul>
		</div>
	</div>

	<div class="col-sm-4">
		<div class="dropdown">
			<button type="button" class="btn btn-block btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $lead['type'] ?> <span class="caret"></span></button>
			<ul class="dropdown-menu type">
				<li class=""><a href="javascript:void(0)" data-id="<?php echo $lead['status'] ?>">Seller</a></li>
				<li class=""><a href="javascript:void(0)" data-id="<?php echo $lead['status'] ?>">Buyer</a></li>  
				<li class=""><a href="javascript:void(0)" data-id="<?php echo $lead['status'] ?>">Seller and Buyer</a></li> 
				<li class=""><a href="javascript:void(0)" data-id="<?php echo $lead['status'] ?>">Reft</a></li>
				<li class=""><a href="javascript:void(0)" data-id="<?php echo $lead['status'] ?>">Rental</a></li>
				<li class=""><a href="javascript:void(0)" data-id="<?php echo $lead['status'] ?>">Purchase</a></li>   
			</ul>
		</div>
	</div>
</div>

<script>
	$('body').on('click','.status li a', function(e){
		e.preventDefault(); 
		$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agentLead', 20, 'agentLead'); ?>">'
			+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-agentLead-status', 20, 'status'); ?>">'
			+ '<input type="hidden" name="id" value="' + $(this).data('lead') + '">'
			+ '<input type="hidden" name="status" value="' + $(this).data('status') + '">');
		$('#<?php echo $dynamicFormId; ?>').submit();
		$('#<?php echo $dynamicFormId; ?>').empty();
	});

	$('body').on('click','.type li a', function(e){
		e.preventDefault(); 
		$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agentLead', 20, 'agentLead'); ?>">'
			+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-agentLead-type', 20, 'type'); ?>">'
			+ '<input type="hidden" name="id" value="' + $(this).data('id') + '">'
			+ '<input type="hidden" name="text" value="' + $(this).text() + '">');
		$('#<?php echo $dynamicFormId; ?>').submit();
		$('#<?php echo $dynamicFormId; ?>').empty();
	});
</script>