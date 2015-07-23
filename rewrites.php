<?php

	/**
	 * add api rewrite rules for multi taxonomy
	 *
	 * @return void
	 * @author 
	 **/
	function cs_create_rewrite_rules($rules) {
		global $wp_rewrite;
		$newRule = array('collection/(.+)/(.+)/(.+)/page/([0-9]{1,})/?$' => 'index.php?collection=' . $wp_rewrite->preg_index( 1 ).'&'.$wp_rewrite->preg_index( 2 ).'='. $wp_rewrite->preg_index( 3 ).'&paged='. $wp_rewrite->preg_index( 4 ));
		$newRule += array('collection/(.+)/(.+)/(.+)/?$' => 'index.php?collection=' . $wp_rewrite->preg_index( 1 ).'&'.$wp_rewrite->preg_index( 2 ).'='. $wp_rewrite->preg_index( 3 ));
		
		$newRules = $newRule + $rules;
		return $newRules;
	}
	add_filter( 'rewrite_rules_array' , 'cs_create_rewrite_rules' );

	/**
	 * use taxonomy template for custom rewrite
	 *
	 * @return void
	 * @author 
	 **/
	add_filter( 'template_include', 'cs_choose_template' );

	function cs_choose_template( $original_template ) {

		global $wp_query;

		if ( isset($wp_query->query['collection']) && (isset($wp_query->query['product-category']) || isset($wp_query->query['product-tag'])) ) {

			$located_product_category_template = locate_template( 'taxonomy-product-category.php' );
			$located_tax_template = locate_template( 'taxonomy-product-category.php' );

			if ( !empty( $located_product_category_template ) ) {
				$template_file = get_stylesheet_directory().'/taxonomy-product-category.php';
			} elseif ( !empty( $located_tax_template ) ) {
				$template_file = get_stylesheet_directory().'/taxonomy.php';
			} else {
				$template_file = $original_template;
			}
			return $template_file;
		} else {
			return $original_template;
		}
	}


?>