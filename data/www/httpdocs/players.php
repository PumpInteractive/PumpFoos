<?php
require_once realpath(__DIR__ . '/../').'/httpdocs/database.php';
$database = new Database();
?>
<!-- Bread crumb is created dynamically -->
<!-- row -->
<div class="row">

	<!-- col -->
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">

			<!-- PAGE HEADER -->
			<i class="fa-fw fa fa-user"></i>
			Statistics
			<span>>
				Players
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
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

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
					<span class="widget-icon"> <i class="fa fa-user"></i> </span>
					<h2>Active </h2>

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
					<div class="widget-body" style="text-align: center;">

						<div class="row">
							<?php

							$sql = "SELECT * from players WHERE archived!=1";

							$result = $database->sqlQuery($sql);

							?>
							<?php while ($row = $result->fetch_assoc()): ?>
								<?php $player_id = $row["id"];
								$getWins = "SELECT games.id,games.winning_team,players.slack_user_name FROM pumpfoos.games JOIN pumpfoos.games_players ON pumpfoos.games.id=pumpfoos.games_players.game_id JOIN pumpfoos.players ON pumpfoos.players.id=pumpfoos.games_players.player_id WHERE games.winning_team=games_players.team AND players.id={$player_id}";
								$getLosses = "SELECT games.id,games.winning_team,players.slack_user_name FROM pumpfoos.games JOIN pumpfoos.games_players ON pumpfoos.games.id=pumpfoos.games_players.game_id JOIN pumpfoos.players ON pumpfoos.players.id=pumpfoos.games_players.player_id WHERE games.losing_team=games_players.team AND players.id={$player_id}";

								$result3 = $database->sqlQuery($getWins);
								$result4 = $database->sqlQuery($getLosses);

								$numWins = $result3->num_rows;
								$numLosses = $result4->num_rows; ?>
								<div class="col-sm-6 col-md-4 col-lg-3" style="padding: 10px;">
									<div class="row">
										<img src="<?php echo $row['slack_profile_pic_url'];?>" alt="user_profile" />
										<p>
											<?php

											echo '<b>Name:</b> '. ucfirst($row["slack_user_name"]).'<br />';
											echo '<b>Games Played:</b> '.($numWins+$numLosses).'<br />';
											echo '<b>Wins:</b> '.$numWins.'<br />';
											echo '<b>Losses:</b> '.$numLosses;
											?>
										</p>
									</div>
									<div class="row" style="height: 50px;">
										<ul class="demo-btns">
										<?php $getAchievements = "SELECT count(id) as count, name FROM trophies_players JOIN trophies ON trophies.id=trophies_players.trophy_id WHERE trophies_players.player_id={$player_id} GROUP BY name";
											$result5 = $database->sqlQuery($getAchievements);
											if($result5->num_rows > 0){
												foreach($result5 as $achievement)
												{
													echo '<li style="padding: 5px;"><a href="javascript:void(0);" rel="tooltip" data-placement="top" data-original-title="'. $achievement['name'] . '(x '. $achievement['count'].')"><i class="fa fa-trophy"></i></a></li>';
												}
											}
											?>
										</ul>
										<p data-player-id="<?= $player_id ?>" style="cursor:pointer;" class="archive"><a>Archive</a></p>
									</div>
								</div>
							<?php endwhile; ?>
						</div>
					</div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget" id="wid-id-4">
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
					<span class="widget-icon"> <i class="fa fa-user"></i> </span>
					<h2>Archived Players</h2>

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
					<div class="widget-body" style="text-align: center;">

						<div class="row">
							<?php

							$sql = "SELECT * from players WHERE archived=1";

							$result = $database->sqlQuery($sql);

							?>
							<?php while ($row = $result->fetch_assoc()): ?>
								<?php $player_id = $row["id"];
								$getWins = "SELECT games.id,games.winning_team,players.slack_user_name FROM pumpfoos.games JOIN pumpfoos.games_players ON pumpfoos.games.id=pumpfoos.games_players.game_id JOIN pumpfoos.players ON pumpfoos.players.id=pumpfoos.games_players.player_id WHERE games.winning_team=games_players.team AND players.id={$player_id}";
								$getLosses = "SELECT games.id,games.winning_team,players.slack_user_name FROM pumpfoos.games JOIN pumpfoos.games_players ON pumpfoos.games.id=pumpfoos.games_players.game_id JOIN pumpfoos.players ON pumpfoos.players.id=pumpfoos.games_players.player_id WHERE games.losing_team=games_players.team AND players.id={$player_id}";

								$result3 = $database->sqlQuery($getWins);
								$result4 = $database->sqlQuery($getLosses);

								$numWins = $result3->num_rows;
								$numLosses = $result4->num_rows; ?>
								<div class="col-sm-6 col-md-4 col-lg-3" style="padding: 10px;">
									<div class="row">
										<img src="<?php echo $row['slack_profile_pic_url'];?>" alt="user_profile" />
										<p>
											<?php

											echo '<b>Name:</b> '. ucfirst($row["slack_user_name"]).'<br />';
											echo '<b>Games Played:</b> '.($numWins+$numLosses).'<br />';
											echo '<b>Wins:</b> '.$numWins.'<br />';
											echo '<b>Losses:</b> '.$numLosses;
											?>
										</p>
									</div>
									<div class="row" style="height: 50px;">
										<ul class="demo-btns">
										<?php $getAchievements = "SELECT count(id) as count, name FROM trophies_players JOIN trophies ON trophies.id=trophies_players.trophy_id WHERE trophies_players.player_id={$player_id} GROUP BY name";
											$result5 = $database->sqlQuery($getAchievements);
											if($result5->num_rows > 0){
												foreach($result5 as $achievement)
												{
													echo '<li style="padding: 5px;"><a href="javascript:void(0);" rel="tooltip" data-placement="top" data-original-title="'. $achievement['name'] . '(x '. $achievement['count'].')"><i class="fa fa-trophy"></i></a></li>';
												}
											}
											?>
										</ul>
										<p data-player-id="<?= $player_id ?>" style="cursor:pointer;" class="unarchive"><a>Unarchive</a></p>
									</div>
								</div>
							<?php endwhile; ?>
						</div>
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

