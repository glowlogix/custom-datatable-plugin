<?php
/**
 * Register meta box(es) for Product post type.
 */
function wpdocs_register_meta_boxes1() {
	add_meta_box( 'meta-box-product', __( 'Product Ingredients', 'textdomain' ), 'product_datatable', 'product' );
	add_meta_box( 'meta-box-crop', __( 'Product Crops', 'textdomain' ), 'crop_datatable', 'product' );
}
add_action( 'add_meta_boxes', 'wpdocs_register_meta_boxes1' );

function product_datatable($post)
{
	global $wpdb;
	$get_ingredients= $wpdb->get_results("SELECT * FROM {$wpdb->prefix}product_ingredient WHERE product_id=$post->ID"); ?>
	<!-- jQuery To Remove the Product Ingredient -->
	<script>
		jQuery( document ).ready(function( $ ) {
			$('.delete_ingredient').click(function(){
				var table_id = $( this ).prev().val();
				console.log(table_id);
				jQuery.ajax({
					url: ajaxurl,
					type: 'post',
					data: {
						action   :'delete_product_ingredient',
						table_id : table_id,
					},
					success: function (response) {
						console.log("Successfully Deleted");
						location.reload();
					}
				});
			});
			$('.update_ingredient').click(function(){
				$('.update_ingredient').hide();
				$('.delete_ingredient').hide();
				var table_id = $(this).siblings('.table_id').val();
				console.log(table_id);
				var ingredient = $(this).parent().siblings(".ingredient").html();
				var composition = $(this).parent().siblings(".composition").html();

                var elem = $('<select id="ingredient" type="text">');
                $.getJSON('http://agrinomia.com/wp-json/wp/v2/ingredient?per_page=100&orderby=title&order=asc', function (data) {

                    var length = data.length;
                    for (var i = 0; i < length; i++) {
                        row = data[i],
                            selected = (row['title']['rendered'] == ingredient) ? 'selected="selected"' : '';
                        elem.append('<option value="' + row['id'] +'" '+ selected +'>' + row['title']['rendered'] + '</option>');
                    }
                });
                $(this).parent().siblings(".ingredient").html( elem );


                $(this).parent().siblings(".composition").html('<textarea id='+'composition' +'>' + composition + '</textarea>');
				$(this).siblings(".save_ingredient").show();
			  	$(this).siblings(".cancel_ingredient").show();
			  	$('.save_ingredient').click(function(){
			  		var ingredient = $('#ingredient').val();
			  		var composition = $('#composition').val();
			  		console.log(composition);
			  		$.ajax({
						url: ajaxurl,
						type: 'post',
						data: {
							action   :'update_ingredient',
							table_id : table_id,
							ingredient : ingredient,
							composition : composition,
						},
						success: function (response) {
							console.log("Successfully Updated");
							location.reload();
						}
					});

			  	});
			  	$('.cancel_ingredient').click(function(){
			  		location.reload();
			  	});

			});
		});		
	</script>
	<!-- jQuery To Remove the Product Ingredient Ends Here -->
	<table class="wp-list-table widefat fixed striped" width="100%" >
        <tr style="background-color:#2EA2CC;color:#FFF;height:35px;">
            <th><?php _e('Active Ingredient','agri');?></th>
            <th><?php _e('Composition','agri');?></th>
            <th><?php _e('Action','agri');?></th>
        </tr>
        <?php foreach ($get_ingredients as $key => $table) { ?>
        <tr>
            <td class="ingredient"><?php echo (get_the_title($table->ingredient_id)); ?></td>
            <td class="composition"><?php echo ($table->content); ?></td>
            <td><input type="hidden" class="table_id" value="<?php echo($table->id) ?>"><a class="delete-registration button button-danger button-large delete_ingredient"><?php _e('Delete','agri');?></a><a class="delete-registration button button-danger button-large update_ingredient"><?php _e('Update','agri');?></a><a class="delete-registration button button-danger button-large save_ingredient" style="display:none;"><?php _e('Save','agri');?></a><a class="delete-registration button button-danger button-large cancel_ingredient" style="display:none;"><?php _e('Cancel','agri');?></a></td>
        </tr>
        <?php wp_reset_postdata(); } ?>
    </table>
    <div class="form-container">
        <!-- Form Name -->
        <div class="dynamic-stuff">
            <!-- Dynamic element will be cloned here -->
            <!-- You can call clone function once if you want it to show it a first element-->
        </div>
        <!-- Button -->
        <div class="form-group" style="background: white;">
            <p class="add-one" style="display: inline-block; margin-right: 10px;"><?php _e('Add Data','agri');?></p>
            <button id="singlebutton" name="singlebutton" class="btn btn-primary" style="width: auto;"><?php _e('Save Data','agri');?></button>
        </div>
    </div>
<?php }

