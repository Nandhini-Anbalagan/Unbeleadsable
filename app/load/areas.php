<?php
if(file_exists("../head.php")){
	include("../head.php");
	$dynamicFormId = Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN);
}
if($_SESSION['user']['level'] >= 50) {
	$areas = $db->getAreas();
	$leads = $db->getAgentLeads();
	$agents = $db->getAgentsAll();
}else {
	$areas = $db->getAreasByCountry($_SESSION['user']['user_country']);
	$leads = $db->getAgentLeadsByCountry($_SESSION['user']['user_country']);
	$agents = $db->getAgentsAllByCountry($_SESSION['user']['user_country']);
}


# Tokenizer container
$postActionArea = Tokenizer::add('post-action-area', 20, 'area');
$postCaseAreaDelete = Tokenizer::add('post-case-area-delete', 30, 'delete');
$postCaseAreaSingle = Tokenizer::add('post-case-area-single', 30, 'single');

?>
<div class="m-b-30">
	<h2 class="page-title pull-left">Areas Mapping</h2>
	<button class="btn btn-primary waves-effect waves-light pull-right" data-toggle="modal" data-target="#add-modal">New Area <i class="fa fa-plus"></i></button>
</div>
<div class="clearfix"></div>
<div class="p-t-20">
	<?php include "buyerSellerBullets.php" ?>
	<br><span class="label label-table label-primary">Leading Agent</span> &nbsp;&nbsp; <span class="label label-table label-danger">Subscribed Agent</span>
</div>
<div class="p-20">
	<table class="table table-striped table-responsive m-0" id="datatable-editable">
		<thead>
			<tr>
				<th width="35%">Name</th>
				<th width="5%">Map</th>
				<th width="50%">Agents</th>
				<th width="10%" class="text-center">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach($areas as $value) {
				$latlng = $value['area_latlng'] != "" ? explode(",", $value['area_latlng']) : "0,0";
				$agentsMapping = $db->getAgentsByAreaID($value['area_id']);
			?>
				<tr>
					<td><?php echo $value['area_name'] ?></td>
					<td><a href="#" data-toggle="modal" data-target="#edit-modal" data-map="true" data-lat="<?php echo $latlng[0] ?>" data-lng="<?php echo $latlng[1] ?>" data-id="<?php echo $value['area_id'] ?>" title="View Map" class="on-default edit-row"><i class="fa fa-map"></i></a></td>
					<td class="agents"><?php
						foreach ($agentsMapping as $v){

							if($v['mapping_type'] == "1") {
								if ($v['lead_type'] == "home_buyers")
									$bull = 'b';
								else if ($v['lead_type'] == "home_sellers")
									$bull = 's';
								else
									$bull = 's';
								echo "<img style='width: 20px;' src='assets/img/button_$bull.png' alt='buyer'>";
								echo '<span class="label label-table label-primary">' . $v['lead_name'] . '</span><br>';
							}else {
								if ($v['agent_slug'] == "home_buyers")
									$bull = 'b';
								else if ($v['agent_slug'] == "home_sellers")
									$bull = 's';
								else
									$bull = 's';
								echo "<img style='width: 20px;' src='assets/img/button_$bull.png' alt='buyer'>";
								echo '<span class="label label-table label-danger">' . $v['agent_name'] . '</span><br>';
							}
						}
					?></td>
					<td class="actions text-center">
						<a href="#" data-toggle="modal" data-target="#edit-modal" data-lat="<?php echo $latlng[0] ?>" data-lng="<?php echo $latlng[1] ?>" data-id="<?php echo $value['area_id'] ?>" title="Edit Area" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
						<?php if($_SESSION['user']["level"] > 20){ ?>
						<a href="#" data-id="<?php echo $value['area_id'] ?>" class="on-default remove-row" title="Delete Area"><i class="fa fa-trash-o"></i></a>
						<?php } ?>
					</td>
				</tr>
			<?php
			}
			if(count($areas) == 0)
				echo "<tr><td colspan='6' class='text-center'><i>oupps, no area found. Why don't you add one right now?</i></td></tr>"
			?>
		</tbody>
	</table>

	<br><br>
</div>

