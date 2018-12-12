<?php
// Function TO Input Data
add_shortcode('input-data-table','agri_input_data');
function agri_input_data(){
	global $wpdb;
	$current_user = wp_get_current_user();
	$role = $current_user->roles[0];
	$id=$current_user->ID;
	if ( !is_user_logged_in() ) {
		echo "<h1>You Are Not Logged In</h1>";
		echo "<a href='".get_site_url()."/login'"."> Go Back To Login Page</a>";
	}
	else{
	$crops = get_terms( 'crop', array(
									'orderby'    => 'name',
									'hide_empty' => 0,
								) );
	$ingredient = array(
		'post_type' => 'ingredient',
		'post_status' => 'publish',
		'posts_per_page' => -1
	); 
	$countries = get_terms( 'country', array(
									'orderby'    => 'name',
									'hide_empty' => 0,
								) );
	$manufacturers = get_terms( 'manufacturer', array(
		'orderby'    => 'name',
		'hide_empty' => 0,
	) );
	$activities = get_terms( 'activity', array(
		'orderby'    => 'name',
		'hide_empty' => 0,
	) );                 
	$the_query = new WP_Query( $ingredient ); ?>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
	<script>
	jQuery( document ).ready(function( $ ) {
		$('.add-ingredient').click(function(){
			$('.dynamic-element').first().clone().appendTo('.dynamic-stuff').show();
			attach_delete();
			});
		$('.add-another-crop').click(function(){
			$('.input-crop-fields').first().clone().appendTo('.dynamic-crop').show();
			attach_delete();
			});



	//Attach functionality to delete buttons
		function attach_delete(){
			$('.delete').off();
			$('.delete').click(function(){
			console.log("click");
			$(this).closest('.form-group').remove();
			});
		}
	});
	</script>
 <!-- End of fields-->
    <form method="post" class="agr-style agr-inptdata" action="../data-added">
    	<div class="row">
    	<div class="col-sm-12">		
    	<input type="text" class="form-control" name="input-product-name" placeholder="<?php _e('Enter Product name','agri');?>" required="">
    	</div>
	<div class="form-group dynamic-element" >
  <!-- Replace these fields -->
  <div class="col-sm-5">
    <select id="input-ingredient" name="input-ingredient[]" class="form-control">
    	<option value = "0"><?php _e('Choose Active Ingredient','agri');?></option>
    	<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
				<option value="<?php echo(get_the_ID()) ?>"><?php echo get_the_title() ?></option>
		<?php endwhile; ?>
     <?php ?>
    </select>
	</div>
  <div class="col-sm-5">		
    <input type="text" class="agr-comp" name="input-composition[]" placeholder="<?php _e('Enter Composition','agri');?>" required="">
   </div>
 
    <div class="col-sm-2 dlt-btn">
      <p class="delete">x</p>
    </div>
  </div>
  <div class="dynamic-stuff">
  </div>
  <!-- Dynamic Add fields --> 
<div class="col-sm-12 inpt-ingre"><a class="add-ingredient"><?php _e('Add Active Ingredient','agri');?></a>
  <!-- End dynamic fields -->
  <p><?php _e('if you cannot find an Active Ingredient in the list above, please contact  <a href="mailto:support@agrinomia.com">support@agrinomia.com</a>','agri');?></p>
</div>
  <!-- Countries Drop Down -->

  <div class="agr-wth">
  	<div class="col-md-6 col-pd">
  <label><?php _e('Please Select the country','agri');?></label>
  <select name="input-country" required="required">
  	<option><?php _e('Choose Country','agri');?></option>
  	<?php foreach ($countries as $key => $country): ?>
  		<option value="<?php echo ($country->term_id) ?>"><?php echo ($country->name); ?></option>
  	<?php endforeach; ?>
  </select>
	</div>
  <!-- Countries Drop down Ends here -->
  <!-- Manufacturer Drop Down -->
  <div class="col-md-6">
  <label><?php _e('Please Select Manufacturer','agri');?></label>
  <select name="input-manufacturer">
  	<option value="0"><?php _e('Choose Manufacturer','agri');?></option>
	<?php foreach ($manufacturers as $key => $manufacturer): ?>
		<option value="<?php echo ($manufacturer->term_id) ?>"><?php echo ($manufacturer->name); ?></option>
  	<?php endforeach; ?>
	</select>
	</div>
	</div>
  <!-- Manufacturer Drop Down Ends Here -->
  <div class="input-crop-fields">
  <div class="agr-wth">
  	<!-- Crop Drop Down -->
  	<div class="col-md-6 col-pd">
  	<label><?php _e('Please Select Crop','agri');?></label>
  <select name="input-crops[]">
  	<option value="0"><?php _e('Choose Crop','agri');?></option>
	<?php foreach ($crops as $key => $crop): ?>
		<option value="<?php echo ($crop->term_id) ?>"><?php echo ($crop->name); ?></option>
  	<?php endforeach; ?>
	</select>
	</div>
		<!-- Crop Drop Down Ends Here -->

  <!-- Activity Drop Down Starts Here -->
  <div class="col-md-6">
  <label><?php _e('Please Select Activity','agri');?></label>
  <select name="input-activity[]">
  	<option value="0"><?php _e('Choose Activity','agri');?></option>
	<?php foreach ($activities as $key => $activity): ?>
		<option value="<?php echo ($activity->term_id) ?>"><?php echo ($activity->name); ?></option>
  	<?php endforeach; ?>
	</select>
	</div>
	</div>
  <!-- Activity Drop Down Ends Here -->
  <div class="col-sm-12">
  <input class="inpt-enter" type="text" name="input-dosage[]" placeholder="<?php _e('Enter Dosage','agri');?>" required="">
  <input class="inpt-enter" type="text" name="input-method-app[]" placeholder="<?php _e('Enter Method Of Application','agri');?>" required="">
  <input class="inpt-enter" type="text" name="input-quarantine[]" placeholder="<?php _e('Enter Quarantine','agri');?>" required="">
 </div>
</div>
<div class="dynamic-crop">
</div>
<!-- Button to Add Crop Fields -->
<div class="col-sm-6">	
  <a class="add-another-crop"><?php _e('Add Crop','agri');?></a>
  <!-- Button to Add Crop Fields Ends Here -->
</div>
<div class="col-sm-6">
	<button type="submit" class="agr-btn" name="singlebutton"><?php _e('Submit For Approval','agri');?></button>
</div>	
</div>	
</form>	
	<?php
	}
}
// Function TO Input Data Ends Here