function crop_datatable( $post ) {
	global $wpdb;
	$product_name =get_the_title();
	$product_id = get_the_ID(); 
	$get_datatable = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}crop_data WHERE product_id= '$product_id'");
	?>
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
			$('.add-one').click(function(){
				$('.dynamic-element').first().clone().appendTo('.dynamic-stuff').show();
				attach_delete();
			});
            var i = 1;
			$('.add-one-crop').click(function(){
                $("#activity").attr('name', 'activities['+i+'][]');
				$('.dynamic-element1').first().clone().appendTo('.dynamic-stuff-1').show();
				attach_delete1();
                i++;
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
            $('.update4').click(function(){
			  $('.update4').hide();
			  $('.delete-product').hide();
			  	var table_id = $(this).next().val();
			  	var crop = $(this).parent().siblings(".crop4").html();
			  	var desease = $(this).parent().siblings(".disease4").html();
			  	var dosage = $(this).parent().siblings(".dosage4").html();
			  	var method_app = $(this).parent().siblings(".method_app4").html();
			  	var quarantine = $(this).parent().siblings(".quarantine4").html();
			  	/*select for option*/

                var elem = $('<select id="crop_id" type="text">');
                $.getJSON('http://agrinomia.com/wp-json/wp/v2/all-terms?term=crop', function (data) {

                    var length = data.length;
                    for (var i = 0; i < length; i++) {
                        row = data[i],
                        selected = (row['name'] == crop) ? 'selected="selected"' : '';
                        elem.append('<option value="' + row['term_id'] +'" '+ selected +'>' + row['name'] + '</option>');
                    }
                });
                $(this).parent().siblings(".crop4").html( elem );

                var activity_elem = $('<select id="activity_id" type="text">');
                $.getJSON('http://agrinomia.com/wp-json/wp/v2/all-terms?term=activity', function (data) {

                    var length = data.length;
                    for (var i = 0; i < length; i++) {
                        row = data[i],
                            selected = (row['name'] == desease) ? 'selected="selected"' : '';
                        activity_elem.append('<option value="' + row['term_id'] +'" '+ selected +'>' + row['name'] + '</option>');
                    }
                });
			    $(this).parent().siblings(".disease4").html(activity_elem);

			    $(this).parent().siblings(".dosage4").html('<textarea id='+'dosage' +'>' + dosage + '</textarea>'); 
			    $(this).parent().siblings(".method_app4").html('<textarea id='+'method_app' +'>'+ method_app + '</textarea>'); 
			    $(this).parent().siblings(".quarantine4").html('<textarea id='+'quarantine' +'>'+ quarantine + '</textarea>');  
			  	console.log(table_id);
			  	$(this).siblings(".save_crop").show();
			  	$(this).siblings(".cancel_update").show();
			  	jQuery(".cancel_update").click(function () {
			  		location.reload();
			  	});

			  	jQuery(".save_crop").click(function () {
			  		var crop_data = $('#crop_id').val();
			  		var disease = $('#activity_id').val();
			  		var dosage = $('#dosage').val();
			  		var method_app = $('#method_app').val();
			  		var quarantine = $('#quarantine').val();
			  		$.ajax({
					url: ajaxurl,
					type: 'post',
					data: {
						action   :'update_crop_data',
						table_id : table_id,
						crop_data : crop_data,
						disease : disease,
						dosage : dosage,
						method_app : method_app,
						quarantine : quarantine,
					},
					success: function (response) {
						console.log("Successfully Updated");
						location.reload();
					}
				});

			  		});
			});

        });
