<?php
function input_data(){
	global $wpdb;
	$current_user = wp_get_current_user();
	$role = $current_user->roles[0];
	$id=$current_user->ID;
	if ($current_user->ID == 0 ){
		wp_redirect('/login/');
	}
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
    <form method="post" action="../data-added">
    	<input type="text" name="input-product-name" placeholder="Enter Product name">
	<div class="form-group dynamic-element" >
  <!-- Replace these fields -->
    <select id="input-ingredient" name="input-ingredient[]" class="form-control">
    	<option value = "0">Choose Active Ingredient</option>
    	<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
				<option value="<?php echo(get_the_ID()) ?>"><?php echo get_the_title() ?></option>
		<?php endwhile; ?>
     <?php ?>
    </select>
    <input type="text" name="input-composition[]" placeholder="Enter Composition">
    <div class="col-md-1">
      <p class="delete">x</p>
    </div>
  </div>
  <div class="dynamic-stuff">
  </div>
  <!-- Dynamic Add fields --> 
  <a class="add-ingredient">Add another active ingredient</a>
  <!-- End dynamic fields -->
  <p>if you cannot find an active ingredient in the list above, please contact support@agrinomia.com</p>

  <!-- Countries Drop Down -->
  <label>Please select the country</label>
  <select name="input-country">
  	<option>Choose Country</option>
  	<?php foreach ($countries as $key => $country): ?>
  		<option value="<?php echo ($country->term_id) ?>"><?php echo ($country->name); ?></option>
  	<?php endforeach; ?>
  </select>
  <!-- Countries Drop down Ends here -->
  <!-- Manufacturer Drop Down -->
  <label>Please Select Manufacturer</label>
  <select name="input-manufacturer">
  	<option value="0">Choose Manufacturer</option>
	<?php foreach ($manufacturers as $key => $manufacturer): ?>
		<option value="<?php echo ($manufacturer->term_id) ?>"><?php echo ($manufacturer->name); ?></option>
  	<?php endforeach; ?>
	</select>
  <!-- Manufacturer Drop Down Ends Here -->
  <div class="input-crop-fields">
  	<!-- Crop Drop Down -->
  	<label>Please Select Crop</label>
  <select name="input-crops[]">
  	<option value="0">Choose Crop</option>
	<?php foreach ($crops as $key => $crop): ?>
		<option value="<?php echo ($crop->term_id) ?>"><?php echo ($crop->name); ?></option>
  	<?php endforeach; ?>
	</select>
		<!-- Crop Drop Down Ends Here -->

  <!-- Activity Drop Down Starts Here -->
  <label>Please Select Activity</label>
  <select name="input-activity[]">
  	<option value="0">Choose Activity</option>
	<?php foreach ($activities as $key => $activity): ?>
		<option value="<?php echo ($activity->term_id) ?>"><?php echo ($activity->name); ?></option>
  	<?php endforeach; ?>
	</select>
	<br>
	<br>
  <!-- Activity Drop Down Ends Here -->
  <input type="text" name="input-dosage[]" placeholder="Enter Dosage">
  <input type="text" name="input-method-app[]" placeholder="Enter Method Of Application">
  <input type="text" name="input-quarantine[]" placeholder="Enter Quarantine">
</div>
<br>
<div class="dynamic-crop">
</div>
<!-- Button to Add Crop Fields -->
  <a class="add-another-crop">Add Another Crop</a>
  <br>
  <!-- Button to Add Crop Fields Ends Here -->

	<button type="submit" name="singlebutton">Submit For Approval</button>
</form>	
	<?php
}
add_shortcode('input-data-table','input_data');
function datatable_add(){
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
	if($current_user->ID!=0){
		$product_post = array(
				'post_title'    => $product_name,
				'post_type'     => 'product',
				'post_status'   => 'draft',
				'post_author'   => $current_user->ID,
				'post_category' => array( 8,39 )
			);
		$product_id = wp_insert_post( $product_post);
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
add_shortcode('data-added','datatable_add');
										function custom_post_type(){
											$labels = array(
												'name'                  => _x( 'Ingredients', 'Post type general name', 'textdomain' ),
												'singular_name'         => _x( 'Ingredient', 'Post type singular name', 'textdomain' ),
												'menu_name'             => _x( 'Ingredients', 'Admin Menu text', 'textdomain' ),
												'name_admin_bar'        => _x( 'Ingredient', 'Add New on Toolbar', 'textdomain' ),
												'add_new'               => __( 'Add New', 'textdomain' ),
												'add_new_item'          => __( 'Add New Ingredient', 'textdomain' ),
												'new_item'              => __( 'New Ingredient', 'textdomain' ),
												'edit_item'             => __( 'Edit Ingredient', 'textdomain' ),
												'view_item'             => __( 'View Ingredient', 'textdomain' ),
												'all_items'             => __( 'All Ingredients', 'textdomain' ),
												'search_items'          => __( 'Search Ingredients', 'textdomain' ),
												'parent_item_colon'     => __( 'Parent Ingredients:', 'textdomain' ),
												'not_found'             => __( 'No Ingredients found.', 'textdomain' ),
												'not_found_in_trash'    => __( 'No Ingredients found in Trash.', 'textdomain' ),
												'featured_image'        => _x( 'Ingredient  Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain' ),
												'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
												'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
												'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
												'archives'              => _x( 'Ingredient archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'textdomain' ),
												'insert_into_item'      => _x( 'Insert into Ingredient', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'textdomain' ),
												'uploaded_to_this_item' => _x( 'Uploaded to this Ingredient', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'textdomain' ),
												'filter_items_list'     => _x( 'Filter Ingredients list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'textdomain' ),
												'items_list_navigation' => _x( 'Ingredients list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'textdomain' ),
												'items_list'            => _x( 'Ingredients list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'textdomain' ),
											);

											$args = array(
												'labels'             => $labels,
												'public'             => true,
												'publicly_queryable' => true,
												'show_ui'            => true,
												'show_in_menu'       => true,
												'query_var'          => true,
												'rewrite'            => array( 'slug' => 'ingredient' ),
												'capability_type'    => 'page',
												'has_archive'        => true,
												'hierarchical'       => false,
												'menu_position'      => null,
												'supports'           => array( 'title' ),
											);
											register_post_type( 'Ingredient', $args );
										}
										add_action('init', 'custom_post_type');
										function custom_post_type1(){
											$labels = array(
												'name'                  => _x( 'Products', 'Post type general name', 'textdomain' ),
												'singular_name'         => _x( 'Product', 'Post type singular name', 'textdomain' ),
												'menu_name'             => _x( 'Products', 'Admin Menu text', 'textdomain' ),
												'name_admin_bar'        => _x( 'Product', 'Add New on Toolbar', 'textdomain' ),
												'add_new'               => __( 'Add New', 'textdomain' ),
												'add_new_item'          => __( 'Add New Product', 'textdomain' ),
												'new_item'              => __( 'New Product', 'textdomain' ),
												'edit_item'             => __( 'Edit Product', 'textdomain' ),
												'view_item'             => __( 'View Product', 'textdomain' ),
												'all_items'             => __( 'All Products', 'textdomain' ),
												'search_items'          => __( 'Search Products', 'textdomain' ),
												'parent_item_colon'     => __( 'Parent Products:', 'textdomain' ),
												'not_found'             => __( 'No Products found.', 'textdomain' ),
												'not_found_in_trash'    => __( 'No Products found in Trash.', 'textdomain' ),
												'featured_image'        => _x( 'Product  Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain' ),
												'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
												'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
												'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
												'archives'              => _x( 'Product archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'textdomain' ),
												'insert_into_item'      => _x( 'Insert into Product', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'textdomain' ),
												'uploaded_to_this_item' => _x( 'Uploaded to this Product', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'textdomain' ),
												'filter_items_list'     => _x( 'Filter Products list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'textdomain' ),
												'items_list_navigation' => _x( 'Products list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'textdomain' ),
												'items_list'            => _x( 'Products list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'textdomain' ),
											);

											$args = array(
												'labels'             => $labels,
												'public'             => true,
												'publicly_queryable' => true,
												'show_ui'            => true,
												'show_in_menu'       => true,
												'query_var'          => true,
												'rewrite'            => array( 'slug' => 'product' ),
												'capability_type'    => 'page',
												'has_archive'        => true,
												'hierarchical'       => false,
												'menu_position'      => null,
												'supports'           => array( 'title' ),
											);
											register_post_type( 'Product', $args );
										}
										add_action('init', 'custom_post_type1');

/**
 * Register meta box(es) for Product post type.
 */
function wpdocs_register_meta_boxes1() {
	add_meta_box( 'meta-box-id-1', __( 'Products Ingredient', 'textdomain' ), 'product_datatable', 'product' );
	add_meta_box( 'meta-box-id', __( 'DataTable', 'textdomain' ), 'wpdocs_my_display_callback1', 'product' );
}
add_action( 'add_meta_boxes', 'wpdocs_register_meta_boxes1' );

function product_datatable($post){
	global $wpdb;
	$get_ingredients= $wpdb->get_results("SELECT * FROM {$wpdb->prefix}product_ingredient WHERE product_id=$post->ID");
	?>
	<table class="wp-list-table widefat fixed striped" width="100%" >
			<tr style="background-color:#2EA2CC;color:#FFF;height:35px;">
				<th>Active Ingredient</th>
				<th>Composition</th>
			</tr>
			<?php foreach ($get_ingredients as $key => $table) { ?>
			<tr>
				<td><?php echo (get_the_title($table->ingredient_id)); ?></td>
				<td><?php echo ($table->content); ?></td>
			</tr>
			<?php wp_reset_postdata(); } ?>
		</table>


<?php
}

function wpdocs_my_display_callback1( $post ) {
	global $wpdb;
	$product_name =get_the_title();
	$product_id = get_the_ID(); 
	$get_datatable = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}crop_data WHERE product_id= '$product_id'");
	?>
	<!-- <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.min.css'> -->
	<script>
		jQuery( document ).ready(function( $ ) {
			jQuery(".delete-product").click(function () {
				var table_id1 = $( this ).prev().val();  
				console.log(table_id1);
				jQuery.ajax({
					url: ajaxurl,
					type: 'post',
					data: {
						action   :'delete_datatable1',
						table_id1 : table_id1,
					},
					success: function (response) {
						console.log("Successfully Deleted");
						location.reload();
					}
				});

			});
			jQuery("#add-data3").click(function () {
				var post_id = $('#post_id3').val(); 
				var product_name = $('#product_name3').val(); 
				var chemical_group = $('#chemical_group3').val(); 
				var ingredient_category = $('#ingredient_category3').val(); 
				var active_ingredient = $('#active_ingredient3').val(); 
				var content = $('#content3').val(); 
				var manufacturer = $('#manufacturer3').val(); 
				var country = $('#country3').val(); 
				var crop = $('#crop_name3').val(); 
				var dosage = $('#dosage_name3').val(); 
				var disease = $('#disease_name3').val(); 
				var method_app = $('#method_app3').val();
				var quarantine_period = $('#quarantine3').val();       
				jQuery.ajax({
					url: ajaxurl,
					type: 'post',
					data: {
						action   :    'add_product_data1',
						post_id :   			post_id,
						product_name : 			product_name,
						chemical_group : 		chemical_group,
						ingredient_category : 	ingredient_category,
						active_ingredient : 		active_ingredient,
						content : 				content,
						manufacturer : 			manufacturer,
						country :  				country,
						crop : 					crop,
						dosage : 				dosage,
						disease : 				disease,
						method_app : 			method_app,
						quarantine_period : 	quarantine_period,
					},
					success: function (response) {
						console.log("Successfully Added Data");
						location.reload();
					}
				});

			});
			$('.add-one').click(function(){
				$('.dynamic-element').first().clone().appendTo('.dynamic-stuff').show();
				attach_delete();
			});
			$('.add-one-crop').click(function(){
				$('.dynamic-element1').first().clone().appendTo('.dynamic-stuff-1').show();
				attach_delete1();
			});
			function attach_delete1(){
				$('.delete1').off();
				$('.delete1').click(function(){
					console.log("click");
					$(this).closest('.form-group1').remove();
				});
			}


				//Attach functionality to delete buttons
				function attach_delete(){
					$('.delete').off();
					$('.delete').click(function(){
						console.log("click");
						$(this).closest('.form-group').remove();
					});
				}
				jQuery(".update4").click(function () {
					var table_id = $('.table_id').val();
					var crop = $('.crop4').html();
					var dosage = $('.dosage4').html();
					var disease = $('.disease4').html();
					var method_app = $('.method_app4').html();
					var quarantine = $('.quarantine4').html();
					console.log(table_id);
					$('#table_id4').val(table_id);
					$('#crop_name4').val(crop);
					$('#dosage_name4').val(dosage);
					$('#disease_name4').val(disease);
					$('#method_app4').val(method_app);
					$('#quarantine4').val(quarantine);

				});
				jQuery("#update-data4").click(function () {
					var table_id = $('#table_id4').val(); 
					var crop         	= $('#crop_name4').val();
					var dosage 			= $('#dosage_name4').val();
					var disease 		= $('#disease_name4').val();
					var method_app 		= $('#method_app4').val();
					var quarantine 		= $('#quarantine4').val(); 
					console.log (table_id);  

					jQuery.ajax({
						url: ajaxurl,
						type: 'post',
						data: {
							action   		:'update_product_datatable',
							table_id 		: table_id,
							crop 			: crop,
							dosage 			: dosage,
							disease 		: disease,
							method_app 		: method_app,
							quarantine 		: quarantine,
						},
						success: function (response) {
							console.log("Successfully updated product Data");
							location.reload();
							tb_remove();

						}
					});
				});
			});
		</script>
		<div id="my-content-id3" style="display:none;">
			<div class="my-modal-1">
				<input type="hidden" name="post_id3" id="post_id3" value="<?php echo $post->ID; ?>">
				<input class="modal_input" type="hidden" name="product_name3" id="product_name3" value="<?php echo get_the_title(); ?>">
				<input class="modal_input" type="hidden" name="chemical_group3" id="chemical_group3" value="<?php echo get_post_meta($post->ID,'chemical_group', true); ?>">
				<input class="modal_input" type="hidden" name="ingredient_category3" id="ingredient_category3" value="<?php echo get_post_meta($post->ID,'ingredient_category', true); ?>">
				<input class="modal_input" type="hidden" name="active_ingredient3" id="active_ingredient3" value="<?php echo get_post_meta($post->ID,'active_ingredient', true); ?>">
				<input class="modal_input" type="hidden" name="content3" id="content3" value="<?php echo get_post_meta($post->ID,'content', true); ?>">
				<input class="modal_input" type="hidden" name="manufacturer3" id="manufacturer3" value="<?php echo get_post_meta($post->ID,'manufacturer', true); ?>">
				<input class="modal_input" type="hidden" name="country3" id="country3" value="<?php echo get_post_meta($post->ID,'country', true); ?>">
				<input class="modal_input" type="text" name="crop3" id="crop_name3" placeholder="Enter Crop">

				<input class="modal_input" type="text" name="disease3" id="disease_name3" placeholder="Enter Disease"> 
				<input class="modal_input" type="text" name="dosage3" id="dosage_name3" placeholder="Enter Dosage">
				<input class="modal_input" type="text" name="method_app3" id="method_app3" placeholder="Enter Method of application">
				<input class="modal_input" type="text" name="quarantine3" id="quarantine3" placeholder="Enter Quarantine period">
				<a class="agri-add-data" id="add-data3">Add Data</a>

			</div>
		</div>
		<div id="my-content-id4" style="display:none;">
			<div class="my-modal-1">
				<input type="hidden" name="table_id4" id="table_id4">
				<input class="modal_input" type="text" name="crop4" id="crop_name4" placeholder="Enter Crop">
				<input class="modal_input" type="text" name="dosage4" id="dosage_name4" placeholder="Enter Dosage">
				<input class="modal_input" type="text" name="disease4" id="disease_name4" placeholder="Enter Disease">
				<input class="modal_input" type="text" name="method_app4" id="method_app4" placeholder="Enter Method of application">
				<input class="modal_input" type="text" name="quarantine4" id="quarantine4" placeholder="Enter Quarantine period">
				<a class="agri-add-data" id="update-data4">Update Data</a>

			</div>
		</div>
		<table class="wp-list-table widefat fixed striped" width="100%" >
			<tr style="background-color:#2EA2CC;color:#FFF;height:35px;">
				<th>Crop</th>
				<th>Diseases</th>
				<th>Dosage</th>
				<th>Method App</th>
				<th>Quarantine Period</th>
				<th>Action</th>
			</tr>
			<?php foreach ($get_datatable as $key => $data_table) {?>
				<tr>
					<?php $crop=get_term_by('id', $data_table->crop_id, 'crop'); ?>
					<?php $disease=get_term_by('id', $data_table->activity_id, 'activity'); ?>
					<td class="crop4"><?php echo $crop->name;?></td>
					<td class="disease4"><?php echo $disease->name;?></td>
					<td class="dosage4"><?php echo $data_table->dosage;?></td>
					<td class="method_app4"><?php echo $data_table->method_app;?></td>
					<td class="quarantine4"><?php echo $data_table->quarantine;?></td>
					<td>
						<input type="hidden" name="data-table-id"class ="data-table-id" value="<?php echo $data_table->id; ?>" />
						<a class="delete-registration button button-danger button-large delete-product ">Delete</a>
						<a href="#TB_inline?width=400&height=300&inlineId=my-content-id4" class="delete-registration button button-danger button-large thickbox  update4">Update</a>
					</td>
				</tr>
				<?php } ?>
				<?php 
				$args = array(
					'post_type' => 'ingredient',
					'post_status' => 'publish',
					'posts_per_page' => -1
				);              
				$the_query = new WP_Query( $args ); ?>


			</table>
			<div class="form-group dynamic-element" style="display:none">
				<div class="row">
					<div class="col-md-2"></div>

					<!-- Replace these fields -->
					<div class="col-md-4">
						<label class="ingred">Select Ingredient</label>
						<select id="profesor" name="ingredients[]" class="form-control">
							<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
								<option value="<?php echo(get_the_ID()) ?>"><?php echo get_the_title() ?></option>
							<?php endwhile; ?>
						</select>
					</div>
					<div class="col-md-3">
						<!-- <label>Enter Compostion</label> -->
						<input class="form-control" type="text" name="compostion[]" placeholder="Enter Compostion">
					</div>
					<!-- End of fields-->
					<div class="col-md-1">
						<p class="delete">x</p>
					</div>
				</div>
			</div>
			<!-- END OF HIDDEN ELEMENT -->

			<div class="form-container">
				<!-- <form method="get" action="" class="form-horizontal"> -->
					<fieldset>
						<!-- Form Name -->
						<div class="dynamic-stuff">
							<!-- Dynamic element will be cloned here -->
							<!-- You can call clone function once if you want it to show it a first element-->
						</div>

						<!-- Button -->
						<div class="form-group">
							<div class="row">
								<div class="col-md-12">
									<p class="add-one">Add Data</p>
								</div>
								<div class="col-md-5"></div>
								<div class="col-md-6">
									<button id="singlebutton" name="singlebutton" class="btn btn-primary">Save Data</button>
								</div>
							</div>
						</div>
					</fieldset>
					<!-- </form> -->	
				</div>
				<div class="form-group1 dynamic-element1" style="display:none">
					<div class="row">
						<div class="col-md-2"></div>

						<!-- Replace these fields -->
						<div class="col-md-4">
							<label>Select Crop</label>
							<select id="crops" name="crops[]" class="form-control">
								<?php 
								$crops = get_terms( 'crop', array(
									'orderby'    => 'count',
									'hide_empty' => 0,
								) );
								foreach ($crops as $key => $crop) { ?>
									<option value="<?php echo($crop->term_id); ?>"><?php echo $crop->name; ?></option>
									<?php } ?>
								</select>
								<label>Select Activity</label>
								<select id="activity" name="activities[]" class="form-control">
									<?php 
									$activities = get_terms( 'activity', array(
										'orderby'    => 'count',
										'hide_empty' => 0,
									) );
									foreach ($activities as $key => $activity) { ?>
										<option value="<?php echo($activity->term_id); ?>"><?php echo $activity->name; ?></option>
										<?php }
										?>
									</select>
								</div>
								<div class="col-md-3">
									<label>Enter Dosage</label>
									<input class="form-control" type="text" name="dosage[]" style="width:150%;">
									<label>Enter Method Of Application</label>
									<input class="form-control" type="text" name="method_app[]"  style="width:150%;">
									<label>Enter Quarantine Period</label>
									<input class="form-control" type="text" name="quarantine[]"  style="width:150%;">
								</div>
								<!-- End of fields-->
								<div class="col-md-1">
									<p class="delete1">x</p>
								</div>
							</div>
						</div>
						<!-- END OF HIDDEN ELEMENT -->

						<div class="form-container">
							<fieldset>
								<!-- Form Name -->
								<legend class="title">Add Crops Data</legend>

								<div class="dynamic-stuff-1">
								</div>

								<!-- Button -->
								<div class="form-group1">
									<div class="row">
										<div class="col-md-12">
											<p class="add-one-crop">Add Crop</p>
										</div>
										<div class="col-md-5"></div>
										<div class="col-md-6">
											<button id="singlebutton1" name="singlebutton1" class="btn btn-primary">Save Crop Data</button>
										</div>
									</div>
								</div>
							</fieldset>
						</div>
						<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>



						<script  src="js/index.js"></script>

						<?php
					}

					function wpdocs_save_meta_box1( $post_id ) {
    // Save logic goes here. Don't forget to include nonce checks!
					}
					add_action( 'save_post', 'wpdocs_save_meta_box1' );



/**
 * Register meta box(es).
 */
function wpdocs_register_meta_boxes() {
	add_meta_box( 'meta-box-id', __( 'Pesticide DataTable', 'textdomain' ), 'wpdocs_my_display_callback', 'ingredient' );
}
add_action( 'add_meta_boxes', 'wpdocs_register_meta_boxes' );

/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function wpdocs_my_display_callback( $post ) {
    // Display code/markup goes here. Don't forget to include nonces!
	global $wpdb;
	$custom_field = get_post_meta($post->ID,'chemical_group', true);
	$get_datatable = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}datatable_pesticide WHERE post_id= $post->ID");
	?>
	<script>
		jQuery( document ).ready(function( $ ) {
			jQuery(".delete1").click(function () {
				var table_id = $('.table_id').val();     
				console.log(table_id);
				jQuery.ajax({
					url: ajaxurl,
					type: 'post',
					data: {
						action   :'delete_datatable',
						table_id : table_id,
					},
					success: function (response) {
						console.log("Successfully Deleted");
						location.reload();
					}
				});

			});
			jQuery("#add_data").click(function () {
				var post_id 		= $('#post_id').val();
				var product_name 	= $('#product_name1').val();
				var content 		= $('#content1').val();
				var manufacturer 	= $('#manufacturer1').val();
				var country      	= $('#country1').val();
				var crop         	= $('#crop_name').val();
				var dosage 			= $('#dosage_name').val();
				var disease 		= $('#disease_name').val();
				var method_app 		= $('#method_app1').val();
				var quarantine 		= $('#quarantine').val(); 
				console.log (quarantine);  

				jQuery.ajax({
					url: ajaxurl,
					type: 'post',
					data: {
						action   		:'add_datatable',
						post_id 		: post_id,
						content 		: content,
						product_name	: product_name,
						manufacturer  	: manufacturer,
						country 	 	: country,
						crop 			: crop,
						dosage 			: dosage,
						disease 		: disease,
						method_app 		: method_app,
						quarantine 		: quarantine,
					},
					success: function (response) {
						console.log("Successfully Added");
						tb_remove();

					}
				});

			});
			jQuery(".update1").click(function () {
				var table_id = $('.table_id').val();
				var product_name = $('.product_name').html();
				var content = $('.content').html();
				var manufacturer = $('.manufacturer').html();
				var country = $('.country').html();
				var crop = $('.crop').html();
				var dosage = $('.dosage').html();
				var disease = $('.disease').html();
				var method_app = $('.method_app').html();
				var quarantine = $('.quarantine_period').html();
				console.log(table_id);
				$('.table_id1').val(table_id);
				$('#product_name2').val(product_name);
				$('#content2').val(content);
				$('#manufacturer2').val(manufacturer);
				$('#country2').val(country);
				$('#crop_name2').val(crop);
				$('#dosage_name2').val(dosage);
				$('#disease_name2').val(disease);
				$('#method_app2').val(method_app);
				$('#quarantine2').val(quarantine);

			});
			jQuery("#update_data").click(function () {
				var table_id = $('.table_id1').val(); 
				var post_id 		= $('#post_id2').val();
				var product_name 	= $('#product_name2').val();
				var content 		= $('#content2').val();
				var manufacturer 	= $('#manufacturer2').val();
				var country      	= $('#country2').val();
				var crop         	= $('#crop_name2').val();
				var dosage 			= $('#dosage_name2').val();
				var disease 		= $('#disease_name2').val();
				var method_app 		= $('#method_app2').val();
				var quarantine 		= $('#quarantine2').val(); 
				console.log (table_id);  

				jQuery.ajax({
					url: ajaxurl,
					type: 'post',
					data: {
						action   		:'update_datatable',
						table_id 		: table_id,
						post_id 		: post_id,
						content 		: content,
						product_name	: product_name,
						manufacturer  	: manufacturer,
						country 	 	: country,
						crop 			: crop,
						dosage 			: dosage,
						disease 		: disease,
						method_app 		: method_app,
						quarantine 		: quarantine,
					},
					success: function (response) {
						console.log("Successfully updated");
						location.reload();
						tb_remove();

					}
				});

			});
		});
	</script>
	<?php add_thickbox(); ?>
	<div id="my-content-id" style="display:none;">
		<div class="my-modal-1">
			<input type="hidden" name="post_id" id="post_id" value="<?php echo $post->ID; ?>">
			<input type="hidden" name="table_id1" class="table_id1" value="<?php echo $post->ID; ?>">
			<input class="modal_input" type="text" name="product_name" id="product_name1" placeholder="Enter Product Name" >
			<input class="modal_input" type="text" name="content" id="content1" placeholder="Enter Content">
			<input class="modal_input" type="text" name="manufacturer" id="manufacturer1" placeholder="Enter Manufacturer">
			<input class="modal_input" type="text" name="country" id="country1" placeholder="Enter Country">
			<input class="modal_input" type="text" name="crop" id="crop_name" placeholder="Enter Crop">
			<input class="modal_input" type="text" name="dosage" id="dosage_name" placeholder="Enter Dosage">
			<input class="modal_input" type="text" name="disease" id="disease_name" placeholder="Enter Disease">
			<input class="modal_input" type="text" name="method_app" id="method_app1" placeholder="Enter Method of application">
			<input class="modal_input" type="text" name="quarantine" id="quarantine" placeholder="Enter Quarantine period">
			<a class="agri-add-data" id="add_data">Add Data</a>

		</div>
	</div>
	<div id="my-content-id1" style="display:none;">
		<div class="my-modal-1">
			<input type="hidden" name="post_id" id="post_id2" value="<?php echo $post->ID; ?>">
			<input class="modal_input" type="text" name="product_name" id="product_name2" placeholder="Enter Product Name" >
			<input class="modal_input" type="text" name="content" id="content2" placeholder="Enter Content">
			<input class="modal_input" type="text" name="manufacturer" id="manufacturer2" placeholder="Enter Manufacturer">
			<input class="modal_input" type="text" name="country" id="country2" placeholder="Enter Country">
			<input class="modal_input" type="text" name="crop" id="crop_name2" placeholder="Enter Crop">
			<input class="modal_input" type="text" name="dosage" id="dosage_name2" placeholder="Enter Dosage">
			<input class="modal_input" type="text" name="disease" id="disease_name2" placeholder="Enter Disease">
			<input class="modal_input" type="text" name="method_app" id="method_app2" placeholder="Enter Method of application">
			<input class="modal_input" type="text" name="quarantine" id="quarantine2" placeholder="Enter Quarantine period">
			<a class="agri-add-data" id="update_data">Update Data</a>

		</div>
	</div>
	<table class="wp-list-table widefat fixed striped" width="100%">
		<tr style="background-color:#2EA2CC;color:#FFF;height:35px;">
			<th><?php _e('Active Ingredient','agri');?></th>
			<th><?php _e('Chemical group of active ingredient','agri');?></th>
			<th><?php _e('Product (Trade Name)','agri');?></th>
			<th><?php _e('Content','agri');?></th>
			<th><?php _e('Manufacturer','agri');?></th>
			<th><?php _e('Country','agri');?></th>
			<th><?php _e('Crop','agri');?></th>
			<th><?php _e('Dosage','agri');?></th>
			<th><?php _e('Diseases','agri');?></th>
			<th><?php _e('Method of application','agri');?></th>
			<th><?php _e('Quarantine period','agri');?></th>
			<th><?php _e('Action','agri');?></th>

		</tr>
		<?php
		$rowspan = count( $get_datatable );
		$counter = 0;
		?>
		<?php foreach($get_datatable as $data_tables){?>
			<tr style="color:#000;">
				<input type="hidden" name="table_id" class ="table_id" value="<?php echo $data_tables->id; ?>">
				<?php if ($counter == 0): $counter++?>
					<td rowspan="<?php echo $rowspan; ?>"><?php echo get_the_title(); ?></td>
					<td rowspan="<?php echo $rowspan; ?>"><?php echo get_post_meta( get_the_ID(),'chemical_group', true); ?></td> 
				<?php endif; ?>
				<td id="product_name " class="product_name"><?php echo $data_tables->product_name; ?></td>
				<td id="content" class="content"><?php echo $data_tables->content; ?></td>
				<td id="manufacturer" class="manufacturer"><?php echo $data_tables->manufacturer; ?></td>
				<td id="country" class="country"><?php echo $data_tables->country; ?></td>
				<td id="crop" class="crop"><?php echo $data_tables->crop; ?></td>
				<td id="dosage" class="dosage"><?php echo $data_tables->dosage; ?></td>
				<td id="disease" class="disease"><?php echo $data_tables->diseases; ?></td>
				<td id="method_app" class="method_app"><?php echo $data_tables->method_app; ?></td>
				<td id="quarantine_period" class="quarantine_period"><?php echo $data_tables->quarantine_period; ?></td>
				<td><a class="delete-registration button button-danger button-large delete1" >Delete</a>
					<a href="#TB_inline?width=400&height=300&inlineId=my-content-id1" class="delete-registration button button-danger button-large thickbox  update1" >Update</a>
				</td>
			</tr>
			<?php }?>
		</table>
		<a href="#TB_inline?width=400&height=300&inlineId=my-content-id" class="thickbox" id="add_data">Add Data</a>
		<?php
	}

/**
 * Save meta box content.
 *
 * @param int $post_id Post ID
 */
function wpdocs_save_meta_box( $post_id ) {
    // Save logic goes here. Don't forget to include nonce checks!
}
add_action( 'save_post', 'wpdocs_save_meta_box' );
//ajax call for deleting the row from table
add_action('wp_ajax_delete_datatable','delete_datatable_row');
function delete_datatable_row(){
	global $wpdb;
	$table_id = $_POST['table_id'];
	$wpdb->delete( $wpdb->prefix . 'datatable_pesticide', array( 'id' => $table_id ) );
	die;
}
add_action('wp_ajax_delete_datatable1','delete_datatable_row1');
function delete_datatable_row1(){
	global $wpdb;
	$table_id = $_POST['table_id1'];
	$wpdb->delete( $wpdb->prefix . 'crop_data', array( 'id' => $table_id ) );
	die;
}
add_action('wp_ajax_add_datatable','add_datatable_row');
function add_datatable_row(){
	global $wpdb;
	$post_id = $_POST['post_id'];
	$content = $_POST['content'];
	$product_name = $_POST['product_name'];
	$manufacturer = $_POST['manufacturer'];
	$country = $_POST['country'];
	$crop = $_POST['crop'];
	$dosage = $_POST['dosage'];
	$disease = $_POST['disease'];
	$method_app = $_POST['method_app'];
	$quarantine = $_POST['quarantine'];
	$id = $wpdb->insert(
		$wpdb->prefix . 'datatable_pesticide',
		array(
			'post_id'           => $post_id,
			'content'           => $content,
			'product_name'      => $product_name,
			'manufacturer'      => $manufacturer,
			'country'           => $country,
			'crop'              => $crop,
			'dosage'            => $dosage,
			'diseases'          => $disease,
			'method_app'        => $method_app,
			'quarantine_period' => $quarantine,
		),
		array(
			'%d', '%s', '%s', '%s', '%s', '%s', '%s','%s','%s','%s',
		)
	);
	return $id;
}
add_action('wp_ajax_update_datatable','update_datatable_row');
function update_datatable_row(){
	global $wpdb;
	$table_id = $_POST['table_id'];
	$post_id = $_POST['post_id'];
	$content = $_POST['content'];
	$product_name = $_POST['product_name'];
	$manufacturer = $_POST['manufacturer'];
	$country = $_POST['country'];
	$crop = $_POST['crop'];
	$dosage = $_POST['dosage'];
	$disease = $_POST['disease'];
	$method_app = $_POST['method_app'];
	$quarantine = $_POST['quarantine'];
	$id = $wpdb->update(
		$wpdb->prefix . 'datatable_pesticide',
		array(
			'post_id'           => $post_id,
			'content'           => $content,
			'product_name'      => $product_name,
			'manufacturer'      => $manufacturer,
			'country'           => $country,
			'crop'              => $crop,
			'dosage'            => $dosage,
			'diseases'          => $disease,
			'method_app'        => $method_app,
			'quarantine_period' => $quarantine,
		),
		array(
			'id'=>$table_id,
		)
	);
	return $id;
}
add_action('wp_ajax_add_product_data1','add_product_data');
function add_product_data(){
	global $wpdb;
	$post_id=$_POST['post_id'];
	$active_ingredient=$_POST['active_ingredient'];
	$ingredient_category=$_POST['ingredient_category'];
	$product_name=$_POST['product_name'];
	$content=$_POST['content'];
	$manufacturer=$_POST['manufacturer'];
	$country=$_POST['country'];
	$chemical_group=$_POST['chemical_group'];
	$crop=$_POST['crop'];
	$dosage=$_POST['dosage'];
	$disease=$_POST['disease'];
	$method_app=$_POST['method_app'];
	$quarantine_period=$_POST['quarantine_period'];
	$return = $wpdb->query( $wpdb->prepare( 
		"
		INSERT INTO {$wpdb->prefix}datatable_pesticide
		( user_id,post_id,ingredient_category,active_ingredient,chemical_group,product_name, crop, dosage,diseases,method_app,quarantine_period,content,manufacturer,country,status )
		VALUES ( %d, %d, %s, %s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%d)
		", 
		1,
		$post_id,
		$ingredient_category,
		$active_ingredient,
		$chemical_group,
		$product_name,
		$crop, 
		$dosage,
		$disease,
		$method_app,
		$quarantine_period,
		$content,
		$country,
		$manufacturer,
		1 
	) );
	die();

}
function product_data_add(){
	global $wpdb;
	$current_user = wp_get_current_user();
	$role=$_POST['role'];
	$user_id=$_POST['user_id'];
	$product_name=$_POST['product_name'];
	$active_ingredient=$_POST['active_ingredient'];
	$chemical_group= $_POST['chemical_group'];
	$content=$_POST['content'];
	$country=$_POST['country'];
	$manufacturer=$_POST['manufacturer'];
	if(isset($_POST['submit'])){
		if($role=='administrator'){
			$my_post = array(
				'post_title'    => $product_name,
				'post_type'     => 'product',
				'post_status'   => 'publish',
				'post_author'   => 1,
				'post_category' => array( 8,39 )
			);
			$post_id = wp_insert_post( $my_post);
			add_post_meta($post_id, 'active_ingredient', $active_ingredient, 1);
			add_post_meta($post_id, 'chemical_group', $chemical_group, 1);
			add_post_meta($post_id, 'content', $content, 1);
			add_post_meta($post_id, 'country', $country, 1);
			add_post_meta($post_id, 'manufacturer', $manufacturer, 1);
			echo "<h1>Your Data Is Added And ready to view</h1>";
		}
	}
	else{
		$my_post = array(
			'post_title'    => $product_name,
			'post_type'     => 'product',
			'post_status'   => 'draft',
			'post_author'   => 1,
			'post_category' => array( 8,39 )
		);
		$post_id = wp_insert_post( $my_post);
		$name = $current_user->display_name;
		add_post_meta($post_id, 'active_ingredient', $active_ingredient, 1);
		add_post_meta($post_id, 'chemical_group', $chemical_group, 1);
		add_post_meta($post_id, 'content', $content, 1);
		add_post_meta($post_id, 'country', $country, 1);
		add_post_meta($post_id, 'manufacturer', $manufacturer, 1);
		add_post_meta($post_id, 'added by', $name, 1);
	}
}
add_shortcode('product-data-add','product_data_add');
function show_my_data(){
	global $wpdb;
	$current_user= wp_get_current_user();
	if($current_user->ID==0){
		echo"<h1>Please Login To see Your Added Data";
	}
	else{
		$get_data=$wpdb->get_results("SELECT * FROM {$wpdb->prefix}datatable_pesticide where
		user_id=$current_user->ID"); ?>
		<table>
			<thead>
				<tr>
					<th>Active Ingredient</th>
					<th>Chemical Group</th>
					<th>Product Name</th>
					<th>Content</th>
					<th>Manufacturer</th>
					<th>Country</th>
					<th>Crop</th>
					<th>Dosage</th>
					<th>Disease</th>
					<th>Method of Application</th>
					<th>Quarantine Period</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($get_data as $key => $your_data) { ?>
					<tr>
						<td><?php echo get_post_meta($your_data->post_id, 'active_ingredient', true); ?></td>
						<td><?php echo get_post_meta($your_data->post_id, 'chemical_group', true); ?></td>
						<td><?php echo get_the_title($your_data->post_id); ?></td>
						<td><?php echo $your_data->content; ?></td>
						<td><?php echo $your_data->manufacturer; ?></td>
						<td><?php echo $your_data->country; ?></td>
						<td><?php echo $your_data->crop; ?></td>
						<td><?php echo $your_data->dosage; ?></td>
						<td><?php echo $your_data->diseases; ?></td>
						<td><?php echo $your_data->method_app; ?></td>
						<td><?php echo $your_data->quarantine_period; ?></td>
						<td><?php echo ($your_data->status==1?"Approved":"Pending"); ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php }
		}
		add_shortcode('show-my-data','show_my_data');
		add_action('wp_ajax_update_product_datatable','update_product_datatable');
		function update_product_datatable(){
			global $wpdb;
			$table_id=$_POST['table_id'];
			$crop=$_POST['crop'];
			$dosage = $_POST['dosage'];
			$disease=$_POST['disease'];
			$method_app=$_POST['method_app'];
			$quarantine_period=$_POST['quarantine'];
			$id = $wpdb->update(
				$wpdb->prefix . 'datatable_pesticide',
				array(
					'crop'              => $crop,
					'dosage'            => $dosage,
					'diseases'          => $disease,
					'method_app'        => $method_app,
					'quarantine_period' => $quarantine_period,
				),
				array(
					'id'=>$table_id,
				)
			);
			return $id;
		}

		add_shortcode('add-crop','add_crop');
		function add_crop(){
			global $wpdb;
			$products= $_POST['products']; ?>
			<form method="post" action="./add-crop-data">
				<label>Select Product</label>
				<select name="products">
					<?php foreach ($products as $key => $product) {?>
						<option value="<?php echo $product; ?>"><?php echo get_the_title($product); ?></option>
						<?php } ?>
					</select>
					<input type="text" name="crop" placeholder="Enter Crop">
					<input type="text" name="dosage" placeholder="Enter Dosage">
					<input type="text" name="disease" placeholder="Enter Disease">
					<input type="text" name="method_app" placeholder="Enter Method Of Application">
					<input type="text" name="quarantine" placeholder="Enter Quarantine Period">
					<input type="submit" name="add-crop" value="Add Product Info">
				</form>
				<?php
			}
			add_shortcode('add-crop-data','add_crop_data');
			function add_crop_data(){
				global $wpdb;
				$current_user = wp_get_current_user();
				$post=$_POST['products'];
				$crop=$_POST['crop'];
				$dosage=$_POST['dosage'];
				$diseases=$_POST['disease'];
				$method_app=$_POST['method_app'];
				$quarantine=$_POST['quarantine'];
				if(isset($_POST['add-crop'])){
					if($current_user->roles[0]=='administrator'){
						$return=$wpdb->query( $wpdb->prepare( 
							"
							INSERT INTO {$wpdb->prefix}datatable_pesticide
							( post_id,user_id, crop, dosage,diseases,method_app,quarantine_period,status )
							VALUES ( %d,%d, %s, %s,%s,%s,%s,%d)
							", 
							$post,
							$current_user->ID, 
							$crop, 
							$dosage,
							$diseases,
							$method_app,
							$quarantine,
							1 
						) );
						echo"Your Data Is Saved And Ready To See";

					}
					else{
						$return=$wpdb->query( $wpdb->prepare( 
							"
							INSERT INTO {$wpdb->prefix}datatable_pesticide
							( post_id,user_id, crop, dosage,diseases,method_app,quarantine_period,status )
							VALUES ( %d,%d, %s, %s,%s,%s,%s,%d)
							", 
							$post,
							$current_user->ID, 
							$crop, 
							$dosage,
							$diseases,
							$method_app,
							$quarantine,
							0 
						) );
						echo"Your Data Is Saved In Pending List";
					}
				}

			}
//taxonomy for ingredients
			function wpdocs_register_private_taxonomy_ingredient() {
				$chemical = array(
					'label'        => __( 'Chemical Group', 'textdomain' ),
					'public'       => true,
					'rewrite'      => false,
					'hierarchical' => true
				);
				$ingredient = array(
					'label'        => __( 'Ingredient Category', 'textdomain' ),
					'public'       => true,
					'rewrite'      => false,
					'hierarchical' => true
				);

				register_taxonomy( 'chemical_group', 'ingredient', $chemical );
				register_taxonomy( 'ingredient_category', 'ingredient', $ingredient );
			}
			add_action( 'init', 'wpdocs_register_private_taxonomy_ingredient', 0 );
//taxonomy for ingredients ends here..
// taxonomy for products
			function wpdocs_register_private_taxonomy_product() {
				$country = array(
					'label'        => __( 'Countries', 'textdomain' ),
					'public'       => true,
					'rewrite'      => false,
					'hierarchical' => true
				);
				$manufacturer = array(
					'label'        => __( 'Manufacturer', 'textdomain' ),
					'public'       => true,
					'rewrite'      => false,
					'hierarchical' => true
				);
				$crop = array(
					'label'                      => __( 'Crops', 'textdomain' ),
					'show_ui'                    => true,
					'show_in_quick_edit'         => false,
					'meta_box_cb'                => false,
					'public'                     => false,
					'rewrite'                    => false,
					'hierarchical'               => true
				);
				$activity = array(
					'label'        => __( 'Activity', 'textdomain' ),
					'show_ui'                    => true,
					'show_in_quick_edit'         => false,
					'meta_box_cb'                => false,
					'public'                     => false,
					'rewrite'                    => false,
					'hierarchical'               => true
				);

				register_taxonomy( 'country', 'product', $country );
				register_taxonomy( 'manufacturer', 'product', $manufacturer );
				register_taxonomy( 'crop', 'product', $crop );
				register_taxonomy( 'activity', 'product', $activity );
			}
			add_action( 'init', 'wpdocs_register_private_taxonomy_product', 0 );
			function check_form_value(){
				global $wpdb;
				$value=$_POST;
				if (isset($_POST['ingredients'])) {
					$compostion= $value['compostion'];
					$ingredients=$value['ingredients'];
					foreach ($ingredients as $key => $ingredient) {
						if($ingredient!=''&& $compostion[$key]!=''){
							$wpdb->insert(
								$wpdb->prefix . 'product_ingredient',
								array(
									'product_id'        => $value['post_ID'],
									'ingredient_id'     => $ingredient,
									'content'           => $compostion[$key],
									'user_id'           => 1,
									'status'            => 1,
								),
								array(
									'%d', '%d', '%s', '%d', '%d',
								)
							);
						}
					}

				}
				if (isset($_POST['crops'])) {
					$crops= $value['crops'];
					$activity=$value['activities'];
					$dosage=$value['dosage'];
					$method_app = $value['method_app'];
					$quarantine = $value['quarantine'];
					foreach ($crops as $key => $crop) {
						if($crop!=''&& $activity[$key]!=''&&$key!=0){
							$wpdb->insert(
								$wpdb->prefix . 'crop_data',
								array(
									'product_id'        => $value['post_ID'],
									'activity_id'       =>$activity[$key],
									'crop_id'        	=> $crop,
									'dosage'			=> $dosage[$key],
									'method_app'		=> $method_app[$key],
									'quarantine'        => $quarantine[$key],
									'user_id'           => 1,
									'status'            => 1,
								),
								array(
									'%d', '%d','%d', '%s', '%s','%s','%d', '%d',
								)
							);
						}
					}

				}
			}
			add_action('init','check_form_value');
			?>