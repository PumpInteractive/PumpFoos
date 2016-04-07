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
					<h2>Players </h2>				
					
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

								if($result->num_rows % 2 == 0)
								{
									$twoRows = true;
								} 

								$colsLG = 12/($result->num_rows / 2);
								$colsMD = 12/($result->num_rows / 4);

								?>

								<?php if($twoRows): ?>
									<div class="row">
									<?php endif; ?>
									<?php while ($row = $result->fetch_assoc()): ?>
										<div class="col-sm-<?php echo $colsMD; ?> col-md-<?php echo $colsMD; ?> col-lg-<?php echo $colsLG; ?>" style="padding: 15px;">
											<img src="<?php echo $row['slack_profile_pic_url'];?>" alt="user_profile" />
											<p>
												<?php 

												echo '<b>Name:</b> '. ucfirst($row["slack_user_name"]).'<br />';
												echo '<b>Games Played:</b> '. $row["games_played"].'<br />';
												echo '<b>Wins:</b> '.$row["wins"].'<br />';
												echo '<b>Losses:</b> '.$row["losses"].'<br />';
												?>
											</p>
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