// Function To Add the Data in Data Base
add_shortcode('data-added','agri_datatable_add');
function agri_datatable_add(){
	global $wpdb;
	$current_user = wp_get_current_user();
	$product_name=$_POST['input-product-name'];
	$ingredient_ids= $_POST['input-ingredient'];
	$compostions=$_POST['input-composition'];
    $crops = $_POST['input-crops'];
	$countries = $_POST['input-country'];
	$manufacturers = $_POST['input-manufacturer'];
	$activities = $_POST['input-activity'];
	$dosages = $_POST['input-dosage'];
	$method_apps = $_POST['input-method-app'];
	$quarantines = $_POST['input-quarantine'];
	if($product_name!=''){
		if($current_user->ID!=0){
			$product_post = array(
					'post_title'    => $product_name,
					'post_type'     => 'product',
					'post_status'   => 'draft',
					'post_author'   => $current_user->ID,
					'post_category' => array( 8,39 )
				);
			$product_id = wp_insert_post( $product_post);
			wp_set_post_terms( $product_id, $countries, 'country', true );
			wp_set_post_terms( $product_id, $manufacturers, 'manufacturer', true );
			
			foreach ($ingredient_ids as $key => $ingredient_id) {
				if($ingredient_id != 0&& $compostions[$key] !=''){
					$wpdb->insert(
								$wpdb->prefix . 'product_ingredient',
								array(
									'product_id'        => $product_id,
									'ingredient_id'     => $ingredient_id,
									'content'           => $compostions[$key],
									'user_id'           => $current_user->ID,
									'status'            => 0,
									),
								array(
									'%d', '%d', '%s', '%d', '%d',
									)
								);

				}
			}
			foreach ($crops as $key => $crop) {
				$wpdb->insert(
								$wpdb->prefix . 'crop_data',
								array(
									'product_id'        => $product_id,
									'crop_id'			=> $crop,
									'activity_id'     	=> $activities[$key],
									'dosage'           	=> $dosages[$key],
									'method_app'		=> $method_apps[$key],
									'quarantine' 		=> $quarantines[$key],
									'user_id'           => $current_user->ID,
									'status'            => 0,
									),
								array(
									'%d', '%d', '%d', '%s', '%s', '%s','%d', '%d',
									)
								);	
			}
			echo "<h1>The Data Is Inserted And Waiting For Approval</h1>";
		}
		else{
			echo "<h1>You Are Not Logged In Please Login To Enter The Data</h1>";
			}
	}
}
// Function To Add the Data in Database Ends Here
/*Function to Show the DataTable*/
add_shortcode('product_data_tables','agri_show_data');
function agri_show_data(){
	global $wpdb;
	 ?>
	<!-- JQuery to Sort the Columns of the Table -->
	<script>
	jQuery( document ).ready(function( $ ) {
		$('.countries').select2({
		    placeholder: "<?php _e('Select Countries','agri');?>",
		    allowClear: true
		});
		var thIndex = 0,
    	curThIndex = null;
		$(function(){
		  $('table thead tr th').click(function(){
		  	console.log("filtered");
		    thIndex = $(this).index();
		    if(thIndex != curThIndex){
		      curThIndex = thIndex;
		      sorting = [];
		      tbodyHtml = null;
		      $('table tbody tr').each(function(){
		        sorting.push($(this).children('td').eq(curThIndex).html() + ', ' + $(this).index());
		      });
		      
		      sorting = sorting.sort();
		      sortIt();
		    }
		  });
		})

		function sortIt(){
		  for(var sortingIndex = 0; sortingIndex < sorting.length; sortingIndex++){
		  	rowId = parseInt(sorting[sortingIndex].split(', ')[1]);
		  	try{
		  	tbodyHtml = tbodyHtml + $('table tbody tr').eq(rowId)[0].outerHTML;
				}catch(e){
				   console.log("YO",e)
				}
		  }
		  $('table tbody').html(tbodyHtml);
		}
		$( ".category" ).change(function() {
		  $(".activity").attr('disabled','disabled');
		  var ajaxurl = '../wp-admin/admin-ajax.php';
		  var category = $('.category').val();
		  $('.ajax-load').show();
		  $.ajax({
					url: ajaxurl,
					type: 'post',
					data: {
						action   :'get_activities',
						category : category,
					},
					success: function (response) {
						var elem =$('.activity') ;
						elem.empty();
						var obj = jQuery.parseJSON(response);
						var list = '';
						$('.ajax-load').hide();
						if (obj!=null) {
						list+='<option value="">All Activities</option>';
						$.each( obj, function( key, value ) {
							$(".activity").removeAttr('disabled');
								if(value!=null){
							  list+='<option value="' + key +'">' + value + '</option>';
							}
							});
						}else{
							list+='<option value="">No Activities Found</option>';
						}
						elem.html(list);
						console.log(obj);
						
					}
				});
		});
	});
	</script>
	<!-- JQuery to Sort the Columns of the Table Ends Here -->
	<script>
		// Script For Data Table Pagination
		jQuery( document ).ready(function( $ ) {
		/*$(window).load(function() {
		 getPagination('#table-id');
		 function getPagination (table){
		 	$('.pagination').html('');						// reset pagination 
		  	var trnum = 0 ;									// reset tr counter 
		  	var maxRows = 10;			// get Max Rows from select option
		  	var totalRows = $(table+' tbody tr').length;		// numbers of rows 
			 $(table+' tr:gt(0)').each(function(){			// each TR in  table and not the header
			 	trnum++;									// Start Counter 
			 	if (trnum > maxRows ){						// if tr number gt maxRows
			 		
			 		$(this).hide();							// fade it out 
			 	}if (trnum <= maxRows ){$(this).show();}// else fade in Important in case if it ..
			 });											//  was fade out to fade it in 
			 if (totalRows > maxRows){						// if tr total rows gt max rows option
			 	var pagenum = Math.ceil(totalRows/maxRows);	// ceil total(rows/maxrows) to get ..  
			 												//	numbers of pages 
			 	for (var i = 1; i <= pagenum ;){			// for each page append pagination li 
			 	$('.pagination').append('<li data-page="'+i+'">\
								      <span>'+ i++ +'<span class="sr-only">(current)</span></span>\
								    </li>').show();
			 	}											// end for i 
			} 												// end if row count > max rows
			$('.pagination li:first-child').addClass('active'); // add active class to the first li 
			$('.pagination li').on('click',function(e){		// on click each page
        		e.preventDefault();
				var pageNum = $(this).attr('data-page');	// get it's number
				var trIndex = 0 ;							// reset tr counter
				$('.pagination li').removeClass('active');	// remove active class from all li 
				$(this).addClass('active');					// add active class to the clicked 
				 $(table+' tr:gt(0)').each(function(){		// each tr in table not the header
				 	trIndex++;								// tr index counter 
				 	// if tr index gt maxRows*pageNum or lt maxRows*pageNum-maxRows fade if out
				 	if (trIndex > (maxRows*pageNum) || trIndex <= ((maxRows*pageNum)-maxRows)){
				 		$(this).hide();		
				 	}else {$(this).show();} 				//else fade in 
				 }); 										// end of for each tr in table
			});	

		  $('#maxRows').on('change',function(){
		  	$('.pagination').html('');						// reset pagination 
		  	var trnum = 0 ;									// reset tr counter 
		  	var maxRows = parseInt($(this).val());			// get Max Rows from select option
		  	var totalRows = $(table+' tbody tr').length;		// numbers of rows 
			 $(table+' tr:gt(0)').each(function(){			// each TR in  table and not the header
			 	trnum++;									// Start Counter 
			 	if (trnum > maxRows ){						// if tr number gt maxRows
			 		
			 		$(this).hide();							// fade it out 
			 	}if (trnum <= maxRows ){$(this).show();}// else fade in Important in case if it ..
			 });											//  was fade out to fade it in 
			 if (totalRows > maxRows){						// if tr total rows gt max rows option
			 	var pagenum = Math.ceil(totalRows/maxRows);	// ceil total(rows/maxrows) to get ..  
			 												//	numbers of pages 
			 	for (var i = 1; i <= pagenum ;){			// for each page append pagination li 
			 	$('.pagination').append('<li data-page="'+i+'">\
								      <span>'+ i++ +'<span class="sr-only">(current)</span></span>\
								    </li>').show();
			 	}											// end for i 
			} 												// end if row count > max rows
			$('.pagination li:first-child').addClass('active'); // add active class to the first li 
			$('.pagination li').on('click',function(e){		// on click each page
        		e.preventDefault();
				var pageNum = $(this).attr('data-page');	// get it's number
				var trIndex = 0 ;							// reset tr counter
				$('.pagination li').removeClass('active');	// remove active class from all li 
				$(this).addClass('active');					// add active class to the clicked 
				 $(table+' tr:gt(0)').each(function(){		// each tr in table not the header
				 	trIndex++;								// tr index counter 
				 	// if tr index gt maxRows*pageNum or lt maxRows*pageNum-maxRows fade if out
				 	if (trIndex > (maxRows*pageNum) || trIndex <= ((maxRows*pageNum)-maxRows)){
				 		$(this).hide();		
				 	}else {$(this).show();} 				//else fade in 
				 }); 										// end of for each tr in table
			});										// end of on click pagination list
		  });
			}	
		});*/
		// Script For Data Table Pagination Ends Here
		// Script To Reset The Search
			 $('#reset').click(function(){
			  $('.category').prop('selectedIndex',0);
			  $('.manufacturer').prop('selectedIndex',0);
			  $('.crops').prop('selectedIndex',0);
			  $('.activity').prop('selectedIndex',0);
			  $('.countries').prop('selectedIndex',0);
			  $('.product-ingredient').prop('selectedIndex',0);
			 });
			 $('.product-ingredient').select2({
		    	placeholder: "Select Product Or ingredients",
		    	allowClear: true
			 });
			 // Script To Reset The Search Ends Here
	});

	</script>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
	<!-- <link rel="stylesheet" href="../css/tooltip.css"> -->
	<script  src="../js/tooltip.js"></script>
	<link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css'>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css"> 
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
	<!-- <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js'></script> -->
	<script src='https://cdnjs.cloudflare.com/ajax/libs/list.js/1.2.0/list.min.js'></script>
	<script src='https://cdnjs.cloudflare.com/ajax/libs/list.pagination.js/0.1.1/list.pagination.min.js'></script>
	<script type="text/javascript">
		jQuery(document).ready( function () {
    		// jQuery('.table').DataTable();
    		jQuery('.table').DataTable({
			  drawCallback: function() {
			    jQuery('[data-toggle="popover"]').popover();
			  }  
			});
			jQuery('body').on('click', function (e) {
		    jQuery('[data-toggle="popover"]').each(function () {
		        //the 'is' for buttons that trigger popups
		        //the 'has' for icons within a button that triggers a popup
		        if (!jQuery(this).is(e.target) && jQuery(this).has(e.target).length === 0 && jQuery('.popover').has(e.target).length === 0) {
		            jQuery(this).popover('hide');
		        }
		    });
		});
		} );

	</script>
	<!-- <script>
	jQuery(document).ready( function ($){
		var arr = $('.dataTables_info').html();
		var res = arr.split(" ");
		var val = parseInt(res[5]);
		for (var i = 3; i < val; i+=4) {
			$("#table-id tr:eq("+i+")").after("<tr><td colspan='10'><img src='../wp-content/plugins/agri/image/banner.gif'></td></tr>");
		}
	});
	</script> -->
	<!-- <script type="text/javascript">
		jQuery( document ).ready(function( $ ) {
			var pagingRows = 10;

			var paginationOptions = {
			    innerWindow: 1,
			    left: 0,
			    right: 0
			};
			var options = {
			  //valueNames: [ 'sortID', 'sortDesc', 'sortTotal' ],
			  page: pagingRows,
			  plugins: [ ListPagination(paginationOptions) ],
			};

			var tableList = new List('tableID', options);

			$('.jTablePageNext').on('click', function(){
			    var list = $('.pagination').find('li');
			    $.each(list, function(position, element){
			        if($(element).is('.active')){
			            $(list[position+1]).trigger('click');
			        }
			    })
			});
			$('.jTablePagePrev').on('click', function(){
			    var list = $('.pagination').find('li');
			    $.each(list, function(position, element){
			        if($(element).is('.active')){
			            $(list[position-1]).trigger('click');
			        }
			    })
			});
		});
	</script> -->
	<!-- JavaScript to Search The Table With Input Field Ends Here-->

	<!-- Fields On The Front Ends To Filter The Table -->
	

  <div id="accordion" class="panel-group">
    <div class="panel">
      <div class="panel-heading">
      	<h4 class="panel-title">
        <a href="#panelBodyOne" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion"><?php _e('Search By Product/Ingredient','sgc');?></a>
        </h4>
      </div>
      <div id="panelBodyOne" class="panel-collapse collapse in">
      <div class="panel-body">
            <!-- <input type="submit" name="search-table" value="<?php _e('Search Data','agri');?>" class="search-data search-data-btn">
  			<input type="<?php echo(isset($_GET['search-table']))||isset($_GET['select2_search'])?'submit':'hidden' ?>" name="reset" value="reset" id="reset"><br> -->
			<?php $args = array( 
		         'post_type' => array( 'product', 'ingredient'),
		         'post_status' => 'publish',
		         'posts_per_page' => -1,
		         'orderby' => 'name', 
		         'order' => 'ASC'
    		);
    		$the_query = new WP_Query( $args ); ?>
	  	  <form method="get" class="search-two">
	  	  		<div class="row">
			  	<div class="col-sm-10">
			  	<select class="product-ingredient" name="select2_id" style="width: 100%">
			      <option value="3620194" selected="selected"><?php _e('Search For Product Or Ingredient','agri');?></option>
			      <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
			        <option value="<?php echo get_the_ID();?>"><?php echo get_the_title(); ?></option>

			      <?php endwhile; ?>
			  	</select>
			  </div>
			    <div class="col-sm-2">
				  <input type="hidden" name="product_info" class="">
				  <input type="submit" name="select2_search" value="<?php _e('Search Product Data','agri');?>" class="select2_search search-data-btn" style="width: 100%;">
				 </div>
				 </div> 
	  	  </form>
        </div>
      </div>
    </div>
    <div class="panel">
      <div class="panel-heading">
        <h4 class="panel-title">
        <a href="#panelBodyTwo" class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion"><?php _e('Search Data By Crop, Manufacturer, Country, Activity Or Category','agri');?></a>
        </h4>
      </div>
	  <div id="panelBodyTwo" class="panel-collapse collapse">
		<div class="panel-body">
	      <div class="agr-style">
	  		<form method="get" class="navbar-form navbar-left">
				<div class="row">
					<div class="col-sm-4 country-select2">
						<label><?php _e('Select Countries','agri');?></label><br>
						<select multiple class="countries" name="countries[]">
							<option value=""><?php _e('All Countries','agri');?></option>
						<?php $countries = get_terms( 'country', array(
					    							  'hide_empty' => false,
													) ); 
							foreach ($countries as $key => $country) { ?>
							<option <?php echo (in_array($country->term_id,$_GET['countries'])?"selected=selected":null); ?> value="<?php echo($country->term_id); ?>"><?php echo($country->name); ?></option><br>
							<?php } ?>
						</select>
					</div>
					<!-- Countries Ends Here -->
					<!-- Category starts here -->
					<?php $posts_array = get_posts(array(
											        'posts_per_page' => -1,
											        'post_type' => 'ingredient',
											        'tax_query' => array(
											            array(
											                'taxonomy' => 'ingredient_category',
											                'field' => 'term_id',
											                'terms' => 10,
											            )
											        )
											    )
									); 
					$activities = array();
					foreach ($posts_array as $key => $post) {
						$products_ids[] = $wpdb->get_results("SELECT product_id FROM {$wpdb->prefix}product_ingredient WHERE ingredient_id = $post->ID "); 
					} 
					foreach ($products_ids as $key => $product_ids) {
					   	foreach ($product_ids as $key => $products) {
					   		$result = $wpdb->get_results("SELECT activity_id FROM {$wpdb->prefix}crop_data WHERE product_id = $products->product_id");
					   		foreach ($result as $key => $value) {
					   		array_push($activities, $value->activity_id);
					   		}
					   	}
					   }
					   $unique_activities=array_unique($activities);
					  ?>
					  <div class="col-sm-4 category-select">
						<label><?php _e('Select Category','sgc');?></label><br>
						<select class="category" name="category">
							<option value=""><?php _e('All Categories','sgc');?></option>
						<?php $categories = get_terms('ingredient_category',array(
														'hide_empty' => false,
														) );
							if(isset($_GET['category'])){

							}
						foreach ($categories as $key => $category) { ?>
							<option <?php echo ($_GET['category']==$category->name?"selected=selected":null); ?> value="<?php echo($category->name); ?>"><?php echo ($category->name); ?></option>
						<?php } ?>
						</select>
					  </div>
					<!-- Category Ends here -->
					<!-- Displaying the Manufacturer --> 
					<div class="col-sm-4 col-mdd-4">	
						<label><?php _e('Select Manufacturer','sgc');?></label><br>
						<select name= "manufacturer1" class="manufacturer1">
							<option value=""><?php _e('All Manufacturers','sgc');?></option>
						<?php $manufacturers = get_terms('manufacturer',array(
														'hide_empty' => false,
														) );
						foreach ($manufacturers as $key => $manufacturer) { ?>
							<option <?php echo ($_GET['manufacturer1']==$manufacturer->name?"selected=selected":null); ?> value="<?php echo($manufacturer->name); ?>"><?php echo ($manufacturer->name); ?></option>
						<?php } ?>
						</select>
					</div>
					<div class="ajax-load" style="display:none;" >
					</div>
					<!-- Manufacturer ends here -->
					<!-- Displaying the crops Here -->
					
				</div>
				<div class="row select-2">
					<div class="col-sm-6 col-mdd-4">
						<label><?php _e('Select Crop','sgc');?></label><br>
						<select name="crop" class="crops" >
							<option value=""><?php _e('All Crops','sgc');?></option>
						<?php $crops = get_terms('crop',array(
														'hide_empty' => false,
														) );
						foreach ($crops as $key => $crop) { ?>
							<option <?php echo ($_GET['crop']==$crop->term_id?"selected=selected":null); ?> value="<?php echo($crop->term_id); ?>"><?php echo ($crop->name); ?></option>
						<?php } ?>
						</select>
					</div>
					<!-- Crops Ends Here -->
					<div class="col-sm-6 col-mdd-4">
						<!-- Displaying the activities Here -->
						<label><?php _e('Select Activity','sgc');?></label><br>
						<select  class="activity" name="activity">
							<option value=""><?php _e('All Activities','sgc');?></option>
						<?php $activities = get_terms('activity',array(
														'hide_empty' => false,
														) );
						foreach ($activities as $key => $activity) { ?>
							<option <?php echo ($_GET['activity']==$activity->term_id?"selected=selected":null); ?> value="<?php echo($activity->term_id); ?>"><?php echo ($activity->name); ?></option>
						<?php } ?>
						</select>
						<!-- Activities Ends Here -->
						
					</div>
				</div>
			</div>
				<input type="submit" name="search-table" value="<?php _e('Search Data','agri');?>" class="search-data search-data-btn">
				<input type="<?php echo(isset($_GET['search-table']))||isset($_GET['select2_search'])?'submit':'hidden' ?>" name="reset" value="Reset" id="reset">
			</form>
		  </div>
	    </div>
      </div>
    </div>
  </div>



	<!-- Pagination Rows -->
				<!-- <div class="form-group">  -->	<!--		Show Numbers Of Rows 		-->
			 	<!-- 	<select class  ="form-control form-control-two" name="state" id="maxRows">
						 <option value="10">10</option>
						 <option value="20">20</option>
						 <option value="50">50</option>
						</select>
			 		
			  	</div> -->
			  	<div class="main-container">
			   		<!-- <div class="ad-banner">
				  		<img src="../wp-content/plugins/agri/image/screenshot-2.png">
				  	</div> -->
	<div class="container form-container">
			
<!-- Fields On The Front Ends To Filter The Table Ends Here -->

<!-- Php get method to search The Table -->
<?php
if(isset($_GET['search-table'])){
	//Get The Data From Form
	$category_name = $_GET['category'];
	$manufacturer_id = $_GET['manufacturer1'];
	$countries_id = $_GET['countries'];
	if($countries_id==null){
		$countries_id[0] ='';
	}
	$crop_id = $_GET['crop'];
	$activity_id = $_GET['activity'];
	$where_query = array();
	if ($manufacturer_id!='') {
	  $where_query[] = "term_tax.taxonomy='manufacturer'";
	  $where_query[] = "terms.name IN ('".$manufacturer_id."')" ;
	}
	if ($crop_id!='') {
	  $where_query[] = "crops.crop_id=" . $crop_id ;
	}
	if ($activity_id!='') {
	  $where_query[] = "crops.activity_id=" . $activity_id ;
	}
	if($manufacturer_id!=''||$crop_id!=''||$activity_id!=''){
		$where_query_text = " WHERE " . implode(' AND ', $where_query);
		$where_query_text .=" AND posts.post_status='publish'";
    }
    else{
    	$where_query_text = " WHERE posts.post_status='publish'";
    }

	$result = $wpdb->get_results(" SELECT  posts.*, crops.* ,prod_ing.* FROM {$wpdb->prefix}posts AS posts 
	 INNER JOIN {$wpdb->prefix}crop_data AS crops ON posts.ID = crops.product_id
	 INNER JOIN {$wpdb->prefix}product_ingredient AS prod_ing ON posts.ID = prod_ing.product_id
	 INNER JOIN {$wpdb->prefix}term_relationships AS tax_r ON posts.ID = tax_r.object_id
	 INNER JOIN {$wpdb->prefix}term_taxonomy AS term_tax ON tax_r.term_taxonomy_id = term_tax.term_taxonomy_id
	 INNER JOIN {$wpdb->prefix}terms AS terms ON term_tax.term_id = terms.term_id 
	 $where_query_text");
?>
	<div class="table-container">
	<table class="wp-list-table widefat fixed striped data-table order-table table" style= "width: 100%;text-align: center;" id= "table-id">
			<thead style="cursor: pointer;">
				<th><?php _e('Country','sgc');?></th>
				<th><?php _e('Manufacturer','sgc');?></th>
				<th><?php _e('Product','sgc');?></th>
				<th><?php _e('Composition','sgc');?></th>
				<th><?php _e('Dosage','sgc');?></th>
				<th><?php _e('Crop','sgc');?></th>
				<th><?php _e('Activity','sgc');?></th>
				<th><?php _e('Method Of Application','sgc');?></th>
				<th><?php _e('Restrictions','sgc');?></th>
			</thead>
<?php foreach ($countries_id as $term_country => $country_id) {
		$banner_count = 1;
 		foreach ($result as $key => $value) {
		$country = wp_get_post_terms( $value->ID, 'country');
		$ingredient_id_category = $wpdb->get_results("SELECT ingredient_id FROM {$wpdb->prefix}product_ingredient WHERE product_id =$value->ID");

		foreach ($ingredient_id_category as $ing => $ingredient) {
			$category1 = wp_get_post_terms( $ingredient->ingredient_id, 'ingredient_category',array("fields" => "names"));
			if(!empty($category1)){
			$category = $category1;
			}
		}
			if($country_id!=''&& $country_id!=$country[0]->term_id){
				continue;
			}
		if($category_name!=''&&!in_array($category_name, $category)){
			continue;
		}
		if($value->post_type=='product'){
			$country = wp_get_post_terms( $value->ID, 'country');
			$manufacturer = wp_get_post_terms( $value->ID, 'manufacturer'); 
			$activity = get_term_by('id', $value->activity_id, 'activity');
			$crop = get_term_by('id', $value->crop_id, 'crop');
			$ingredients = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}product_ingredient WHERE product_id=$value->product_id");
			$concat = "";
			$content="";
			$chemical_group='';
			foreach ($ingredients as $key => $ingr_val) {
				$active_ingredient = get_post($ingr_val->ingredient_id);
				$content.=$concat.$ingr_val->content." ".$active_ingredient->post_title;
				$chemical = wp_get_post_terms( $ingr_val->ingredient_id, 'chemical_group');
				$chemical_group.= $concat . $chemical[0]->name;
				$concat=" + ";
			}
			$content.=' ('.$chemical_group.')'; 
			?>
			<tr>
				<td><?php $country_name = term_exists( strtolower($country[0]->name), 'question_tag' );
					if ( $country_name !== 0 && $country_name !== null ) {?>
					<a href="/questions/tags/<?php echo (str_replace(" ","-",strtolower($country[0]->name))); ?>/" title="Click To Visit Question Answers" class="tooltip--bottom"><?php echo ($country[0]->name); ?></a>
					<?php }
					else{ echo ($country[0]->name); }
					?>
				</td>
				<!-- <td><a href="/questions/tags/"title="Click To Visit Question Answers" class="tooltip--bottom"><?php echo(str_replace(" ","-",strtolower($country[0]->name)));?></a></td> -->
				<!-- <td><a href="/questions/tags/" title="Click To Visit Question Answers" class="tooltip--bottom"><?php echo $manufacturer[0]->name;?></a></td> -->
				<td><?php $manufacturer_name = term_exists( strtolower($manufacturer[0]->name), 'question_tag' );
					if ( $manufacturer_name !== 0 && $manufacturer_name !== null ) {?>
					<a href="/questions/tags/<?php echo(str_replace(" ","-",strtolower($manufacturer[0]->name)));?>" title="Click To Visit Question Answers" class="tooltip--bottom"><?php echo($manufacturer[0]->name);?></a>
					<?php }
					else{ echo($manufacturer[0]->name); }
					?>
				</td>
				<td><?php $product_name = term_exists( strtolower(get_the_title($value->ID)), 'question_tag' );
					if ( $product_name !== 0 && $product_name !== null ) {?>
					<a href="/questions/tags/<?php echo(str_replace(" ","-",strtolower(get_the_title($value->ID))));?>" title="Click To Visit Question Answers" class="tooltip--bottom"><?php echo get_the_title($value->ID); ?></a>
					<?php }
					else{ echo get_the_title($value->ID); }
					?>
				</td>
				<!-- <td><a href="/questions/tags/" title="Click To Visit Question Answers" class="tooltip--bottom"><?php echo (str_replace(" ","-",strtolower(get_the_title($value->ID)))); ?></a></td> -->
				<td><?php $comp = term_exists( strtolower($content), 'question_tag' );
					if ( $comp !== 0 && $comp !== null ) {?>
					<a href="/questions/tags/<?php echo(str_replace(" ","-",strtolower($content)));?>/" title="Click To Visit Question Answers" class="tooltip--bottom"><?php echo($content);?></a>
					<?php }
					else{ echo $content; }
					?>
				</td>
				<!-- <td><a href="/questions/tags" title="Click To Visit Question Answers" class="tooltip--bottom"><?php echo $content ?></a></td> -->
				<td><?php echo $value->dosage ?></td>
				<td><?php $crop_name = term_exists( strtolower($crop->name), 'question_tag' );
					if ( $crop_name !== 0 && $crop_name !== null ) {?>
					<a href="/questions/tags/<?php echo(str_replace(" ","-",strtolower($crop->name)));?>/" title="Click To Visit Question Answers" class="tooltip--bottom"><?php echo $crop->name;?></a>
					<?php }
					else{ echo $crop->name; }
					?>
				</td>
				<!-- <td><?php echo(str_replace(" ","-",strtolower($crop->name)));?></td> -->
				<td><?php $activity_name = term_exists( strtolower($activity->name), 'question_tag' );
					if ( $activity_name !== 0 && $activity_name !== null ) {?>
					<a href="/questions/tags/<?php echo(str_replace(" ","-",strtolower($activity->name)));?>/" title="Click To Visit Question Answers" class="tooltip--bottom"><?php echo($activity->name);?></a>
					<?php }
					else{ echo $activity->name; }
					?>
				</td>
				<!-- <td><a href="/questions/tags" title="Click To Visit Question Answers" class="tooltip--bottom"><?php echo $activity->name;?></a></td> -->
				<td><?php echo wp_trim_words($value->method_app, 10, '...'); if(str_word_count($value->method_app) > 10){?> <a tabindex="0" class=""  style="color: #2f7f8e; text-decoration:underline;" data-toggle="popover" data-placement="left" data-content="<?php echo($value->method_app);?>">More>></a><?php }?></td>
				<td><?php echo wp_trim_words($value->quarantine, 10, '...'); if(str_word_count($value->quarantine) > 10){?> <a tabindex="0" class=""  style="color: #2f7f8e; text-decoration:underline;" data-toggle="popover" data-placement="left" data-content="<?php echo($value->quarantine);?>">More>></a><?php }?></td>
			</tr>
			<?php if (($banner_count%10)==0) { ?>
			
			<?php } $banner_count++; ?>
		<?php }elseif ($value->post_type=='ingredient') {
			$get_products = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}product_ingredient WHERE ingredient_id=$value->ID");
			foreach ($get_products as $key1 => $product) {
				$get_crops = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}crop_data WHERE product_id=$product->product_id ");
				$banner_count = 1;
				foreach ($get_crops as $key2 => $crop) {
				$country = wp_get_post_terms( $crop->product_id, 'country');
				$manufacturer = wp_get_post_terms( $crop->product_id, 'manufacturer'); 
				$activity = get_term_by('id', $crop->activity_id, 'activity');
				$crops = get_term_by('id', $crop->crop_id, 'crop'); ?>
				   <tr>
					<td><a href="/questions/tags/<?php echo(str_replace(" ","-",strtolower($country[0]->name)));?>/" title="Click To Visit Question Answers" class="tooltip--bottom"><?php echo $country[0]->name;?></a></td>
					<td><a href="/questions/tags/<?php echo(str_replace(" ","-",strtolower($manufacturer[0]->name)));?>/" title="Click To Visit Question Answers" class="tooltip--bottom"><?php echo $manufacturer[0]->name;?></a></td>
					<td><a href="/questions/tags/<?php echo(str_replace(" ","-",strtolower(get_the_title($product->product_id)))); ?>/" title="Click To Visit Question Answers" class="tooltip--bottom"><?php echo(str_replace(" ","-",strtolower(get_the_title($product->product_id)))); ?></a></td>
					<td><a href="/questions/tags" title="Click To Visit Question Answers" class="tooltip--bottom"><?php echo $product->content ?></a></td>
					<td><a href="/questions/tags" title="Click To Visit Question Answers" class="tooltip--bottom"><?php echo $crop->dosage ?></a></td>
					<td><a href="/questions/tags/<?php echo(str_replace(" ","-",strtolower($crops->name)));?>/" title="Click To Visit Question Answers" class="tooltip--bottom"><?php echo $crops->name;?></a></td>
					<td><?php echo $activity->name;?></td>
					<td><?php echo $crop->method_app; ?></td>
					<td><?php echo $crop->quarantine; ?></td>
				</tr>
				<?php if (($banner_count%10)==0) { ?>
				
			<?php } $banner_count++; ?>
			<?php }
				?>
		<?php }
		 ?>

			
	  <?php } ?>
	<?php } }
	?>
	</table>
	 <!--Start Pagination -->
			<div class='pagination-container data-tab-pag' >
				<nav>
				  <ul class="pagination">
				   <!--	Here the JS Function Will Add the Rows -->
				  </ul>
				</nav>
			</div>
	<!--End Pagination -->
	</div>
		<!-- This Is Displaying All The Data Of All The Products Or Ingredients with Select 2 search -->
