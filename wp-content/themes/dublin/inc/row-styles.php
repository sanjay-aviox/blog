<?php
/**
 * Dynamic styles for the Page Builder rows
 *
 * @package Dublin
 */
?>
<?php

function dublin_panels_row_style_fields($fields) {

	$fields['bottom_border'] = array(
		'name' => __('Bottom Border Color', 'dublin'),
		'type' => 'color',
	);

	$fields['color'] = array(
		'name' => __('Color', 'dublin'),
		'type' => 'color',
	);	

	$fields['background'] = array(
		'name' => __('Background Color', 'dublin'),
		'type' => 'color',
	);

	$fields['background_image'] = array(
		'name' => __('Background Image (upload the image your Media Gallery and paste the link here)', 'dublin'),
		'type' => 'url',
	);

	return $fields;
}
add_filter('siteorigin_panels_row_style_fields', 'dublin_panels_row_style_fields');

function dublin_panels_panels_row_style_attributes($attr, $style) {
	$attr['style'] = '';

	if(!empty($style['bottom_border'])) $attr['style'] .= 'border-bottom: 1px solid '.$style['bottom_border'].'; ';
	if(!empty($style['background'])) $attr['style'] .= 'background-color: '.$style['background'].'; ';
	if(!empty($style['color'])) $attr['style'] .= 'color: '.$style['color'].'; ';
	if(!empty($style['background_image'])) $attr['style'] .= 'background-image: url('.esc_url($style['background_image']).'); ';

	if(empty($attr['style'])) unset($attr['style']);
	return $attr;
}
add_filter('siteorigin_panels_row_style_attributes', 'dublin_panels_panels_row_style_attributes', 10, 2);