</script>
    <?php
    $args = array(
        'post_type'     => 'ingredient',
        'post_status'   => 'publish',
        'posts_per_page'=> -1,
        'orderby'       => 'title',
        'order'         => 'ASC',
    );
    $the_query = new WP_Query( $args ); ?>
    <div class="form-group dynamic-element" style="display:none">
        <div class="row bcknd">
            <!-- Replace these fields -->
            <div class="col-md-5">
                <label class="ingred"><?php _e('Select Ingredient','agri');?></label><br>
                <select id="profesor" name="ingredients[]" class="form-control">
                    <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
                        <option value="<?php echo(get_the_ID()) ?>"><?php echo get_the_title() ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-5">
                <label><?php _e('Enter Compostion','agri');?></label><br>
                <input class="form-control" type="text" name="compostion[]">
            </div>
            <!-- End of fields-->
            <div class="col-md-2">
                <p class="delete1">x</p>
            </div>
        </div>
    </div>
    <!-- END OF HIDDEN ELEMENT -->
    <table class="wp-list-table widefat fixed striped" width="100%">
        <tr style="background-color:#2EA2CC;color:#FFF;height:35px;">
            <th><?php _e('Crop','agri');?></th>
            <th><?php _e('Diseases','agri');?></th>
            <th><?php _e('Dosage','agri');?></th>
            <th><?php _e('Method App','agri');?></th>
            <th><?php _e('Quarantine Period','agri');?></th>
            <th><?php _e('Action','agri');?></th>
        </tr>
        <?php foreach ($get_datatable as $key => $data_table) { ?>
            <tr>
                <?php $crop = get_term_by('id', $data_table->crop_id, 'crop'); ?>
                <?php $disease = get_term_by('id', $data_table->activity_id, 'activity'); ?>
                <td class="crop4"><?php echo $crop->name; ?></td>
                <td class="disease4"><?php echo $disease->name; ?></td>
                <td class="dosage4"><?php echo $data_table->dosage; ?></td>
                <td class="method_app4"><?php echo $data_table->method_app; ?></td>
                <td class="quarantine4"><?php echo $data_table->quarantine; ?></td>
                <td>
                    <a class="delete-registration button button-danger button-large update4"><?php _e('Update','agri');?></a>
                    <input type="hidden" name="data-table-id" class="data-table-id"
                           value="<?php echo $data_table->id; ?>"/>
                    <a class="delete-registration button button-danger button-large delete-product "><?php _e('Delete','agri');?></a>
                    <a class="delete-registration button button-danger button-large save_crop" style="display: none;"><?php _e(' Save','agri');?> </a>
                    <a class="delete-registration button button-danger button-large cancel_update" style="display: none;"> <?php _e('Cancel','agri');?> </a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <div class="form-group1 dynamic-element1" style="display:none">
        <div class="row scnd-crp">
            <!-- Replace these fields -->
            <script type='text/javascript'>
//                jQuery(document).ready(function ($) {
//                    $('#crops').select2();
//                });
            </script>
            <div class="col-md-5">
                <label><?php _e('Select Crop','agri');?></label>
                <br>
                <select id="crops" name="crops[]">
                    <?php
                    $crops = get_terms('crop', array(
                        'hide_empty' => 0,
                        'orderby' => 'name',
                        'order' => 'ASC'
                    ));
                    foreach ($crops as $key => $crop) { ?>
                        <option value="<?php echo($crop->term_id); ?>"><?php echo $crop->name; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-5">
                <label><?php _e('Select Activity','agri');?></label>
                <br>
                <select id="activity" multiple="multiple" name="activities[][]" style="height: 120px;" class="form-control">
                    <?php
                    $activities = get_terms('activity', array(
                        'orderby'   => 'name',
                        'order'     => 'ASC',
                        'hide_empty' => 0,
                    ));
                    foreach ($activities as $key => $activity) { ?>
                        <option value="<?php echo($activity->term_id); ?>"><?php echo $activity->name; ?></option>
                    <?php }
                    ?>
                </select>
            </div>
            <div class="col-md-2">
                <p class="delete1">x</p>
            </div>
        </div>

        <div class="scnd-crp1">
            <div class="col-md-4">
                <label><?php _e('Enter Dosage','agri');?></label><br>
                <input class="form-control" type="text" name="dosage[]">
            </div>
            <div class="col-md-4">
                <label><?php _e('Enter Method Of Application','agri');?></label><br>
                <input class="form-control" type="text" name="method_app[]">
            </div>
            <div class="col-md-4">
                <label><?php _e('Enter Quarantine Period','agri');?></label><br>
                <input class="form-control" type="text" name="quarantine[]">
            </div>
        </div>
        <!-- End of fields-->
    </div>
    <div class="form-container">
        <fieldset>
            <!-- Form Name -->
            <legend class="title "><h3><?php _e('Add Crops Data','agri');?></h3></legend>

            <div class="dynamic-stuff-1">
            </div>

            <!-- Button -->
            <div class="form-group1" style="background: white;">
                <p class="add-one-crop" style="display: inline-block; margin-right: 10px;"><?php _e('Add Crop','agri');?></p>
                <button id="singlebutton1" name="singlebutton1" class="btn btn-primary" style="width: auto;"><?php _e('Save Crop Data','agri');?></button>
            </div>
        </fieldset>
    </div>
    <!-- END OF HIDDEN ELEMENT -->
<!--    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>-->
<!--    <script src="js/index.js"></script>-->
    <?php
}

function wpdocs_save_meta_box1($post_id)
{
    // Save logic goes here. Don't forget to include nonce checks!
}

add_action('save_post', 'wpdocs_save_meta_box1');



/**
 * Register meta box(es).
 */
function wpdocs_register_meta_boxes() {
}
add_action( 'add_meta_boxes', 'wpdocs_register_meta_boxes' );

/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function wpdocs_my_display_callback( $post ) {
}
//Ajax call to delete the data from crop table
add_action('wp_ajax_delete_datatable1','delete_datatable_row1');
function delete_datatable_row1(){
	global $wpdb;
	$table_id = $_POST['table_id1'];
	$wpdb->delete( $wpdb->prefix . 'crop_data', array( 'id' => $table_id ) );
	die;
}

function check_form_value()
{
    global $wpdb;
    $value = $_POST;
    if (isset($_POST['ingredients'])) {
        $compostion = $value['compostion'];
        $ingredients = $value['ingredients'];
        foreach ($ingredients as $key => $ingredient) {
            if ($ingredient != '' && $compostion[$key] != '') {
                $wpdb->insert(
                    $wpdb->prefix . 'product_ingredient',
                    array(
                        'product_id' => $value['post_ID'],
                        'ingredient_id' => $ingredient,
                        'content' => $compostion[$key],
                        'user_id' => 1,
                        'status' => 1,
                    ),
                    array(
                        '%d', '%d', '%s', '%d', '%d',
                    )
                );
            }
        }

    }
    if (isset($_POST['crops'])) {
        $crops = $value['crops'];
        $activity = $value['activities'];
        $dosage = $value['dosage'];
        $method_app = $value['method_app'];
        $quarantine = $value['quarantine'];
        foreach ($crops as $key => $crop) {
            if ($crop != ''  && $key != 0) {
                $activities= $activity[$key];
                foreach ($activities as $key1 => $activity_elem) {
                    $wpdb->insert(
                        $wpdb->prefix . 'crop_data',
                        array(
                            'product_id' => $value['post_ID'],
                            'activity_id' => $activity_elem,
                            'crop_id' => $crop,
                            'dosage' => $dosage[$key],
                            'method_app' => $method_app[$key],
                            'quarantine' => $quarantine[$key],
                            'user_id' => 1,
                            'status' => 1,
                        ),
                        array(
                            '%d', '%d', '%d', '%s', '%s', '%s', '%d', '%d',
                        )
                    );
                }
            }
        }

    }
}

add_action('init', 'check_form_value');
//Ajax To Delete The Product Ingredient
add_action('wp_ajax_delete_product_ingredient','delete_product_ingredient');
function delete_product_ingredient(){
	global $wpdb;
	$table_id = $_POST['table_id'];
	$wpdb->delete( $wpdb->prefix . 'product_ingredient', array( 'id' => $table_id ) );
	die;
}
//Ajax To Delete The Product Ingredient ends here
//Ajax To update the crop data
add_action('wp_ajax_update_crop_data','update_crop_data');
function update_crop_data(){
	global $wpdb;
	$crop_data= $_POST['crop_data'];
	$activity_id = $_POST['disease'];
	$table_id = $_POST['table_id'];
	$dosage = $_POST['dosage'];
	$method_app = $_POST['method_app'];
	$quarantine = $_POST['quarantine'];

	$update_crop = $wpdb->update(
		$wpdb->prefix . 'crop_data',
		array(
			'crop_id' 			=> $crop_data,
			'activity_id'       => $activity_id,
			'dosage'            => $dosage,
			'method_app'        => $method_app,
			'quarantine'        => $quarantine,
		),
		array(
			'id'=>$table_id,
		)
	);
}
//Ajax To Update The Ingredient
add_action('wp_ajax_update_ingredient','update_ingredient');
function update_ingredient(){
	global $wpdb;
	$table_id = $_POST['table_id'];
	$ingredient = $_POST['ingredient'];
	$composition = $_POST['composition'];
	$update_ingredient = $wpdb->update(
		$wpdb->prefix . 'product_ingredient',
		array(
			'ingredient_id' => $ingredient,
			'content'       => $composition,
		),
		array(
			'id'=>$table_id,
		)
	);
}
add_action( 'before_delete_post', 'delete_crop' );
function delete_crop( $postid ){

    // We check if the global post type isn't ours and just return
    global $post_type ,$wpdb;   
    if ( $post_type == 'product' ) {
        $crop_delete = $wpdb->query("DELETE FROM {$wpdb->prefix}crop_data WHERE product_id = $postid");
        $product_ingredient_delete = $wpdb->query("DELETE FROM {$wpdb->prefix}product_ingredient WHERE product_id = $postid");
    }
    
    // My custom stuff for deleting my custom post type here
}
?>