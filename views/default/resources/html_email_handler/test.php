<?php
/**
 * A test page for theme developer to view the layout of an email notification
 */

$user = elgg_get_logged_in_user_entity();
$site = elgg_get_site_entity();

$subject = elgg_echo('useradd:subject');
$plain_message = elgg_echo('useradd:body', [
	$user->getDisplayName(),
	$site->getDisplayName(),
	$site->getURL(),
	$user->username,
	'test123',
]);

$html_message = html_email_handler_make_html_body([
	'subject' => $subject,
	'body' => $plain_message,
	'recipient' => $user,
]);

$html_message = html_email_handler_normalize_urls($html_message);
$html_message = html_email_handler_base64_encode_images($html_message);

echo $html_message;

if (!get_input('mail')) {
	return;
}

// Test sending attachments through notify_user()
$params = [
	'attachments' => [
		[
			'filepath' => elgg_get_plugin_from_id('html_email_handler')->getPath() . 'manifest.xml',
			'filename' => 'manifest.xml',
			'mimetype' => 'application/xml',
		],
	],
];

notify_user($user->guid, $site->guid, $subject, $plain_message, $params, ['email']);
