				<!-------------------------------------- SENTIR JS ------------------------------------->
					<script src="js/jquery.min.js"></script>
					<script src="js/bootstrap.min.js"></script>
					<script src="js/retina.min.js"></script>
					<script src="js/jquery.nicescroll.js"></script>
					<script src="js/jquery.slimscroll.min.js"></script>
					<script src="js/jquery.backstretch.min.js"></script>
					
					<!-- PLUGINS -->
					<script src="js/skycons.js"></script>
					<script src="js/prettify.js"></script>
					<script src="js/jquery.magnific-popup.min.js"></script>
					<script src="js/owl.carousel.min.js"></script>
					<script src="js/chosen.jquery.min.js"></script>
					<script src="js/icheck.min.js"></script>
					<script src="js/bootstrap-datepicker.js"></script>
					<script src="js/bootstrap-timepicker.js"></script>
					<script src="js/jquery.mask.min.js"></script>
					<script src="js/bootstrapValidator.min.js"></script>
					<script src="js/jquery.dataTables.min.js"></script>
					<script src="js/bootstrap.datatable.js"></script>
					<script src="js/summernote.min.js"></script>
					<script src="js/markdown.js"></script>
					<script src="js/to-markdown.js"></script>
					<script src="js/bootstrap-markdown.js"></script>
					<script src="js/bootstrap-slider.js"></script>
					<script src="js/salvattore.min.js"></script>
					<script src="js/toastr.js"></script>
					
					<!-- FULL CALENDAR JS -->
					<script src="js/jquery-ui.custom.min.js"></script>
					<script src="js/fullcalendar.min.js"></script>
					<!-- <script src="js/full-calendar.js"></script> -->
					
					
					<!-- EASY PIE CHART JS -->
					<script src="js/easypiechart.min.js"></script>
					<script src="js/jquery.easypiechart.min.js"></script>
					
					<!-- KNOB JS -->
					<!--[if IE]>
					<script type="text/javascript" src="js/excanvas.js"></script>
					<![endif]-->
					<script src="js/jquery.knob.js"></script>
					<script src="js/knob.js"></script>

					<!-- FLOT CHART JS -->
					<script src="js/jquery.flot.js"></script>
					<script src="js/jquery.flot.tooltip.js"></script>
					<script src="js/jquery.flot.resize.js"></script>
					<script src="js/jquery.flot.selection.js"></script>
					<script src="js/jquery.flot.stack.js"></script>
					<script src="js/jquery.flot.time.js"></script>

					<!-- MORRIS JS -->
					<script src="js/raphael.min.js"></script>
					<script src="js/morris.min.js"></script>
					
					<!-- C3 JS -->
					<script src="js/d3.v3.min.js" charset="utf-8"></script>
					<script src="js/c3.min.js"></script>
					
					<!-- MAIN APPS JS -->
					<script src="js/apps.js"></script>
					<!-- <script src="js/toastr-demo.js"></script> -->
					<script src="js/demo-panel.js"></script>
				<!--------------------------------------//SENTIR JS ------------------------------------->
				
				<!----------------------------------- Fancy Box Lightbox -------------------------------->
					<!-- Add mousewheel plugin (this is optional) -->
					<script type="text/javascript" src="fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
					<!-- Add fancyBox main JS and CSS files -->
					<script type="text/javascript" src="fancybox/source/jquery.fancybox.js?v=2.1.0"></script>
					<link rel="stylesheet" type="text/css" href="fancybox/source/jquery.fancybox.css?v=2.1.0" media="screen">
					<!-- Add Button helper (this is optional) -->
					<link rel="stylesheet" type="text/css" href="fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.3">
					<script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.3"></script>
					<!-- Add Thumbnail helper (this is optional) -->
					<link rel="stylesheet" type="text/css" href="fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.6">
					<script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.6"></script>
					
					<script type="text/javascript">
						$(document).ready(function() {
							$('.fancybox').fancybox();
						});
						
						$(document).ready(function() {
							$('.fancybox2').fancybox({
								'autoSize'   : false,
								'autoResize' : false,
								'autoWidth'  : false,
								'width'      : 400,
								'autoHeight' : false,
								'minHeight'  : 100
							});
						});

						$(document).ready(function() {
							$('.fancybox3').fancybox({
								'autoSize'   : false,
								'autoResize' : false,
								'autoWidth'  : false,
								'width'      : 500,
								'autoHeight' : false,
								'minHeight'  : 750
							});
						});

						$(document).ready(function() {
							$('.fancybox4').fancybox({
								'autoSize'   : false,
								'autoResize' : false,
								'autoWidth'  : false,
								'width'      : 1050,
								'autoHeight' : false,
								'minHeight'  : 690,
							});
						});

						$(document).ready(function() {
							$('.fancybox5').fancybox({
								'autoSize'   : false,
								'autoResize' : false,
								'autoWidth'  : false,
								'width'      : 300,
								'autoHeight' : false,
								'maxHeight'  : 100
							});
						});

						$(document).ready(function() {
							$('.fancybox6').fancybox({
								'autoSize'   : true,
								'autoResize' : true,
								'autoWidth'  : false,
								'width'      : 640,
								'autoHeight' : true,
								'minHeight'  : 660
							});
						});

						$(document).on("change", "#inwardissue", function(e) {
							var tag = $('#inwardissue').val();
							if($('#inwardissue').selected!="")
							{
								$.fancybox({
									'width': 800,
									'height': 700,
									'type': 'iframe',
									'href': 'LBF_inwardissues.php?issue=' + tag
								})
							}
						});


						// $(window).on('load', function() {
						// 	console.log("soham is great!!!!", document)
						// 	setTimeout(function() {
						// 		console.log(document)
						// 		$.fancybox.close();					
						// 		console.log(document)
						// 	}, 8000);  
						// });


						// 	$(window).on('load', function() {
						// 	console.log("Page fully loaded!");
						// 	// $('.fancybox').fancybox();
						// 	$('#closeModal').on('click', function() {
						// 		console.log("Close button clicked!", document);
						// 		$.fancybox.close();
						// 		console.log("Close button clicked!25");
						// 	});
						// });



					</script>
					
					<style type="text/css">
						.fancybox-custom .fancybox-skin
						{
							box-shadow: 0 0 50px #222;
						}
					</style>
				<!--------------------------------- //Fancy Box Lightbox -------------------------------->
				
				<script type="text/javascript" src="js/aws.js"></script>

				<!-------------------------------- AUTOCOMPLETE SCRIPT --------------------------------->
					<link rel="stylesheet" href="autocomplete/jquery-ui.css">
					<!-- <script src="autocomplete/jquery-1.10.2.js"></script> -->
					<script src="autocomplete/jquery-ui.js"></script>
					<script>
					  $(function() {
						$( "#skills" ).autocomplete({
						  source: 'search.php'
						});
					  });
					</script>
					<script>
						$(function(){
							$("#oem").autocomplete({
							source: 'enlist.php?enlist=oems'
							});
						});
					</script>
					<script>
						$(function(){
							$("#tier").autocomplete({
							source: 'enlist.php?enlist=tiers'
							});
						});
					</script>
					<script>
						$(function(){
							$("#toolmaker").autocomplete({
							source: 'enlist.php?enlist=toolmakers'
							});
						});
					</script>
					<script>
						$(function(){
							$("#tool").autocomplete({
							source: 'enlist.php?enlist=tools'
							});
						});
					</script>
					<script>
						$(function(){
							$("#texturecode").autocomplete({
							source: 'enlist.php?enlist=texturecodes'
							});
						});
					</script>
				<!-------------------------------//AUTOCOMPLETE SCRIPT --------------------------------->

				<!----------------------------------- IMAGE PREVIEW ------------------------------------>
					<script type="text/javascript">
						document.getElementById("files").onchange = function ()
						{
							var reader = new FileReader();
							reader.onload = function (e) {
								document.getElementById("image").src = e.target.result;
							};
							reader.readAsDataURL(this.files[0]);
						};
					</script>
				<!----------------------------------//IMAGE PREVIEW ------------------------------------>

				<!------------------------------------- DATETIME PICKER -------------------------------->
					<!-- <script src="datetimepicker/jquery.js"></script>
					<script src="datetimepicker/jquery.datetimepicker.js"></script>
					<script>
						$('#datetimepicker10').datetimepicker({
							step:15,
							inline:true
						});
						$('#datetimepicker_mask').datetimepicker({
							mask:'9999/19/39 29:59'
						});
						$('#datetimepicker').datetimepicker();
						$('#datetimepicker').datetimepicker({step:15});
						$('#datetimepicker1').datetimepicker({
							datepicker:false,
							format:'H:i',
							step:5
						});
						$('#datetimepicker2').datetimepicker({
							yearOffset:222,
							lang:'ch',
							timepicker:false,
							format:'d/m/Y',
							formatDate:'Y/m/d',
							minDate:'-1970/01/02', // yesterday is minimum date
							maxDate:'+1970/01/02' // and tommorow is maximum date calendar
						});
						$('#datetimepicker3').datetimepicker({
							inline:true
						});
						$('#datetimepicker4').datetimepicker();
						$('#open').click(function(){
							$('#datetimepicker4').datetimepicker('show');
						});
						$('#close').click(function(){
							$('#datetimepicker4').datetimepicker('hide');
						});
						$('#reset').click(function(){
							$('#datetimepicker4').datetimepicker('reset');
						});
						$('#datetimepicker5').datetimepicker({
							datepicker:false,
							allowTimes:['12:00','13:00','15:00','17:00','17:05','17:20','19:00','20:00']
						});
						$('#datetimepicker6').datetimepicker();
						$('#destroy').click(function(){
							if( $('#datetimepicker6').data('xdsoft_datetimepicker') ){
								$('#datetimepicker6').datetimepicker('destroy');
								this.value = 'create';
							}else{
								$('#datetimepicker6').datetimepicker();
								this.value = 'destroy';
							}
						});
						var logic = function( currentDateTime ){
							if( currentDateTime.getDay()==6 ){
								this.setOptions({
									minTime:'11:00'
								});
							}else
								this.setOptions({
									minTime:'8:00'
								});
						};
						$('#datetimepicker7').datetimepicker({
							onChangeDateTime:logic,
							onShow:logic
						});
						$('#datetimepicker8').datetimepicker({
							onGenerate:function( ct ){
								$(this).find('.xdsoft_date')
									.toggleClass('xdsoft_disabled');
							},
							minDate:'-1970/01/2',
							maxDate:'+1970/01/2',
							timepicker:false
						});
						$('#datetimepicker9').datetimepicker({
							onGenerate:function( ct ){
								$(this).find('.xdsoft_date.xdsoft_weekend')
									.addClass('xdsoft_disabled');
							},
							weekends:['01.01.2014','02.01.2014','03.01.2014','04.01.2014','05.01.2014','06.01.2014'],
							timepicker:false
						});
					</script> -->
				<!------------------------------------//DATETIME PICKER -------------------------------->

				<!-------------------------------------- CALENDAR -------------------------------------->
					<script type="text/javascript">
						$(document).ready(function() {
						/* initialize the external events
							 -----------------------------------------------------------------*/

							$('#external-events div.external-event').each(function() {

								// create an Event Object (https://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
								// it doesn't need to have a start or end
								var eventObject = {
									title: $.trim($(this).text()) // use the element's text as the event title
								};

								// store the Event Object in the DOM element so we can get to it later
								$(this).data('eventObject', eventObject);

								// make the event draggable using jQuery UI
								$(this).draggable({
									zIndex: 999,
									revert: true,      // will cause the event to go back to its
									revertDuration: 0  //  original position after the drag
								});

							});


							/* initialize the calendar
							 -----------------------------------------------------------------*/

							var date = new Date();
							var d = date.getDate();
							var m = date.getMonth();
							var y = date.getFullYear();

							$('#calendar').fullCalendar({
								header: {
									left: 'prev,next today',
									center: 'title',
									right: 'month,basicWeek,basicDay'
								},
								editable: true,
								droppable: true, // this allows things to be dropped onto the calendar !!!
								drop: function(date, allDay) { // this function is called when something is dropped

									// retrieve the dropped element's stored Event Object
									var originalEventObject = $(this).data('eventObject');

									// we need to copy it, so that multiple events don't have a reference to the same object
									var copiedEventObject = $.extend({}, originalEventObject);

									// assign it the date that was reported
									copiedEventObject.start = date;
									copiedEventObject.allDay = allDay;

									// render the event on the calendar
									// the last `true` argument determines if the event "sticks" (https://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
									$('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

									// is the "remove after drop" checkbox checked?
									if ($('#drop-remove').is(':checked')) {
										// if so, remove the element from the "Draggable Events" list
										$(this).remove();
									}

								},
								events: [
									{
										title: '1 : IN',
										start: new Date(y, m, 1),
										end: new Date(y, m, 1),
										className:'fancybox2 fancybox.iframe',
										url: 'LBF_editschedule.php',
										startEditable : false
									},
									{
										title: '1 : MK',
										start: new Date(y, m, 2),
										end: new Date(y, m, 2),
										className:'fancybox2 fancybox.iframe',
										url: 'LBF_editschedule.php',
										startEditable : false
									},
									{
										title: '1 : IP',
										start: new Date(y, m, 3),
										end: new Date(y, m, 3),
										className:'fancybox2 fancybox.iframe',
										url: 'LBF_editschedule.php',
										startEditable : false
									},
									{
										title: '1 : ET',
										start: new Date(y, m, 4),
										end: new Date(y, m, 4),
										className:'fancybox2 fancybox.iframe',
										url: 'LBF_editschedule.php',
										startEditable : false
									},
									{
										title: '1:INS',
										start: new Date(y, m, 5),
										end: new Date(y, m, 5),
										className:'fancybox2 fancybox.iframe',
										url: 'LBF_editschedule.php',
										startEditable : false
									},
									{
										title: '2:IN',
										start: new Date(y, m, 5),
										end: new Date(y, m, 5),
										className:'fancybox2 fancybox.iframe',
										url: 'LBF_editschedule.php',
										startEditable : false
									},
									{
										title: '2:MK',
										start: new Date(y, m, 6),
										end: new Date(y, m, 7),
										className:'fancybox2 fancybox.iframe',
										url: 'LBF_editschedule.php',
										startEditable : false
									},
									{
										title: '2:RT',
										start: new Date(y, m, 8),
										end: new Date(y, m, 8),
										className:'fancybox2 fancybox.iframe',
										url: 'LBF_editschedule.php',
										startEditable : false
									},
									{
										title: '2:ET',
										start: new Date(y, m, 9),
										end: new Date(y, m, 9),
										className:'fancybox2 fancybox.iframe',
										url: 'LBF_editschedule.php',
										startEditable : false
									},
									{
										title: '2:RFD',
										start: new Date(y, m, 10),
										end: new Date(y, m, 10),
										className:'fancybox2 fancybox.iframe',
										url: 'LBF_editschedule.php',
										startEditable : false
									}
								]
							});
						});
					</script>
				<!-------------------------------------//CALENDAR -------------------------------------->
				<!-------------------------------------- OTHER JS -------------------------------------->
					<script type="text/javascript">
						function select_urn(obj)
						{
							if(obj.value == "NW" || obj.value == "RT")
							{
								document.getElementById('urn').disabled = 1;
								document.getElementById('urn').value = "";
							}
							else
							{
								document.getElementById('urn').disabled = 0;
							}
						}
					</script>
				<!-------------------------------------//OTHER JS -------------------------------------->

				<!-------------------------------- DRAGAGBLE CHECKBOXES -------------------------------->
					<script type="text/javascript">

					$('.cb_cb').mouseover(function(){
							var p = $(this).attr('roomtype');
							console.log(p);
							$('table tbody').dragcheck({
							container: 'tr', // Using the tr as a container
							onSelect: function(obj, state) {
									obj.prop('checked', state);		
									display_checked_slots(p);
								}
							});
						});	
						function display_checked_slots(p)
						{
							console.log(p);
							a = p;
							var flag	= 0;
							var inhtm	= "";

							for(x=1; x<=48; x++)
							{
								if(document.getElementById("cb"+a+"_"+x).checked && flag==0)
								{
									inhtm += "<div>";
									if(x%2)
										inhtm += ((x-1)/2)+":00-";
									else
										inhtm += ((x-2)/2)+":30-";

									flag = 1;
								}

								if(x<48)
								{
									if(flag == 1 && !document.getElementById("cb"+a+"_"+(x+1)).checked)
									{
										if((x+1)%2)
											inhtm += (x/2)+":00";
										else
											inhtm += ((x-1)/2)+":30";
										inhtm += "</div>";

										flag = 0;
									}
								}
								
								if(flag == 1 && x==48)
									inhtm += "00:00</div>";
							}
							document.getElementById("slots_list"+"_"+a).innerHTML = inhtm;
						}
					</script>
					
					<script src="dragcheck.js"></script>
					<script>
						$(window).load(function(){
							
						});
					</script>
				<!-------------------------------// DRAGAGBLE CHECKBOXES ------------------------------->