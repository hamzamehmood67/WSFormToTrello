add_action('wsf_after_save_form_data', 'create_trello_card_from_wsform', 10, 2);

function create_trello_card_from_wsform($submission_id, $form_id) {
// Trello API credentials
$api_key = 'your_trello_api_key';
$token = 'your_trello_token';
$list_id = 'your_trello_list_id'; // Get the ID of the Trello list where cards will be created

// Get WS Form submission data
$submission_data = wsf_get_submission($submission_id); // Retrieves form submission data
$form_data = $submission_data['fields'];

// Prepare Trello card data
$card_name = 'New WS Form Submission'; // Customize as needed
$card_description = '';
foreach ($form_data as $field) {
$card_description .= $field['label'] . ': ' . $field['value'] . "\n";
}

// Trello API endpoint
$url = "https://api.trello.com/1/cards";

// Trello API request parameters
$body = [
'key' => $api_key,
'token' => $token,
'idList' => $list_id,
'name' => $card_name,
'desc' => $card_description,
];

// Make a POST request to Trello API
$response = wp_remote_post($url, [
'body' => $body,
]);

// Handle the response
if (is_wp_error($response)) {
error_log('Trello API Error: ' . $response->get_error_message());
} else {
error_log('Trello card created successfully.');
}
}