<?php
/**
 * Render a container element
 *
 * @uses $vars['item']      The item being rendered
 * @uses $vars['item_vars'] Vars received from the page/components/table view
 * @uses $vars['type']      The item type or ""
 * @uses $vars['link']      Set to false to not use an anchor
 */

$item = $vars['item'];
/* @var ElggEntity $item */

$link = elgg_extract('link', $vars, true);

$entity = $item->getContainerEntity();
if (!$entity) {
	return;
}

$value = $entity->getDisplayName();
if ($link) {
	$value = elgg_view('output/url', [
		'href' => $entity->getURL(),
		'text' => $value,
	]);
}

echo $value;
