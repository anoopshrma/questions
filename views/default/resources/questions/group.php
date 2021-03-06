<?php
/**
 * Elgg questions plugin owner page
 *
 * @package Questions
 */

elgg_entity_gatekeeper(elgg_get_page_owner_guid(), 'group');
elgg_group_tool_gatekeeper('questions');

/* @var $page_owner ElggGroup */
$page_owner = elgg_get_page_owner_entity();

elgg_push_collection_breadcrumbs('object', ElggQuestion::SUBTYPE, $page_owner);

elgg_register_title_button('questions', 'add', 'object', ElggQuestion::SUBTYPE);

// prepare options
$options = [
	'type' => 'object',
	'subtype' => 'question',
	'container_guid' => $page_owner->guid,
	'full_view' => false,
	'list_type_toggle' => false,
	'no_results' => elgg_echo('questions:none'),
];

$tags = get_input('tags');
if (!empty($tags)) {
	if (is_string($tags)) {
		$tags = string_to_tag_array($tags);

	}
	$options['metadata_name_value_pairs'] = [
		'name' => 'tags',
		'value' => $tags,
	];
}

// build page elements
$title = elgg_echo('questions:owner', [$page_owner->getDisplayName()]);

$content = elgg_list_entities($options);

// build page
$body = elgg_view_layout('content', [
	'title' => $title,
	'content' => $content,
]);

// draw page
echo elgg_view_page($title, $body);
