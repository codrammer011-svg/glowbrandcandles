<?php

if (!class_exists('WP_List_Table')) {
      require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

// Extending class
class Supporthost_List_Table extends WP_List_Table
{
     // Here we will add our code

    // Define table columns
    function get_columns()
    {
        $columns = array(
                'cb'            => '<input type="checkbox" />',
                'name'          => __('Name', 'supporthost-cookie-consent'),
                'email'         => __('Email', 'supporthost-cookie-consent'),
                'firstname'   => __('FirstName', 'supporthost-cookie-consent'),
                'lastname'        => __('LastName', 'supporthost-cookie-consent'),
              	'view_dashboard'        => __('Representative Dashboard', 'supporthost-cookie-consent')
        );
        return $columns;
    }
    
   
    
    // Get table data
   // Get table data
    private function get_table_data( $search = '' ) {
        global $wpdb;

        /*$table = $wpdb->prefix . 'users';
        print_r($table);

        if ( !empty($search) ) {
            return $wpdb->get_results(
                "SELECT * from {$table} WHERE name Like '%{$search}%' OR description Like '%{$search}%' OR status Like '%{$search}%'",
                ARRAY_A
            );
        } else {
            
            return $wpdb->get_results(
                "SELECT * from {$table}",
                ARRAY_A
            );
            $data = $wpdb->get_results(
                "SELECT * from {$table}",
                ARRAY_A
            );
            echo "<pre>";
            print_r($data);
        }
        */
        
        
    $data = array();

      $users_data = get_users(array( 'role__in' => array( 'representative' )));

        
		foreach($users_data as $user){
		    
        	$data[] = array(   
        	    'id' => $user->data->ID,
            	'name' => $user->data->user_login,
            	'email' => $user->data->user_email,
            	'firstname' =>  get_user_meta($user->data->ID,'first_name',true),
            	'lastname' =>  get_user_meta($user->data->ID,'last_name',true),
                'view_dashboard' =>  '<a href="https://glowbrandscandles.com/reprenstative-dashboard/?userid='.$user->data->ID.'" target="_blank">View</a>'
                );
        }
                return $data;
    }
    
        private $table_data;

  
      function column_default($item, $column_name)
    {
          switch ($column_name) {
                case 'id':
                case 'name':
                case 'email':
                case 'firstname':
                case 'lastname':  
                case 'view_dashboard':  
                default:
                    return $item[$column_name];
          }
    }
    
     function column_cb($item)
    {
        return sprintf(
                '<input type="checkbox" name="element[]" value="%s" />',
                $item['id']
        );
    }
    
    protected function get_sortable_columns()
{
      $sortable_columns = array(
            'name'  => array('name', false),
            'firstname' => array('firstname', false),
            'lastname'   => array('lastname', true)
      );
      return $sortable_columns;
}

 // Sorting function
    function usort_reorder($a, $b)
    {
        // If no sort, default to user_login
        $orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : 'user_login';

        // If no order, default to asc
        $order = (!empty($_GET['order'])) ? $_GET['order'] : 'asc';

        // Determine sort order
        $result = strcmp($a[$orderby], $b[$orderby]);

        // Send final sort direction to usort
        return ($order === 'asc') ? $result : -$result;
    }
    
    
        // Adding action links to column
    function column_name($item)
    {
        $actions = array(
                'edit'      => sprintf('<a href="?page=%s&action=%s&element=%s">' . __('Edit', 'supporthost-admin-table') . '</a>', $_REQUEST['page'], 'edit', $item['id']),
                'delete'    => sprintf('<a href="?page=%s&action=%s&element=%s">' . __('Delete', 'supporthost-admin-table') . '</a>', $_REQUEST['page'], 'delete', $item['id']),
        );

        return sprintf('%1$s %2$s', $item['name'], $this->row_actions($actions));
    }
    
  // Bind table with columns, data and all
   // Bind table with columns, data and all
    function prepare_items()
    {
        //data
        if ( isset($_POST['s']) ) {
            $this->table_data = $this->get_table_data($_POST['s']);
        } else {
            $this->table_data = $this->get_table_data();
        }

        $columns = $this->get_columns();
        $hidden = ( is_array(get_user_meta( get_current_user_id(), 'managetoplevel_page_supporthost_list_tablecolumnshidden', true)) ) ? get_user_meta( get_current_user_id(), 'managetoplevel_page_supporthost_list_tablecolumnshidden', true) : array();
        $sortable = $this->get_sortable_columns();
        $primary  = 'name';
        $this->_column_headers = array($columns, $hidden, $sortable, $primary);

        usort($this->table_data, array(&$this, 'usort_reorder'));

        /* pagination */
        $per_page = $this->get_items_per_page('elements_per_page', 5);
        $current_page = $this->get_pagenum();
        $total_items = count($this->table_data);

        $this->table_data = array_slice($this->table_data, (($current_page - 1) * $per_page), $per_page);

        $this->set_pagination_args(array(
                'total_items' => $total_items, // total number of items
                'per_page'    => $per_page, // items to show on a page
                'total_pages' => ceil( $total_items / $per_page ) // use ceil to round up
        ));
        
        $this->items = $this->table_data;
    }
}
function add_new_reps_submenu_page_callback(){
  //  wp_redirect( "https://egmontpeoplewoo.duplisite.com/wp-admin/user-new.php", 301 );
    //    exit();
    include_once( 'add-reps.php' );	
}
// Adding menu
function my_add_menu_items()
{
        
      add_menu_page('Representative', 'Representative', 'manage_options', 'representative', 'representative_list_init');
      add_submenu_page("representative","Add New Reps","Add New Reps",'manage_options',"my-custom-submenu-page", "add_new_reps_submenu_page_callback");
}
add_action('admin_menu', 'my_add_menu_items');

// Plugin menu callback function
function representative_list_init()
{
      // Creating an instance
      $table = new Supporthost_List_Table();

      echo '<div class="wrap"><h2>Representative Data</h2>';
      echo '<form method="post">';
      // Prepare table
      $table->prepare_items();
      // Search form
      //$table->search_box('search', 'search_id');
      // Display table
      $table->display();
      echo '</div></form>';
}
 
?>