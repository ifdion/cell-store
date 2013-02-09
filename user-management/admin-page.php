<?php
/* show registration date on manage user column
---------------------------------------------------------------
*/

// add_filter('manage_users_columns', 'set_registration_column');
// function set_registration_column($columns) {
// 	$columns['registration-date'] = 'Registration Date';
// 	return $columns;
// }
 
// add_action('manage_users_custom_column',  'show_registration_date', 10, 3);
// function show_registration_date($value, $column_name, $user_id) {
// 	if ( 'registration-date' == $column_name ){
// 		$user = get_user_by('id', $user_id);
// 		return $user->data->user_registered;
// 	}
// }


/*******************
 * Modify the User Search in Admin to include first, last names.
 * Add sorting by name if search string starts with 'byname:'.
*/
add_action('pre_user_query','mam_pre_user_query');
function mam_pre_user_query($user_search) {
	global $wpdb;
	// print_r($user_search);
	$vars = $user_search->query_vars;
	if (!is_null($vars['search'])){
		/* For some reason, the search term is enclosed in asterisks. Remove them */
		$search = preg_replace('/^\*/','',$vars['search']);
		$search = preg_replace('/\*$/','',$search);
		$user_search->query_from .= " INNER JOIN {$wpdb->usermeta} m1 ON " . "{$wpdb->users}.ID=m1.user_id AND (m1.meta_key='first_name')";
		$user_search->query_from .= " INNER JOIN {$wpdb->usermeta} m2 ON " . "{$wpdb->users}.ID=m2.user_id AND (m2.meta_key='last_name')";
		// IF the search var starts with byname:, sort by name.
		if (preg_match('/^byname:/',$search)) {
			$search = preg_replace('/^byname:/','',$search);
			$user_search->query_orderby = ' ORDER BY UPPER(m2.meta_value), UPPER(m1.meta_value) ';
			$user_search->query_vars['search'] = $search;
			$user_search->query_where = str_replace('byname:','',$user_search->query_where);
		}
		$names_where = $wpdb->prepare("m1.meta_value LIKE '%s' OR m2.meta_value LIKE '%s'", "%{$search}%","%$search%");
		$user_search->query_where = str_replace('WHERE 1=1 AND (', "WHERE 1=1 AND ({$names_where} OR ",$user_search->query_where);
	}
}
?>