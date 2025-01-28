<?php

/**
 * Plugin Name: WS Form to Trello Automation
 * Description: Creates Trello cards from WS Form submissions.
 * Version: 1.0.0
 * Author: Hamza Mehmood
 */

// Prevent direct access
if (!defined('ABSPATH')) exit;

// Hook into WS Form submission
add_action('wsf_after_save_form_data', 'create_trello_card_from_wsform', 10, 2);

/**
 * Create a Trello card from a WS Form submission.
 *
 * @param int $submission_id The WS Form submission ID.
 * @param int $form_id The WS Form ID.
 */
function create_trello_card_from_wsform($submission_id, $form_id)
{
    // Trello API credentials
    $api_key = 'your_trello_api_key';
    $token = 'your_trello_token';
    $list_id = 'your_trello_list_id'; // Replace with your Trello List ID

    // Get WS Form submission data
    $submission_data = wsf_get_submission($submission_id);
    if (!$submission_data || empty($submission_data['fields'])) {
        error_log('Failed to retrieve WS Form submission data.');
        return;
    }

    // Prepare the Trello card details
    $card_name = 'New Submission from WS Form'; // Card title
    $card_description = '';
    foreach ($submission_data['fields'] as $field) {
        $card_description .= $field['label'] . ': ' . $field['value'] . "\n";
    }

    // Trello API endpoint
    $url = "https://api.trello.com/1/cards";

    // Request payload
    $body = [
        'key' => $api_key,
        'token' => $token,
        'idList' => $list_id,
        'name' => $card_name,
        'desc' => $card_description,
    ];

    // Make API request to Trello
    $response = wp_remote_post($url, [
        'body' => $body,
    ]);

    // Log errors or success
    if (is_wp_error($response)) {
        error_log('Trello API Error: ' . $response->get_error_message());
    } else {
        $response_body = wp_remote_retrieve_body($response);
        error_log('Trello card created successfully: ' . $response_body);
    }
}
