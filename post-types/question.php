<?php

/**
 * Registers the `question` post type.
 */
function question_init() {
    register_post_type(
        'question',
        [
            'labels'                => [
                'name'                  => __( 'Questions', 'quizzar' ),
                'singular_name'         => __( 'Question', 'quizzar' ),
                'all_items'             => __( 'All Questions', 'quizzar' ),
                'archives'              => __( 'Question Archives', 'quizzar' ),
                'attributes'            => __( 'Question Attributes', 'quizzar' ),
                'insert_into_item'      => __( 'Insert into Question', 'quizzar' ),
                'uploaded_to_this_item' => __( 'Uploaded to this Question', 'quizzar' ),
                'featured_image'        => _x( 'Featured Image', 'question', 'quizzar' ),
                'set_featured_image'    => _x( 'Set featured image', 'question', 'quizzar' ),
                'remove_featured_image' => _x( 'Remove featured image', 'question', 'quizzar' ),
                'use_featured_image'    => _x( 'Use as featured image', 'question', 'quizzar' ),
                'filter_items_list'     => __( 'Filter Questions list', 'quizzar' ),
                'items_list_navigation' => __( 'Questions list navigation', 'quizzar' ),
                'items_list'            => __( 'Questions list', 'quizzar' ),
                'new_item'              => __( 'New Question', 'quizzar' ),
                'add_new'               => __( 'Add New Question', 'quizzar' ),
                'add_new_item'          => __( 'Add New Question', 'quizzar' ),
                'edit_item'             => __( 'Edit Question', 'quizzar' ),
                'view_item'             => __( 'View Question', 'quizzar' ),
                'view_items'            => __( 'View Questions', 'quizzar' ),
                'search_items'          => __( 'Search Questions', 'quizzar' ),
                'not_found'             => __( 'No Questions found', 'quizzar' ),
                'not_found_in_trash'    => __( 'No Questions found in trash', 'quizzar' ),
                'parent_item_colon'     => __( 'Parent Question:', 'quizzar' ),
                'menu_name'             => __( 'Quizzar', 'quizzar' ),
            ],
            'public'                => true,
            'hierarchical'          => false,
            'show_ui'               => true,
            'show_in_nav_menus'     => true,
            'supports'              => [ 'title', 'editor' ],
            'has_archive'           => true,
            'rewrite'               => true,
            'query_var'             => true,
            'menu_position'         => null,
            'menu_icon'             => 'dashicons-book-alt',
            'show_in_rest'          => true,
            'rest_base'             => 'question',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
        ]
    );

    // Add new "Locations" taxonomy to Posts
    register_taxonomy('location', 'question', array(
        'hierarchical' => false,
        'labels' => array(
            'name' => _x( 'Quizzes', 'taxonomy general name' ),
            'singular_name' => _x( 'Quiz', 'taxonomy singular name' ),
            'search_items' =>  __( 'Search Quizzes' ),
            'all_items' => __( 'All Quizzes' ),
            'edit_item' => __( 'Edit Quiz' ),
            'update_item' => __( 'Update Quiz' ),
            'add_new_item' => __( 'Add New Quiz' ),
            'new_item_name' => __( 'New Quiz Name' ),
            'menu_name' => __( 'Manage Quizzes' ),
        ),
        'rewrite' => array(
            'slug' => 'quiz',
            'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
        ),
    ));
}

add_action( 'init', 'question_init' );

/**
 * Sets the post updated messages for the `question` post type.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `question` post type.
 */
function question_updated_messages( $messages ) {
    global $post;

    $permalink = get_permalink( $post );

    $messages['question'] = [
        0  => '', // Unused. Messages start at index 1.
        /* translators: %s: post permalink */
        1  => sprintf( __( 'Question updated. <a target="_blank" href="%s">View Question</a>', 'quizzar' ), esc_url( $permalink ) ),
        2  => __( 'Custom field updated.', 'quizzar' ),
        3  => __( 'Custom field deleted.', 'quizzar' ),
        4  => __( 'Question updated.', 'quizzar' ),
        /* translators: %s: date and time of the revision */
        5  => isset( $_GET['revision'] ) ? sprintf( __( 'Question restored to revision from %s', 'quizzar' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        /* translators: %s: post permalink */
        6  => sprintf( __( 'Question published. <a href="%s">View Question</a>', 'quizzar' ), esc_url( $permalink ) ),
        7  => __( 'Question saved.', 'quizzar' ),
        /* translators: %s: post permalink */
        8  => sprintf( __( 'Question submitted. <a target="_blank" href="%s">Preview Question</a>', 'quizzar' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
        /* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
        9  => sprintf( __( 'Question scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Question</a>', 'quizzar' ), date_i18n( __( 'M j, Y @ G:i', 'quizzar' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
        /* translators: %s: post permalink */
        10 => sprintf( __( 'Question draft updated. <a target="_blank" href="%s">Preview Question</a>', 'quizzar' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
    ];

    return $messages;
}

add_filter( 'post_updated_messages', 'question_updated_messages' );

/**
 * Sets the bulk post updated messages for the `question` post type.
 *
 * @param  array $bulk_messages Arrays of messages, each keyed by the corresponding post type. Messages are
 *                              keyed with 'updated', 'locked', 'deleted', 'trashed', and 'untrashed'.
 * @param  int[] $bulk_counts   Array of item counts for each message, used to build internationalized strings.
 * @return array Bulk messages for the `question` post type.
 */
function question_bulk_updated_messages( $bulk_messages, $bulk_counts ) {
    global $post;

    $bulk_messages['question'] = [
        /* translators: %s: Number of Questions. */
        'updated'   => _n( '%s Question updated.', '%s Questions updated.', $bulk_counts['updated'], 'quizzar' ),
        'locked'    => ( 1 === $bulk_counts['locked'] ) ? __( '1 Question not updated, somebody is editing it.', 'quizzar' ) :
                        /* translators: %s: Number of Questions. */
                        _n( '%s Question not updated, somebody is editing it.', '%s Questions not updated, somebody is editing them.', $bulk_counts['locked'], 'quizzar' ),
        /* translators: %s: Number of Questions. */
        'deleted'   => _n( '%s Question permanently deleted.', '%s Questions permanently deleted.', $bulk_counts['deleted'], 'quizzar' ),
        /* translators: %s: Number of Questions. */
        'trashed'   => _n( '%s Question moved to the Trash.', '%s Questions moved to the Trash.', $bulk_counts['trashed'], 'quizzar' ),
        /* translators: %s: Number of Questions. */
        'untrashed' => _n( '%s Question restored from the Trash.', '%s Questions restored from the Trash.', $bulk_counts['untrashed'], 'quizzar' ),
    ];

    return $bulk_messages;
}

add_filter( 'bulk_post_updated_messages', 'question_bulk_updated_messages', 10, 2 );
