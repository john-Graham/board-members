<?php

/**
 * ACF Blocks helpers.
 *
 * @link https://www.advancedcustomfields.com/resources/blocks/
 */

namespace UWBoardMembers;

class ACFBlockHelpers
{
	public function __construct()
	{
		add_action('init', [$this, 'uw_board_members_blocks_register'], 5);
		add_filter('block_categories_all', [$this, 'uw_board_members_block_category']);
		add_filter('acf/blocks/no_fields_assigned_message', [$this, 'uw_board_members_block_no_fields_msg'], 10, 2);
	}

	public function uw_board_members_blocks_register()
	{
		$blocks = $this->uw_board_members_get_blocks();

		foreach ($blocks as $block) {
			if (file_exists(UW_BOARD_MEMBERS_PLUGIN_BLOCKS . $block . '/block.json')) {
				register_block_type(UW_BOARD_MEMBERS_PLUGIN_BLOCKS . $block . '/block.json');
			}
		}
	}

	public function uw_board_members_get_blocks()
	{
		$blocks  = get_option('uw_board_members_blocks');
		$version = get_option('uw_board_members_blocks_version');

		if (empty($blocks) || version_compare(UW_BOARD_MEMBERS_VERSION, $version, '>') || (function_exists('wp_get_environment_type') && 'production' !== wp_get_environment_type())) {
			$blocks = scandir(UW_BOARD_MEMBERS_PLUGIN_BLOCKS);
			$blocks = array_values(array_diff($blocks, array('..', '.', '.DS_Store')));

			update_option('uw_board_members_blocks', $blocks);
			update_option('uw_board_members_blocks_version', UW_BOARD_MEMBERS_VERSION);
		}

		return $blocks;
	}

	public function uw_board_members_block_category($block_categories)
	{
		$block_categories[] = array(
			'slug'  => 'uw-board-member-blocks',
			'title' => __('UW Board Member Blocks', 'uw-board-members-blocks'),
		);

		return $block_categories;
	}

	public function uw_board_members_block_no_fields_msg($message, $block_name)
	{
		// Custom message logic can go here
		return $message;
	}
}

// Create an instance of the class to ensure the hooks are registered
new ACFBlockHelpers();
