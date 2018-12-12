<?php
	if (isset($_GET['select2_data'])) {
		$post_id = $_GET['select2_search'];
		$post_data = get_post(126);
		if ($post_data->post_type=="product") {
			$get_ingredients = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}product_ingredient WHERE product_id = $post_data->ID");
			foreach ($get_ingredients as $key => $get_ingredient) {
				$ingredient_name.=$concat.get_the_title($get_ingredient->ingredient_id);
				$ingredient_content.=$concat.$get_ingredient->content;
				$category = wp_get_post_terms( $get_ingredient->ingredient_id, 'ingredient_category');
				$concat=',';
			}
			$get_crops = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}crop_data
				WHERE product_id = $post_data->ID"); ?>
			<table>
				<thead>
					<th>Active ingredient</th>	
					<th>Product Name</th>
					<th>Composition</th>
					<th>Manufacturer</th>
					<th>Country</th>
					<th>Crop</th>
					<th>Dosage</th>
					<th>Activity</th>
					<th>Method of application</th>
					<th>Restrictions</th>
				</thead>
				
			<?php foreach ($get_crops as $key => $crop) {
			$manufacturer = wp_get_post_terms( $crop->product_id, 'manufacturer'); 
			$country = wp_get_post_terms( $id, 'country');
			$crop_data=get_term_by('id', $crop->crop_id, 'crop'); 
			$activity_data=get_term_by('id', $crop->activity_id, 'activity'); ?>
				<tr>
					<td><?php echo($ingredient_name);  ?></td>
					<td><?php echo(get_the_title($crop->product_id)) ?></td>
					<td><?php echo ($ingredient_content) ?></td>
					<td><?php echo ($manufacturer[0]->name) ?></td>
					<td><?php echo ($country[0]->name) ?></td>
					<td><?php echo($crop_data->name) ?></td>
					<td><?php echo($crop->dosage)?></td>
					<td><?php echo($activity_data->name) ?></td>
					<td><?php echo($crop->method_app) ?></td>
					<td><?php echo($crop->quarantine) ?></td>	
				</tr>
			<?php } ?>
			</table>
		<?php }elseif ($post_data->post_type=="ingredient") {
			$get_product= $wpdb->get_results("SELECT * FROM {$wpdb->prefix}product_ingredient WHERE ingredient_id = $post_data->ID group by product_id");
			print_r($get_product);
			$product_id = $get_product[0]->product_id;
			$get_crops = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}crop_data
				WHERE product_id = $product_id"); ?>
			<table>
				<thead>
					<th>Active ingredient</th>	
					<th>Product Name</th>
					<th>Composition</th>
					<th>Manufacturer</th>
					<th>Country</th>
					<th>Crop</th>
					<th>Dosage</th>
					<th>Activity</th>
					<th>Method of application</th>
					<th>Restrictions</th>
				</thead>
			<?php foreach ($get_crops as $key => $crop) {
				$manufacturer = wp_get_post_terms( $crop->product_id, 'manufacturer'); 
				$country = wp_get_post_terms( $id, 'country');
				$crop_data=get_term_by('id', $crop->crop_id, 'crop'); 
				$activity_data=get_term_by('id', $crop->activity_id, 'activity'); ?>
				<tr>
					<td><?php echo get_the_title($post_data->ID);  ?></td>
					<td><?php echo(get_the_title($crop->product_id)) ?></td>
					<td><?php echo ($$get_product[0]->content) ?></td>
					<td><?php echo ($manufacturer[0]->name) ?></td>
					<td><?php echo ($country[0]->name) ?></td>
					<td><?php echo($crop_data->name) ?></td>
					<td><?php echo($crop->dosage)?></td>
					<td><?php echo($activity_data->name) ?></td>
					<td><?php echo($crop->method_app) ?></td>
					<td><?php echo($crop->quarantine) ?></td>	
				</tr>
			<?php } ?>
			</table>	
		<?php }
	}

	 ?>