<!-- Bread crumb is created dynamically -->
<?php
require_once realpath(__DIR__ . '/../').'/httpdocs/database.php';
$database = new Database();
?>
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

	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			

			<div class="jarviswidget" id="wid-id-2">

				<header>
					<span class="widget-icon"> <i class="fa fa-bolt"></i> </span>
					<h2>Live Preview</h2>				
					
				</header>

				<div>
					
					<div class="jarviswidget-editbox">

						<input class="form-control" type="text">	
					</div>



					<div class="widget-body">
						<h2>Live Game Preview</h2>
						<div class="row">
							<div class="col-sm-6 col-md-6 col-lg-6">
								<div id="gameSummary">
									<p id="gameInfo">No game in progress..</p>
									<p id="goalInfo"></p>
								</div>
							</div>
							<div class="col-sm-6 col-md-6 col-lg-6" style="text-align: center;">
								<a class="btn btn-lg btn-primary" href="/">Play a Game!</a>
							</div>

						</div>
					</div>
				</div>

				
			</div>


		</article>

	</div>

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
						$sql = "SELECT * FROM games;";

						$result = $database->sqlQuery($sql);

						$num_games = $result->num_rows;

						$total_time = 0;
						$yellowWins = 0;
						$blackWins = 0;

						while($row = $result->fetch_assoc()){
							$total_time += intval($row['duration']);
							if($row['winning_team'] == 1)
							{
								$yellowWins++;
							}
							else
							{
								$blackWins++;
							}
						}

						$sql1 = "SELECT * FROM goals;";

						$result1 = $database->sqlQuery($sql1);

						$num_goals = $result1->num_rows;

						$sql2 = "SELECT count(*) AS num from games_players WHERE team=team GROUP BY game_id";

						$result2 = $database->sqlQuery($sql2);

						$numSingleGames = 0;
						$numDoublesGames = 0;
						while($row1 = $result2->fetch_assoc()){
							$numPlayers = $row1['num'];
							if($numPlayers == 4)
							{
								$numDoublesGames++;
							}
							elseif($numPlayers == 2)
							{
								$numSingleGames++;
							}
						}

						?>
						<div class="row">
							<div class="col-sm-4 col-md-4 col-lg-4" style="text-align: center;">
								<p><b>Games Played:</b> <br /><br /><span class="label label-primary" style="text-align: center;padding: 10px;font-size: 20pt;"><?php echo $num_games; ?></span></p>
							</div>
							<div class="col-sm-4 col-md-4 col-lg-4" style="text-align: center;">
								<p><b>Goals Scored:</b> <br /><br /><span class="label label-success" style="text-align: center;padding: 10px;font-size: 20pt;"><?php echo $num_goals; ?></span></p>
							</div>
							<div class="col-sm-4 col-md-4 col-lg-4" style="text-align: center;">
								<p><b>Time Well Spent:</b> <br /><br /><span class="label label-danger" style="text-align: center;padding: 10px;font-size: 20pt;"><?php echo gmdate("H:i:s", $total_time) ?></span></p>
							</div>
						</div>
						<hr class="simple">
						<div class="row">
							<div class="col-sm-12 col-md-12 col-lg-12">
								<p><span style="color: #e6b800;font-weight:bold;">Yellow</span> vs. <span style="font-weight: bold;">Black</span></p>
								<?php
								$yellowPercent = $yellowWins/($num_games > 0 ? $num_games : 1);
								$blackPercent = $blackWins/($num_games > 0 ? $num_games : 1);
								?>
								<div class="progress progress-lg progress-striped active">
									<?php if($yellowPercent > $blackPercent)
									{
										echo '<div class="progress-bar bg-color-darken" style="left: ' . round(($blackPercent*100),0) . '%;width:'. round(($yellowPercent*100),0) . '%;">'. round(($yellowPercent*100),0) . '%</div>';
										echo '<div class="progress-bar bg-color-yellow" style="width: ' . round(($blackPercent*100),0) . '%;">'. round(($blackPercent*100),0) . '%</div>';
									}
									else{
										echo '<div class="progress-bar bg-color-yellow" style="left: ' . round(($yellowPercent*100),0) . '%;width:'. round(($blackPercent*100),0) . '%;">'. round(($blackPercent*100),0) . '%</div>';
										echo '<div class="progress-bar bg-color-darken" style="width: '. round(($yellowPercent*100),0) . '%;">'. round(($yellowPercent*100),0) . '%</div>';
									} ?>

									
								</div>
							</div>
							<hr class="simple">
							<div class="col-sm-12 col-md-12 col-lg-12">
								<div class="col-sm-6 col-md-6 col-lg-6" style="text-align: center;">
									<p><b>Avg game length:</b> <br /><br /><span class="label label-success" style="text-align: center;padding: 10px;font-size: 20pt;"><?php echo gmdate("H:i:s", $total_time/($num_games > 0 ? $num_games : 1)) ?></span></p>
								</div>
								<div class="col-sm-6 col-md-6 col-lg-6" style="text-align: center;">
									<p><b>Singles Games vs. Doubles Games</b><br /><br /><span class="label label-primary" style="text-align: center;padding: 10px;font-size: 20pt;"><?php echo $numSingleGames; ?></span> : <span class="label label-danger" style="text-align: center;padding: 10px;font-size: 20pt;"><?php echo $numDoublesGames; ?></span></p>
								</div>
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
									<th data-class="expand"><i class="fa fa-fw fa-gamepad text-muted hidden-md hidden-sm hidden-xs"></i> GP</th>
									<th data-class="expand"><i class="fa fa-fw fa-trophy text-muted hidden-md hidden-sm hidden-xs"></i> W</th>
									<th data-class="expand"><i class="fa fa-fw fa-times text-muted hidden-md hidden-sm hidden-xs"></i> L</th>
									<th data-class="expand"><i class="fa fa-fw fa-percent text-muted hidden-md hidden-sm hidden-xs"></i> Win</th>
									<th data-class="expand"><i class="fa fa-fw fa-soccer-ball-o text-muted hidden-md hidden-sm hidden-xs"></i> GF</th>
									<th data-class="expand"><i class="fa fa-fw fa-minus-circle text-muted hidden-md hidden-sm hidden-xs"></i> GA</th>
									<th data-class="expand"><i class="fa fa-fw fa-line-chart text-muted hidden-md hidden-sm hidden-xs"></i> +/- </th>
									<th data-class="expand"><i class="fa fa-fw fa-minus-circle text-muted hidden-md hidden-sm hidden-xs"></i> GPG</th>
									<th data-class="expand"><i class="fa fa-fw fa-minus-circle text-muted hidden-md hidden-sm hidden-xs"></i> GAA </th>
									<th data-class="expand"><i class="fa fa-fw fa-soccer-ball-o text-muted hidden-md hidden-sm hidden-xs"></i> AG </th>
									<th data-class="expand"><i class="fa fa-fw fa-soccer-ball-o text-muted hidden-md hidden-sm hidden-xs"></i> DG </th>
								</tr>
							</thead>
							<tbody>
								<?php

								$sql = "SELECT * from players";

								$result = $database->sqlQuery($sql);

								?>
								<?php while ($row = $result->fetch_assoc()): ?>
									<?php
									$player_id = $row["id"];
									$getGoals = "SELECT * FROM goals WHERE scoring_player_id = {$player_id} OR defending_player_id = {$player_id}";

									$getWins = "SELECT games.id,games.winning_team,players.slack_user_name FROM pumpfoos.games JOIN pumpfoos.games_players ON pumpfoos.games.id=pumpfoos.games_players.game_id JOIN pumpfoos.players ON pumpfoos.players.id=pumpfoos.games_players.player_id WHERE games.winning_team=games_players.team AND players.id={$player_id}";
									$getLosses = "SELECT games.id,games.winning_team,players.slack_user_name FROM pumpfoos.games JOIN pumpfoos.games_players ON pumpfoos.games.id=pumpfoos.games_players.game_id JOIN pumpfoos.players ON pumpfoos.players.id=pumpfoos.games_players.player_id WHERE games.losing_team=games_players.team AND players.id={$player_id}";

									$result2 = $database->sqlQuery($getGoals);
									$result3 = $database->sqlQuery($getWins);
									$result4 = $database->sqlQuery($getLosses);

									$numWins = $result3->num_rows;
									$numLosses = $result4->num_rows;

									$sumGoalsForAttack = 0;
									$sumGoalsForDefense = 0;

									$sumGoalsFor = 0;
									$sumGoalsAgainst = 0;

									while ($row2 = $result2->fetch_assoc()) {
										if($row2['scoring_player_id'] == $row['id'])
										{
											if($row2['player_position'] == 'attack')
											{
												$sumGoalsForAttack++;
											}
											else {
												$sumGoalsForDefense++;
											}

											$sumGoalsFor++;
										}
										elseif($row2['defending_player_id'] == $row['id'])
										{
											$sumGoalsAgainst++;
										}
									}

									$difference = $sumGoalsFor - $sumGoalsAgainst;
									?>
									<tr>
										<td><?php echo ucfirst($row["slack_user_name"]); ?></td>
										<td><?php echo $numWins+$numLosses; ?></td>
										<td><?php echo $numWins; ?></td>
										<td><?php echo $numLosses; ?></td>
										<td><?php echo ($numWins+$numLosses > 0 ? round($numWins/($numWins+$numLosses),2)*100 : 0).'%'; ?></td>
										<td><?php echo $sumGoalsFor; ?></td>
										<td><?php echo $sumGoalsAgainst; ?></td>
										<td><?php echo ($difference > 0 ? "+".$difference : $difference); ?></td>
										<td><?php echo ($numWins+$numLosses > 0 ? round($sumGoalsFor/($numWins+$numLosses),2) : 0); ?></td>
										<td><?php echo ($numWins+$numLosses > 0 ? round($sumGoalsAgainst/($numWins+$numLosses),2) : 0); ?></td>
										<td><?php echo $sumGoalsForAttack; ?></td>
										<td><?php echo $sumGoalsForDefense; ?></td>
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
				// Fill all progress bars with animation
				$('.progress-bar').progressbar({
					display_text : 'fill'
				});
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
	
	loadScript("/assets/js/plugin/datatables/jquery.dataTables.min.js", function(){
		loadScript("/assets/js/plugin/datatables/dataTables.colVis.min.js", function(){
			loadScript("/assets/js/plugin/datatables/dataTables.tableTools.min.js", function(){
				loadScript("/assets/js/plugin/datatables/dataTables.bootstrap.min.js", function(){
					loadScript("/assets/js/plugin/datatable-responsive/datatables.responsive.min.js", pagefunction)
				});
			});
		});
	});
	
</script>
