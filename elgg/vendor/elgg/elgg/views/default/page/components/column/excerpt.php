<?php
/**
 * Render an excerpt of the description
 *
 * @uses $vars['item']      The item being rendered
 * @uses $vars['item_vars'] Vars received from the page/components/table view
 * @uses $vars['type']      The item type or ""
 * @uses $vars['length']    The excerpt length
 */

$item = $vars['item'];
/* @var ElggEntity $item */

$length = elgg_extract('length', $vars, 250);

$description = $item->description;

echo elgg_get_excerpt($description, $excerpt_length);