</section>
<!-- end widget grid -->

<script type="text/javascript">
	$('.archive').each(function() {
		$(this).click(function () {
			$this = this;
			$.ajax({
				url: '/archive.php',
				method: 'POST',
				data: {
					action: 'archive',
					player_id: $(this).data('player-id')
				},
				success: function success(data) {
					if (data == 1) {
						$(window).trigger("hashchange"); // Reload the page.
					}
				},
				error: function error(data) {
					console.log(data);
				}
			});
		});
	});
	$('.unarchive').each(function() {
		$(this).click(function () {
			$this = this;
			$.ajax({
				url: '/archive.php',
				method: 'POST',
				data: {
					action: 'unarchive',
					player_id: $(this).data('player-id')
				},
				success: function success(data) {
					if (data == 1) {
						$(window).trigger("hashchange"); // Reload the page.
					}
				},
				error: function error(data) {
				}
			});
		});
	});
	/* DO NOT REMOVE : GLOBAL FUNCTIONS!
	 *
	 * pageSetUp(); WILL CALL THE FOLLOWING FUNCTIONS
	 *
	 * // activate tooltips
	 * $("[rel=tooltip]").tooltip();
	 *
	 * // activate popovers
	 * $("[rel=popover]").popover();
	 *
	 * // activate popovers with hover states
	 * $("[rel=popover-hover]").popover({ trigger: "hover" });
	 *
	 * // activate inline charts
	 * runAllCharts();
	 *
	 * // setup widgets
	 * setup_widgets_desktop();
	 *
	 * // run form elements
	 * runAllForms();
	 *
	 ********************************
	 *
	 * pageSetUp() is needed whenever you load a page.
	 * It initializes and checks for all basic elements of the page
	 * and makes rendering easier.
	 *
	 */

	 pageSetUp();

	/*
	 * ALL PAGE RELATED SCRIPTS CAN GO BELOW HERE
	 * eg alert("my home function");
	 *
	 * var pagefunction = function() {
	 *   ...
	 * }
	 * loadScript("js/plugin/_PLUGIN_NAME_.js", pagefunction);
	 *
	 */

	// PAGE RELATED SCRIPTS

	// pagefunction
	var pagefunction = function() {
		//console.log("cleared");

		/* // DOM Position key index //

			l - Length changing (dropdown)
			f - Filtering input (search)
			t - The Table! (datatable)
			i - Information (records)
			p - Pagination (paging)
			r - pRocessing
			< and > - div elements
			<"#id" and > - div with an id
			<"class" and > - div with a class
			<"#id.class" and > - div with an id and class

			Also see: http://legacy.datatables.net/usage/features
			*/

		}


	</script>
