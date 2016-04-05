<!-- Bread crumb is created dynamically -->
<!-- row -->
<div class="row">
	
	<!-- col -->
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			
			<!-- PAGE HEADER -->
			<i class="fa-fw fa fa-globe"></i> 
			Statistics
			<span>>  
				Global
			</span>
		</h1>
	</div>
	<!-- end col -->
	
</div>
<!-- end row -->

<!--
	The ID "widget-grid" will start to initialize all widgets below 
	You do not need to use widgets if you dont want to. Simply remove 
	the <section></section> and you can use wells or panels instead 
-->

<!-- widget grid -->
<section id="widget-grid" class="">

	<!-- row -->
	<div class="row">
		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
			
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget" id="wid-id-1">
				<!-- widget options:
					usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
					
					data-widget-colorbutton="false"	
					data-widget-editbutton="false"
					data-widget-togglebutton="false"
					data-widget-deletebutton="false"
					data-widget-fullscreenbutton="false"
					data-widget-custombutton="false"
					data-widget-collapsed="true" 
					data-widget-sortable="false"
					
				-->
				<header>
					<span class="widget-icon"> <i class="fa fa-globe"></i> </span>
					<h2>Overview</h2>				
					
				</header>

				<!-- widget div-->
				<div>
					
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->
						<input class="form-control" type="text">	
					</div>
					<!-- end widget edit box -->
					
					<!-- widget content -->
					<div class="widget-body">
													<?php
								require_once realpath(__DIR__ . '/../').'/config.php';

								$mysqli = new \mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);

								if ($mysqli->connect_errno) {
									$response['status'] = 'error';
									$response['message'] = "Database Connect Failed: ".$mysqli->connect_error;

									echo json_encode($response);

									exit();
								}


								$sql = "SELECT * FROM games;";

								if (!$result = $mysqli->query($sql)) {
									die ('There was an error running query[' . $mysqli->error . ']');
								}

								$num_games = $result->num_rows;

								$total_time = 0;
								while($row = $result->fetch_assoc()){
									$total_time += intval($row['duration']);
								}

								$sql1 = "SELECT * FROM goals;";

								if (!$result1 = $mysqli->query($sql1)) {
									die ('There was an error running query[' . $mysqli->error . ']');
								}

								$num_goals = $result1->num_rows;

								?>
						<div class="row">
							<div class="col-sm-6 col-md-4 col-lg-4" style="text-align: center;">
							<p><b>Games Played:</b> <br /><br /><span class="label label-primary" style="text-align: center;padding: 10px;font-size: 20pt;"><?php echo $num_games; ?></span></p>
							</div>
							<div class="col-sm-6 col-md-4 col-lg-4" style="text-align: center;">
							<p><b>Goals Scored:</b> <br /><br /><span class="label label-success" style="text-align: center;padding: 10px;font-size: 20pt;"><?php echo $num_goals; ?></span></p>
							</div>
							<div class="col-sm-6 col-md-4 col-lg-4" style="text-align: center;">
							<p><b>Time Well Spent:</b> <br /><br /><span class="label label-danger" style="text-align: center;padding: 10px;font-size: 20pt;"><?php echo gmdate("H:i:s", $total_time) ?></span></p>
							</div>
						</div>
					</div>
					<!-- end widget content -->
					
				</div>
				<!-- end widget div -->
				
			</div>
			<!-- end widget -->

		</article>
		<!-- WIDGET END -->
		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
			
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget" id="wid-id-0">
				<!-- widget options:
					usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
					
					data-widget-colorbutton="false"	
					data-widget-editbutton="false"
					data-widget-togglebutton="false"
					data-widget-deletebutton="false"
					data-widget-fullscreenbutton="false"
					data-widget-custombutton="false"
					data-widget-collapsed="true" 
					data-widget-sortable="false"
					
				-->
				<header>
					<span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
					<h2>Leaderboard</h2>				
					
				</header>

				<!-- widget div-->
				<div>
					
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->
						<input class="form-control" type="text">	
					</div>
					<!-- end widget edit box -->
					
					<!-- widget content -->
					<div class="widget-body">
						<table id="leaderboard_dt" class="table table-striped table-bordered table-hover" width="100%">
							<thead>			                
								<tr>
									<th data-hide="phone"> <i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i>Name</th>
									<th data-class="expand"><i class="fa fa-fw fa-gamepad text-muted hidden-md hidden-sm hidden-xs"></i> Games Played</th>
									<th data-class="expand"><i class="fa fa-fw fa-trophy text-muted hidden-md hidden-sm hidden-xs"></i> Wins</th>
									<th data-class="expand"><i class="fa fa-fw fa-times text-muted hidden-md hidden-sm hidden-xs"></i> Losses</th>
									<th data-class="expand"><i class="fa fa-fw fa-soccer-ball-o text-muted hidden-md hidden-sm hidden-xs"></i> Goals For</th>
									<th data-class="expand"><i class="fa fa-fw fa-trophy text-muted hidden-md hidden-sm hidden-xs"></i> Goals Against</th>
								</tr>
							</thead>
							<tbody>
								<?php
								require_once realpath(__DIR__ . '/../').'/config.php';

								$mysqli = new \mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);

								if ($mysqli->connect_errno) {
									$response['status'] = 'error';
									$response['message'] = "Database Connect Failed: ".$mysqli->connect_error;

									echo json_encode($response);

									exit();
								}


								$sql = "SELECT * from players ORDER BY wins";

								if (!$result = $mysqli->query($sql)) {
									die ('There was an error running query[' . $mysqli->error . ']');
								}

								?>
								<?php while ($row = $result->fetch_assoc()): ?>
									<?php
									$player_id = $row["id"];
									$getGoals = "SELECT * FROM goals WHERE scoring_player_id = {$player_id} OR defending_player_id = {$player_id}";

									if (!$result2 = $mysqli->query($getGoals)) {
										die ('There was an error running query[' . $mysqli->error . ']');
									}

									$sumGoalsFor = 0;
									$sumGoalsAgainst = 0;

									while ($row2 = $result2->fetch_assoc()) {
										if($row2['scoring_player_id'] == $row['id'])
										{
											$sumGoalsFor++;
										}
										elseif($row2['defending_player_id'] == $row['id'])
										{
											$sumGoalsAgainst++;
										}
									}

									?>
									<tr>
										<td><?php echo ucfirst($row["slack_user_name"]); ?></td>
										<td><?php echo $row["games_played"]; ?></td>
										<td><?php echo $row["wins"]; ?></td>
										<td><?php echo $row["losses"]; ?></td>
										<td><?php echo $sumGoalsFor; ?></td>
										<td><?php echo $sumGoalsAgainst; ?></td>
									</tr>
								<?php endwhile; ?>

							</tbody>


						</table>
					</div>
					<!-- end widget content -->
					
				</div>
				<!-- end widget div -->
				
			</div>
			<!-- end widget -->

		</article>
		<!-- WIDGET END -->
		
	</div>

	<!-- end row -->

	<!-- row -->

	<div class="row">

		<!-- a blank row to get started -->
		<div class="col-sm-12">
			<!-- your contents here -->
		</div>

	</div>

	<!-- end row -->

