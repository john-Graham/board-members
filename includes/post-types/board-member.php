<?php

namespace UWBoardMembers;

class CPTRegistration
{
    public function __construct()
    {
        add_action('init', [$this, 'registerBoardMemberCPT']);
    }

    public function registerBoardMemberCPT()
    {
        register_post_type('board-member', array(
            'labels' => array(
                'name' => 'Board Members',
                'singular_name' => 'Board Member',
                'menu_name' => 'Board Members',
                'all_items' => 'All Board Members',
                'edit_item' => 'Edit Board Member',
                'view_item' => 'View Board Member',
                'view_items' => 'View Board Members',
                'add_new' => 'Add New Board Member',
                'add_new_item' => 'Add New Board Member',
                'new_item' => 'New Board Member',
                'parent_item_colon' => 'Parent Board Member:',
                'search_items' => 'Search Board Members',
                'not_found' => 'No board members found',
                'not_found_in_trash' => 'No board members found in Trash',
                'archives' => 'Board Member Archives',
                'attributes' => 'Board Member Attributes',
                'insert_into_item' => 'Insert into board member',
                'uploaded_to_this_item' => 'Uploaded to this board member',
                'filter_items_list' => 'Filter board members list',
                'filter_by_date' => 'Filter board members by date',
                'items_list_navigation' => 'Board Members list navigation',
                'items_list' => 'Board Members list',
                'item_published' => 'Board Member published.',
                'item_published_privately' => 'Board Member published privately.',
                'item_reverted_to_draft' => 'Board Member reverted to draft.',
                'item_scheduled' => 'Board Member scheduled.',
                'item_updated' => 'Board Member updated.',
                'item_link' => 'Board Member Link',
                'item_link_description' => 'A link to a board member.',
            ),
            'description' => 'Blocks for displaying board members',
            'public' => true,
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'show_in_rest' => true,
            'menu_icon' => 'dashicons-networking',
            'supports' => array('title', 'editor', 'thumbnail'),
            'taxonomies' => array('board'),
            'delete_with_user' => false,
        ));
    }
}

// Create an instance of the class to ensure the CPT is registered
new CPTRegistration();