<div id="add-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content p-0 b-0">
			<div class="panel panel-color panel-primary">
				<div class="panel-heading">
					<button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true">×</button>
					<h2 class="panel-title text-center">Add New Area</h2>
				</div>
				<div class="panel-body">
					<form class="form-horizontal" role="form" data-parsley-validate="" novalidate>
						<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-area', 20, 'area'); ?>">
						<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-area-add', 30, 'add'); ?>">
						<input type="hidden" name="latlng">
						<input type="hidden" name="country">

						<div class="form-group">
							<label for="areaNameAdd" class="col-sm-12">Name</label>
							<div class="col-sm-12">
								<input type="text" class="form-control" required="" id="areaNameAdd" name="areaName" placeholder="Area Name">
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-12">
								<div id="map_canvas_add" style="height: 300px; width: 100%; border-radius: 5px; border: 1px solid #E3E3E3;"></div>
							</div>
						</div>

						<div class="form-group">
							<label for="agents" class="col-sm-12">Customers</label>
							<div class="col-sm-12">
								<select class="select2 select2-multiple" name="agents[]" multiple="multiple" multiple data-placeholder="Choose ...">
									<optgroup label="Leads">
										<?php foreach ($leads as $l) {
											if ($l['lead_type'] == "home_buyers")
												$bull = 'b';
											else if ($l['lead_type'] == "home_sellers")
												$bull = 's';
											else
												$bull = 's';
											echo "<option value='l_".$l['internal_id']."' class='".$bull."'>".$l['lead_name']." - ".$l['lead_agency']." - ".$l['lead_areas']."</option>";
										} ?>
									</optgroup>

									<optgroup label="Agents">
										<?php foreach ($agents as $a) {
											if ($a['agent_slug'] == "home_buyers")
												$bull = 'b';
											else if ($a['agent_slug'] == "home_sellers")
												$bull = 's';
											else
												$bull = 's';
											echo "<option value='a_".$a['internal_id']."' class='".$bull."'>".$a['agent_name']." - ".$a['agent_agency']." - ".$a['agent_areas']."</option>";
										} ?>
									</optgroup>

								</select>
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-offset-4 col-sm-8">
								<button type="submit" class="btn btn-primary waves-effect waves-light">Add</button>
								<button type="reset" class="btn btn-danger waves-effect waves-light m-l-5" data-dismiss="modal">Cancel</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content p-0 b-0">
			<div class="panel panel-color panel-primary">
				<div class="panel-heading" target="head" style="display: none">
					<button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true">×</button>
					<h2 class="panel-title text-center">Edit Area</h2>
				</div>
				<div class="panel-body">
					<form class="form-horizontal" role="form" data-parsley-validate="" novalidate>
						<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-area', 20, 'area'); ?>">
						<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-area-edit', 30, 'edit'); ?>">
						<input type="hidden" name="id" value="">
						<input type="hidden" name="latlng">
						<input type="hidden" name="country">

						<div class="form-group" target="name" style="display: none">
							<label for="areaNameEdit" class="col-sm-12">Name</label>
							<div class="col-sm-12">
								<input type="text" class="form-control" required="" id="areaNameEdit" name="areaName" placeholder="Area Name">
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-12">
								<div id="map_canvas_edit" style="height: 300px; width: 100%; border-radius: 5px; border: 1px solid #E3E3E3;"></div>
							</div>
						</div>

						<div class="form-group" target="agents" style="display: none">
							<label for="agents" class="col-sm-12">Agents / Leads</label>
							<div class="col-sm-12">
								<select class="select2 select2-multiple" name="agents[]" multiple="multiple" multiple data-placeholder="Choose ...">
									<optgroup label="Leads">
										<?php foreach ($leads as $l) {
											if ($l['lead_type'] == "home_buyers")
												$bull = 'b';
											else if ($l['lead_type'] == "home_sellers")
												$bull = 's';
											else
												$bull = 's';
											echo "<option value='l_".$l['internal_id']."' class='".$bull."'>".$l['lead_name']." - ".$l['lead_agency']."</option>";
										} ?>
									</optgroup>

									<optgroup label="Agents">
										<?php foreach ($agents as $a) {
											if ($a['agent_slug'] == "home_buyers")
												$bull = 'b';
											else if ($a['agent_slug'] == "home_sellers")
												$bull = 's';
											else
												$bull = 's';
											echo "<option value='a_".$a['internal_id']."' class='".$bull."'>".$a['agent_name']." - ".$a['agent_agency']."</option>";
										} ?>
									</optgroup>

								</select>
							</div>
						</div>

						<div class="form-group ">
							<div class="col-sm-7 col-sm-offset-4">
								<input id="deleteLead" type="checkbox" class="styledCheckbox" name="deleteArea">
								<label for="deleteLead" style="vertical-align: super;">Delete Area</label>
							</div>
						</div>


						<div class="form-group" target="action" style="display: none">
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
	$("#datatable-editable").DataTable({
		language: {
			emptyTable: "Oopps, no data found, are you sure you have any?"
		},
		order: [],
		columnDefs: [
			{ "orderable": false, "targets": [1,2,3] }
		],
		fixedHeader: true,
		autoWidth: false,
		responsive: true,
		"bStateSave": true
	});

	//$(".select2").select2();
	var latlng = new google.maps.LatLng(45.5016889,-73.56725599999999), map = null, marker = null, input = null, autocomplete = null;
	var mapOptions = {
			draggable: false,
			center: latlng,
			zoom: 10,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};

	function formatSelect (agent){
		if (!agent.id) { return agent.text; }
		return "<img style='width: 20px;' src='assets/img/button_"+agent.css+".png' alt='buyer'>"+agent.text;
	}

	$(".select2").select2({
		formatResult: formatSelect,
		insertTag: formatSelect
	});

	$('#add-modal').on('shown.bs.modal', function(){
		setGoogleMap('map_canvas_add','areaNameAdd');
		google.maps.event.trigger(map, 'resize');
		map.setCenter(latlng);
	});

	$('#edit-modal').on('shown.bs.modal', function(e) {
		$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo $postActionArea; ?>">'
			+ '<input type="hidden" name="case" value="<?php echo $postCaseAreaSingle; ?>">'
			+ '<input type="hidden" name="id" value="' + $(e.relatedTarget).data('id') + '">');
		$('#<?php echo $dynamicFormId; ?>').submit();
		$('#<?php echo $dynamicFormId; ?>').empty();

		latlng = new google.maps.LatLng(parseFloat($(e.relatedTarget).data('lat')), parseFloat($(e.relatedTarget).data('lng')));
		setGoogleMap('map_canvas_edit','areaNameEdit');
		google.maps.event.trigger(map, 'resize');
		map.setCenter(new google.maps.LatLng(parseFloat($(e.relatedTarget).data('lat')), parseFloat($(e.relatedTarget).data('lng'))));
		map.setZoom(13);

		if(!$(e.relatedTarget).data('map')){
			$("div[target='head']").slideDown();
			$("div[target='name']").slideDown();
			$("div[target='agents']").slideDown();
			$("div[target='delete']").slideDown();
			$("div[target='action']").slideDown();
		}
	});

	$('#edit-modal').on('hidden.bs.modal', function () {
		$("div[target='head']").hide();
		$("div[target='name']").hide();
		$("div[target='agents']").hide();
		$("div[target='delete']").hide();
		$("div[target='action']").hide();
	})

	$('a.remove-row').on('click', function(e){
		e.preventDefault();
		var id = $(this).data('id');
		swal({
			title: "Are you sure?",
			text: "You will not be able to recover this Area!",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes, delete it!",
			closeOnConfirm: false
		}, function(){
			$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo $postActionArea; ?>">'
				+ '<input type="hidden" name="case" value="<?php echo $postCaseAreaDelete; ?>">'
				+ '<input type="hidden" name="id" value="' + id + '">');
			$('#<?php echo $dynamicFormId; ?>').submit();
			$('#<?php echo $dynamicFormId; ?>').empty();
		});
	});

	function setGoogleMap(mapDiv, autocompleteInput){
		map = new google.maps.Map(document.getElementById(mapDiv), mapOptions);
		input = document.getElementById(autocompleteInput);

		marker = new google.maps.Marker({position: latlng, animation: google.maps.Animation.DROP, map: map});
		autocomplete = new google.maps.places.Autocomplete(input, {types: ["geocode"]});

		autocomplete.bindTo('bounds', map);

		google.maps.event.addListener(autocomplete, 'place_changed', function() {
			var place = autocomplete.getPlace();
			map.setCenter(place.geometry.location);
			map.setZoom(13);
			marker.setAnimation(google.maps.Animation.DROP);
			marker.setPosition(place.geometry.location);
			$("input[name='latlng']").val(place.geometry.location.lat() + "," + place.geometry.location.lng());
			$("input[name='country']").val(place.formatted_address.split(", ").pop());
		});

		google.maps.event.addListener(map, "click", function(e){
			marker.setPosition(e.latLng);
			console.log(e);
			$("input[name='latlng']").val(e.latLng.lat() + "," + e.latLng.lng());
		});
	}

});
</script>