</section>
<!-- end widget grid -->

<script type="text/javascript">
	pageSetUp();
	
	var pagefunction = function() {
		// clears the variable if left blank

		/* BASIC ;*/
		var responsiveHelper_dt_basic = undefined;
		var responsiveHelper_datatable_fixed_column = undefined;
		var responsiveHelper_datatable_col_reorder = undefined;
		var responsiveHelper_datatable_tabletools = undefined;

		var breakpointDefinition = {
			tablet : 1024,
			phone : 480
		};

		$('#leaderboard_dt').dataTable({
			"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
			"t"+
			"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
			"oLanguage": {
				"sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
			},
			"order": [[ 2, "desc" ]],
			"autoWidth" : true,
			"preDrawCallback" : function() {
					// Initialize the responsive datatables helper once.
					if (!responsiveHelper_dt_basic) {
						responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#leaderboard_dt'), breakpointDefinition);
					}
				},
				"rowCallback" : function(nRow) {
					responsiveHelper_dt_basic.createExpandIcon(nRow);
				},
				"drawCallback" : function(oSettings) {
					responsiveHelper_dt_basic.respond();
				}
			});

		/* END BASIC */

	}
	
	// end pagefunction

	// destroy generated instances 
	// pagedestroy is called automatically before loading a new page
	// only usable in AJAX version!

	var pagedestroy = function(){
		
		/*
		Example below:

		$("#calednar").fullCalendar( 'destroy' );
		if (debugState){
			root.console.log("✔ Calendar destroyed");
		} 

		For common instances, such as Jarviswidgets, Google maps, and Datatables, are automatically destroyed through the app.js loadURL mechanic

		*/
	}


	// load related plugins
	
	loadScript("assets/js/plugin/datatables/jquery.dataTables.min.js", function(){
		loadScript("assets/js/plugin/datatables/dataTables.colVis.min.js", function(){
			loadScript("assets/js/plugin/datatables/dataTables.tableTools.min.js", function(){
				loadScript("assets/js/plugin/datatables/dataTables.bootstrap.min.js", function(){
					loadScript("assets/js/plugin/datatable-responsive/datatables.responsive.min.js", pagefunction)
				});
			});
		});
	});
	
</script>