<?php  }elseif (isset($_GET['select2_search'])) {
	$post_id = $_GET['select2_id'];
	$post_data = get_post($post_id);
	if ($post_data->post_type=="product") {
			$get_ingredients = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}product_ingredient WHERE product_id = $post_data->ID");
			$ingredient_name='';
			$ingredient_content='';
			$chemical_group='';
			foreach ($get_ingredients as $key => $get_ingredient) {
				$ingredient_name.=$concat." ".get_the_title($get_ingredient->ingredient_id);
				$ingre_name = get_the_title($get_ingredient->ingredient_id);
				//$ingredient_content.=$concat.$get_ingredient->content;
				$ingredient_content.=$concat1.$get_ingredient->content." ".$ingre_name;
				$chemical = wp_get_post_terms( $get_ingredient->ingredient_id, 'chemical_group');
				$chemical_group.= $concat1 . $chemical[0]->name;
				$concat1=" + ";
				/*$category = wp_get_post_terms( $get_ingredient->ingredient_id, 'ingredient_category');*/
				$concat=',';
			}
			$ingredient_content.=' ('.$chemical_group.')';
			$get_crops = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}crop_data
				WHERE product_id = $post_data->ID"); ?>
			<div class="table-container">	
			<table class="wp-list-table widefat fixed striped data-table order-table table table-list"  style= "width: 100%;text-align: center;" id= "table-id" data-currentpage="1">
				<thead style="cursor: pointer;">
					<th><?php _e('Active Ingredient','agri');?></th>	
					<th><?php _e('Country','agri');?></th>
					<th><?php _e('Manufacturer','agri');?></th>
					<th><?php _e('Product Name','agri');?></th>
					<th><?php _e('Composition','agri');?></th>
					<th><?php _e('Dosage','agri');?></th>
					<th><?php _e('Crop','agri');?></th>
					<th><?php _e('Activity','agri');?></th>
					<th><?php _e('Method of application','agri');?></th>
					<th><?php _e('Restrictions','agri');?></th>
				</thead>
			<?php
			$banner_count = 1;
			foreach ($get_crops as $key => $crop) {
				$manufacturer = wp_get_post_terms( $crop->product_id, 'manufacturer'); 
				$country = wp_get_post_terms( $crop->product_id, 'country');

				$crop_data=get_term_by('id', $crop->crop_id, 'crop'); 
				$activity_data=get_term_by('id', $crop->activity_id, 'activity'); ?>
				<tr>
					<td><a href="/questions/tags/<?php echo(str_replace(" ","-",strtolower($ingredient_name)));?>/" title="Click To Visit Question Answers" class="tooltip--bottom"><?php echo($ingredient_name);?></a></td>
					<td><a href="/questions/tags/<?php echo (str_replace(" ","-",strtolower($country[0]->name))); ?>/" title="Click To Visit Question Answers" class="tooltip--bottom"><?php echo ($country[0]->name); ?></a></td>
					<td><a href="/questions/tags/<?php echo (str_replace(" ","-",strtolower($manufacturer[0]->name)));?>/" title="Click To Visit Question Answers" class="tooltip--bottom"><?php echo ($manufacturer[0]->name);?></a></td>
					<td><a href="/questions/tags/<?php echo(str_replace(" ","-",strtolower(get_the_title($crop->product_id))));?>/" title="Click To Visit Question Answers" class="tooltip--bottom"><?php echo(get_the_title($crop->product_id));?></a></td>
					<td><?php echo ($ingredient_content) ?></td>
					<td><?php echo($crop->dosage)?></td>
					<td><a href="/questions/tags/<?php echo(str_replace(" ","-",strtolower($crop_data->name))); ?>" title="Click To Visit Question Answers" class="tooltip--bottom"><?php echo($crop_data->name); ?></a></td>
					<td><a href="//questions/tags/<?php echo(str_replace(" ","-",strtolower($activity_data->name)));?>/" title="Click To Visit Question Answers" class="tooltip--bottom"><?php echo($activity_data->name);?></a></td>
					<td><?php echo($crop->method_app) ?></td>
					<td><?php echo($crop->quarantine) ?></td>	
				</tr>
				<?php if (($banner_count%10)==0) { ?>
				
			<?php } $banner_count++; ?>
			<?php } ?>
			</table>
			 <!--Start Pagination -->
			<div class='pagination-container data-tab-pag' >
				<nav>
				  <ul class="pagination">
				   <!--	Here the JS Function Will Add the Rows -->
				  </ul>
				</nav>
			</div>
	<!--End Pagination -->
			</div>
		<?php }elseif ($post_data->post_type=="ingredient") {
			$get_product= $wpdb->get_results("SELECT * FROM {$wpdb->prefix}product_ingredient WHERE ingredient_id = $post_data->ID group by product_id");
			$get_crops1 = array();
			foreach ($get_product as $key => $product) {
			$product_id = $product->product_id;
			$get_crops1[] = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}crop_data
				WHERE product_id = $product_id"); 
			}
			 ?>
			 <div class="table-container">
			<table class="wp-list-table widefat fixed striped data-table order-table table table-list"  style= "width: 100%;text-align: center;" id= "table-id" data-currentpage="1">
				<thead style="cursor: pointer;">
					<th><?php _e('Active ingredient','agri');?></th>	
					<th><?php _e('Product Name','agri');?></th>
					<th><?php _e('Composition','agri');?></th>
					<th><?php _e('Manufacturer','agri');?></th>
					<th><?php _e('Country','agri');?></th>
					<th><?php _e('Crop','agri');?></th>
					<th><?php _e('Dosage','agri');?></th>
					<th><?php _e('Activity','agri');?></th>
					<th><?php _e('Method of application','agri');?></th>
					<th><?php _e('Restrictions','agri');?></th>
				</thead>
			<?php $banner_count = 1;
			 foreach ($get_crops1 as $key => $get_crops) {
				 foreach ($get_crops as $key => $crop) {
					$manufacturer = wp_get_post_terms( $crop->product_id, 'manufacturer'); 
					$country = wp_get_post_terms( $crop->product_id, 'country');
					$crop_data=get_term_by('id', $crop->crop_id, 'crop'); 
					$activity_data=get_term_by('id', $crop->activity_id, 'activity'); ?>
					<tr>
						<td><a href="/questions/tags/<?php echo(str_replace(" ","-",strtolower(get_the_title($post_data->ID))));?>/" title="Click To Visit Question Answers" class="tooltip--bottom"><?php echo get_the_title($post_data->ID);?></a></td>
						<td><a href="/questions/tags/<?php echo(str_replace(" ","-",strtolower(get_the_title($crop->product_id))));?>" title="Click To Visit Question Answers" class="tooltip--bottom"><?php echo(get_the_title($crop->product_id));?></a></td>
						<td><?php echo ($get_product[0]->content) ?></a></td>
						<td><a href="/questions/tags/<?php echo (str_replace(" ","-",strtolower($manufacturer[0]->name)));?>/" title="Click To Visit Question Answers" class="tooltip--bottom"><?php echo ($manufacturer[0]->name);?></a></td>
						<td><a href="/questions/tags/<?php echo (str_replace(" ","-",strtolower($country[0]->name))); ?>/" title="Click To Visit Question Answers" class="tooltip--bottom"><?php echo ($country[0]->name); ?></a></td>
						<td><a href="/questions/tags/<?php echo(str_replace(" ","-",strtolower($crop_data->name))); ?>/" title="Click To Visit Question Answers" class="tooltip--bottom"><?php echo($crop_data->name); ?></a></td>
						<td><?php echo($crop->dosage)?></a></td>
						<td><a href="/questions/tags/<?php echo(str_replace(" ","-",strtolower($activity_data->name)));?>/" title="Click To Visit Question Answers" class="tooltip--bottom"><?php echo($activity_data->name);?></a></td>
						<td><?php echo($crop->method_app) ?></a></td>
						<td><?php echo($crop->quarantine) ?></a></td>	
					</tr>
					<?php if (($banner_count%10)==0) { ?>
					
				<?php } $banner_count++; ?>
			<?php } } ?>
			</table>
			 <!--Start Pagination -->
			<div class='pagination-container data-tab-pag' >
				<nav>
				  <ul class="pagination">
				   <!--	Here the JS Function Will Add the Rows -->
				  </ul>
				</nav>
			</div>
	<!--End Pagination -->
			</div>	
		<?php }
}
 else { ?>
	<div class="table-container">
	<div id="tableID">
	<table class="wp-list-table widefat fixed striped data-table order-table table table-list"  style= "width: 100%;text-align: center;" id= "table-id" data-currentpage="1">
			<thead style="cursor: pointer;">
				<th><?php _e('Active ingredient','agri');?></th>
				<th><?php _e('Product Name','agri');?></th>
				<th><?php _e('Composition','agri');?></th>
				<th><?php _e('Manufacturer','agri');?></th>
				<th><?php _e('Country','agri');?></th>
				<th><?php _e('Crop','agri');?></th>
				<th><?php _e('Dosage','agri');?></th>
				<th><?php _e('Activity','agri');?></th>
				<th><?php _e('Method of application','agri');?></th>
				<th><?php _e('Restrictions','agri');?></th>
			</thead>
		<tbody class="list">
			<?php
			$args = array(
				'post_type' => 'product',
				'post_status' => 'publish',
				'posts_per_page' => -1
				);              
			$the_query = new WP_Query( $args);
			$banner_count = 1;		
			while ( $the_query->have_posts() ) :
				$the_query->the_post(); 
				$id = get_the_ID();
				$country = wp_get_post_terms( $id, 'country');
				$manufacturer = wp_get_post_terms( $id, 'manufacturer');

				$get_crop = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}crop_data where product_id=$id AND status=1");
				$get_ingredients = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}product_ingredient where product_id=$id AND status=1");
				$ingredient_name='';
				$ingredient_content='';
				$concat='';
				$concat1='';
				$chemical_group='';
				foreach ($get_ingredients as $key => $get_ingredient) {
					$ingredient_name.=$concat.get_the_title($get_ingredient->ingredient_id);
					$ingre_name = get_the_title($get_ingredient->ingredient_id);
					$ingredient_content.=$concat1.$get_ingredient->content." ".$ingre_name;
					$chemical = wp_get_post_terms( $get_ingredient->ingredient_id, 'chemical_group',true);
					$chemical_group.= $concat1 . $chemical[0]->name;
					$concat1=" + ";
					$concat=' , ';
				}
				if($chemical_group!='')
				$ingredient_content.=' ('.$chemical_group.')';
				foreach ($get_crop as $key => $crop):
					$crop_data=get_term_by('id', $crop->crop_id, 'crop'); 
					$activity_data=get_term_by('id', $crop->activity_id, 'activity');
					$ingredient_data='';
					?>
			<tr>
				<td><?php $term = term_exists( strtolower($ingredient_name), 'question_tag' );
					if ( $term !== 0 && $term !== null ) {?>
					<a href="/questions/tags/<?php echo(str_replace(" ","-",strtolower($ingredient_name)));?>/" title="Click To Visit Question Answers" class="tooltip--bottom"><?php echo($ingredient_name);?></a>
					<?php }
					else{ echo($ingredient_name); }
					?>
				</td>
				<td><?php $prod_title = term_exists( get_the_title($crop->product_id), 'question_tag' );
					if ( $prod_title !== 0 && $prod_title !== null ) {?>
					<a href="/questions/tags/<?php echo(str_replace(" ","-",strtolower(get_the_title($crop->product_id))));?>/" title="Click To Visit Question Answers" class="tooltip--bottom"><?php echo(get_the_title($crop->product_id));?></a>
					<?php }
					else{ echo(get_the_title($crop->product_id)); }
					?>
				</td>
				<td><?php echo ($ingredient_content) ?>
				</td>
				<td><?php $manufacturer_name = term_exists( strtolower($manufacturer[0]->name), 'question_tag' );
					if ( $manufacturer_name !== 0 && $manufacturer_name !== null ) {?>
					<a href="/questions/tags/<?php echo(str_replace(" ","-",strtolower($manufacturer[0]->name)));?>" title="Click To Visit Question Answers" class="tooltip--bottom"><?php echo($manufacturer[0]->name);?></a>
					<?php }
					else{ echo($manufacturer[0]->name); }
					?>
				</td>
				<td><?php $country_name = term_exists( strtolower($country[0]->name), 'question_tag' );
					if ( $country_name !== 0 && $country_name !== null ) {?>
					<a href="/questions/tags/<?php echo (str_replace(" ","-",strtolower($country[0]->name))); ?>/" title="Click To Visit Question Answers" class="tooltip--bottom"><?php echo ($country[0]->name); ?></a>
					<?php }
					else{ echo ($country[0]->name); }
					?>
				</td>
				<td><?php $crop_name = term_exists( strtolower($crop_data->name), 'question_tag' );
					if ( $crop_name !== 0 && $crop_name !== null ) {?>
					<a href="/questions/tags/<?php echo(str_replace(" ","-",strtolower($crop_data->name)));?>/" title="Click To Visit Question Answers" class="tooltip--bottom"><?php echo($crop_data->name);?></a>
					<?php }
					else{ echo($crop_data->name); }
					?>
				</td>
				<td><?php echo($crop->dosage)?></td>
				<td><?php $activity_name = term_exists( strtolower($activity_data->name), 'question_tag' );
					if ( $activity_name !== 0 && $activity_name !== null ) {?>
					<a href="/questions/tags/<?php echo(str_replace(" ","-",strtolower($activity_data->name)));?>/" title="Click To Visit Question Answers" class="tooltip--bottom"><?php echo($activity_data->name);?></a>
					<?php }
					else{ echo($activity_data->name); }
					?>
				</td>
				<td class="method_aap"><?php echo wp_trim_words($crop->method_app, 10, '...'); if(str_word_count($crop->method_app) > 10){?> <a tabindex="0" class=""  style="color: #2f7f8e; text-decoration:underline;" data-toggle="popover" data-placement="left" data-content="<?php echo($crop->method_app);?>">More>></a><?php }?></td>
				<td><?php echo wp_trim_words($crop->quarantine, 10, '...'); if(str_word_count($crop->quarantine) > 10){?> <a tabindex="0" class=""  style="color: #2f7f8e; text-decoration:underline;" data-toggle="popover" data-placement="left" data-content="<?php echo($crop->quarantine);?>">More>></a><?php }?></td>
			</tr>
			<?php if (($banner_count%10)==0) { ?>
				
			<?php } $banner_count++; ?>
			 <?php endforeach; ?>
			<?php endwhile; ?>
		</tbody>
	  </table>
	</div>
	</div>
     <!--Start Pagination -->
			<!-- <div class='pagination-container data-tab-pag' >
				<nav>
				  <ul class="pagination"> -->
				   <!--	Here the JS Function Will Add the Rows -->
				<!--   </ul>
				</nav>
			</div> -->
	<!--End Pagination -->
