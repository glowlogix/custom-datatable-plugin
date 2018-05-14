<?php
function create_agri_table(){
    global $wpdb;
    $table_name1 = $wpdb->prefix . 'datatable_pesticide';
    $table_name2 = $wpdb->prefix . 'datatables_user';
    $table_name3 = $wpdb->prefix . 'product_ingredient';
    $table_name4 = $wpdb->prefix . 'crop_data';

    $charset_collate = $wpdb->get_charset_collate();


    $sql1 = "CREATE TABLE $table_name1 (
      id int(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
      post_id int(255),
      product_name varchar(255),
      content varchar(255),
      manufacturer varchar(255),
      country varchar(255),
      crop varchar(255),
      dosage varchar(255),
      diseases varchar(255),
      method_app varchar(255),
      quarantine_period varchar(255)
      ) $charset_collate;";

    $sql2 = "CREATE TABLE $table_name2 (
      id int(9)  AUTO_INCREMENT PRIMARY KEY,
      user_id int(9) NOT NULL,
      user_name varchar(255) NOT NULL, 
      user_role BOOLEAN DEFAULT 0
      ) $charset_collate;";

    $sql3 = "CREATE TABLE $table_name3 (
      id int(9)  AUTO_INCREMENT PRIMARY KEY,
      product_id int(9) NOT NULL,
      ingredient_id int(9) NOT NULL,
      content varchar(255) NOT NULL,
      user_id int(11) NOT NULL, 
      status BOOLEAN DEFAULT 0
      ) $charset_collate;";

    $sql4 = "CREATE TABLE $table_name4 (
      id int(9)  AUTO_INCREMENT PRIMARY KEY,
      product_id int(9) NOT NULL,
      crop_id int(255) NOT NULL,
      activity_id int(255) NOT NULL,
      dosage varchar(255) NOT NULL, 
      method_app varchar(255) NOT NULL,
      quarantine varchar(255) NOT NULL,
      status BOOLEAN DEFAULT 0
      ) $charset_collate;";

    

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql1 );
    dbDelta( $sql2 );
    dbDelta( $sql3 );
    dbDelta( $sql4 );
  }
  function my_plugin_remove_tables() {
    global $wpdb;
    $tables[] = $wpdb->prefix . 'datatable_pesticide';
    $tables[] = $wpdb->prefix . 'datatables_user';
    $tables[] = $wpdb->prefix . 'product_ingredient';
    $tables[] = $wpdb->prefix . 'crop_data';
    foreach ($tables as $key => $table) {
    $sql = "DROP TABLE IF EXISTS $table;";
    $wpdb->query($sql);
    delete_option("my_plugin_db_version");
    }
  }
  ?>