</div>
</div>
</div>
    <!-- Table To Display Complete Data From All the Products End Here -->
<?php 
 } ?>
<?php }
//Ajax Call To Get Activities On The Base Of Selected Ingredient Category
add_action('wp_ajax_nopriv_get_activities','agri_get_activities');
add_action('wp_ajax_get_activities','agri_get_activities');
function agri_get_activities(){
	global $wpdb;
	$category = $_POST['category'];
	$posts_array = get_posts(array(
							        'posts_per_page' => -1,
							        'post_type' => 'ingredient',
							        'tax_query' => array(
							            array(
							                'taxonomy' => 'ingredient_category',
							                'field' => 'name',
							                'terms' => $category,
							            )
							        )
							    )
						);
	$activities = array();
		foreach ($posts_array as $key => $post) {
			$products_ids[] = $wpdb->get_results("SELECT product_id FROM {$wpdb->prefix}product_ingredient WHERE ingredient_id = $post->ID "); 
		} 
		foreach ($products_ids as $key => $product_ids) {
		   	foreach ($product_ids as $key => $products) {
		   		$result = $wpdb->get_results("SELECT activity_id FROM {$wpdb->prefix}crop_data WHERE product_id = $products->product_id");
		   		foreach ($result as $key => $value) {
		   		array_push($activities, $value->activity_id);
		   		}
		   	}
		}
	$unique_activities =array_unique($activities);
	foreach ($unique_activities as $key => $activity) {
		$term = get_term_by('id', $activity, 'activity');
		$activity_name[$activity]= $term->name;
	}
	echo (json_encode($activity_name));
	die();
}
?>