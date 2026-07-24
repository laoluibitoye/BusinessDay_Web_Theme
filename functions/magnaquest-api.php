<?php
/**
 * Magnaquest API Integration
 * Handles proxying authentication, registration, and password changes to Magnaquest APIs.
 */

@ini_set('display_errors', 0);

// Magnaquest Staging API Endpoints
if (!defined('MQ_API_LOGIN_URL')) {
    define('MQ_API_LOGIN_URL', 'https://businessdaytest.magnaquest.com/WebApi/Restapi/Login');
}
if (!defined('MQ_API_REGISTER_URL')) {
    define('MQ_API_REGISTER_URL', 'https://businessdaytest.magnaquest.com/WebApi/Restapi/CreateCustomer');
}
if (!defined('MQ_API_CHANGE_PASSWORD_URL')) {
    define('MQ_API_CHANGE_PASSWORD_URL', 'https://businessdaytest.magnaquest.com/WebApi/Restapi/ChangePassword');
}
if (!defined('MQ_API_FORGOT_PASSWORD_URL')) {
    define('MQ_API_FORGOT_PASSWORD_URL', 'https://businessdaytest.magnaquest.com/WebApi/Restapi/ForgotPassword');
}
if (!defined('MQ_API_RESET_PASSWORD_URL')) {
    define('MQ_API_RESET_PASSWORD_URL', 'https://businessdaytest.magnaquest.com/WebApi/Restapi/ResetPassword');
}
if (!defined('MQ_API_FIND_APPUSER_URL')) {
    define('MQ_API_FIND_APPUSER_URL', 'https://businessdaytest.magnaquest.com/WebApi/Restapi/FindAppuserByLogintypeAndLoginName');
}
if (!defined('MQ_API_SELFCARE_RESET_PASSWORD_URL')) {
    define('MQ_API_SELFCARE_RESET_PASSWORD_URL', 'https://businessdaytest.magnaquest.com/WebApi/Restapi/SelfcareResetPassword');
}

if (!defined('MQ_API_SEND_PASSWORD_RESET_LINK_URL')) {
    define('MQ_API_SEND_PASSWORD_RESET_LINK_URL', 'https://businessdaytest.magnaquest.com/WebApi/Restapi/SendPasswordResetLink');
}


/**
 * Helper to generate a GUID (UUID v4)
 */
function mq_generate_guid() {
    if (function_exists('wp_generate_uuid4')) {
        return wp_generate_uuid4();
    }
    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X',
        mt_rand(0, 65535), mt_rand(0, 65535),
        mt_rand(0, 65535),
        mt_rand(16384, 20479),
        mt_rand(32768, 49151),
        mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535)
    );
}

/**
 * Helper to register a shutdown function that intercepts fatal errors during AJAX execution.
 *
 * @param string $flow_name The name of the flow (e.g. 'Login', 'Register') for logging purposes.
 */
function mq_register_shutdown_handler($flow_name) {
    register_shutdown_function(function() use ($flow_name) {
        $error = error_get_last();
        if ($error !== null && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR])) {
            error_log('AJAX FATAL SHUTDOWN DETECTED (' . $flow_name . '): ' . print_r($error, true));
            if (defined('WP_CONTENT_DIR')) {
                file_put_contents(WP_CONTENT_DIR . '/ajax-fatal-error.log', date('[Y-m-d H:i:s] ') . $flow_name . ' Flow Error: ' . print_r($error, true), FILE_APPEND);
            }
        }
    });
}

/**
 * Perform a POST request to the Magnaquest API.
 *
 * @param string $api_url                 The base API URL.
 * @param array  $payload                 The data payload.
 * @param bool   $require_operator_headers Optional. Whether to append administrative Operator credentials.
 * @return array|WP_Error Array with status_code and body on success, WP_Error on remote connection failure.
 */
function mq_api_request($api_url, $payload, $require_operator_headers = false) {
    $guid = mq_generate_guid();
    $full_url = add_query_arg('ReferenceNo', $guid, $api_url);

    $headers = [
        'Content-Type' => 'application/json',
    ];

    if ($require_operator_headers && defined('MQ_API_OPERATOR_USER') && defined('MQ_API_OPERATOR_PASS')) {
        $headers['userName'] = MQ_API_OPERATOR_USER;
        $headers['password'] = MQ_API_OPERATOR_PASS;
        error_log('Magnaquest API Request: Appending administrative Operator headers from wp-config.php');
    }

    error_log('Magnaquest Request URL: ' . $full_url);
    error_log('Magnaquest Request Payload: ' . wp_json_encode($payload));

    $response = wp_remote_post($full_url, [
        'body'    => wp_json_encode($payload),
        'headers' => $headers,
        'timeout' => 30,
    ]);

    if (is_wp_error($response)) {
        error_log('Magnaquest Remote Connection Error: ' . $response->get_error_message());
        return $response;
    }

    $status_code = wp_remote_retrieve_response_code($response);
    $response_body_str = wp_remote_retrieve_body($response);
    error_log('Magnaquest Response Code: ' . $status_code);
    error_log('Magnaquest Response Body: ' . $response_body_str);

    $response_body = json_decode($response_body_str, true);

    return [
        'status_code' => $status_code,
        'body'        => is_array($response_body) ? $response_body : [],
    ];
}

/**
 * Handle User Login AJAX
 */
add_action('wp_ajax_nopriv_mq_login', 'handle_mq_login');
add_action('wp_ajax_mq_login', 'handle_mq_login');

function handle_mq_login() {
    ob_start();
    mq_register_shutdown_handler('Login');

    check_ajax_referer('mq_auth_nonce', 'security');

    // FIX (2026-07-18): field renamed from "username" to "login_username" in
    // login.php — a sitewide "username already taken" script was still
    // colliding with the login form even after the id-only fix, so the
    // name attribute (what's actually submitted here) got renamed too.
    $username = sanitize_email($_POST['login_username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        if (ob_get_level()) { ob_end_clean(); }
        wp_send_json_error(['message' => 'Username and password are required.']);
    }

    $payload = [
        'login' => [
            'userName' => $username,
            'password' => $password
        ]
    ];

    $response = mq_api_request(MQ_API_LOGIN_URL, $payload);

    if (is_wp_error($response)) {
        if (ob_get_level()) { ob_end_clean(); }
        wp_send_json_error(['message' => 'Magnaquest authentication server is currently unreachable. Please try again later.']);
        return;
    }

    $status_code = $response['status_code'];
    $response_body = $response['body'];

    $login_successful = false;
    $response_message = '';
    $user_id_from_api = '';

    if ($status_code == 200 && isset($response_body['status']['errorNo']) && $response_body['status']['errorNo'] == 0) {
        $login_successful = true;
        $user_id_from_api = $response_body['data']['userInfo']['user_id'] ?? 'mq_user_' . time();
        $response_message = $response_body['status']['message'] ?? 'Login successful';
        
        // Save JWT token object in session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!empty($response_body['data']['accessToken'])) {
            $_SESSION['selfcareJWT'] = wp_json_encode([
                'expiresIn'    => $response_body['data']['expiresIn'] ?? '1440',
                'accessToken'  => $response_body['data']['accessToken'] ?? '',
                'refreshToken' => $response_body['data']['refreshToken'] ?? '',
                'userType'     => $response_body['data']['userType'] ?? 'C',
                'userDescr'    => $response_body['data']['userDescr'] ?? ''
            ]);
        }
    } else {
        $response_message = $response_body['status']['message'] ?? 'Authentication failed.';
    }

    if ($login_successful) {
        // Sync user with WordPress so that the user is logged into WP
        $wp_user = null;
        if (email_exists($username)) {
            $wp_user = get_user_by('email', $username);
        } elseif (username_exists($username)) {
            $wp_user = get_user_by('login', $username);
        }

        if (!$wp_user) {
            // Create a WordPress user dynamically for this subscriber if they don't exist
            $new_user_id = wp_create_user($username, $password, $username); // Set WP password to their typed password
            if (!is_wp_error($new_user_id)) {
                $wp_user = get_userdata($new_user_id);
                $wp_user->set_role('subscriber');
            } else {
                error_log('Failed to dynamically create WordPress user on login: ' . $new_user_id->get_error_message());
            }
        } else {
            // Update local password to match their Magnaquest password if different
            if (!wp_check_password($password, $wp_user->user_pass, $wp_user->ID)) {
                wp_set_password($password, $wp_user->ID);
            }
        }

        // Authenticate the user in WordPress
        if ($wp_user) {
            wp_clear_auth_cookie();
            wp_set_current_user($wp_user->ID);
            wp_set_auth_cookie($wp_user->ID, true);
            update_user_caches($wp_user);
        }

        // Set secure cookie for Magnaquest session
        $cookie_val = $user_id_from_api ? $user_id_from_api : 'mq_user_' . ($wp_user ? $wp_user->ID : time());
        setcookie('mq_session_token', base64_encode($cookie_val . ':' . $username), time() + (86400 * 30), "/", "", is_ssl(), true);

        $redirect_to = !empty($_POST['redirect_to']) ? esc_url_raw($_POST['redirect_to']) : '';
        $redirect_to = wp_validate_redirect($redirect_to, home_url('/my-account'));

        if (ob_get_level()) { ob_end_clean(); }
        wp_send_json_success([
            'message' => $response_message,
            'redirect' => $redirect_to,
            'tokenObject' => [
                'expiresIn'    => $response_body['data']['expiresIn'] ?? '1440',
                'accessToken'  => $response_body['data']['accessToken'] ?? '',
                'refreshToken' => $response_body['data']['refreshToken'] ?? '',
                'userType'     => $response_body['data']['userType'] ?? 'C',
                'userDescr'    => $response_body['data']['userDescr'] ?? ''
            ]
        ]);
    } else {
        if (ob_get_level()) { ob_end_clean(); }
        wp_send_json_error(['message' => $response_message]);
    }
}

/**
 * Handle User Registration AJAX
 */
add_action('wp_ajax_nopriv_mq_register', 'handle_mq_register');
add_action('wp_ajax_mq_register', 'handle_mq_register');

function handle_mq_register() {
    ob_start();
    mq_register_shutdown_handler('Register');

    check_ajax_referer('mq_auth_nonce', 'security');

    $first_name = sanitize_text_field($_POST['firstName']);
    $last_name = sanitize_text_field($_POST['lastName']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $password = $_POST['password'];

    if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        if (ob_get_level()) { ob_end_clean(); }
        wp_send_json_error(['message' => 'All fields are required.']);
    }

    // Check if user already exists in WordPress
    if (email_exists($email) || username_exists($email)) {
        if (ob_get_level()) { ob_end_clean(); }
        wp_send_json_error(['message' => 'An account with this email address already exists. Please login instead.']);
        return;
    }

    $payload = [
        'CustomerInfo' => [
            'customerType' => '',
            'category' => '',
            'individual' => 'Y',
            'title' => 'Mr.',
            'firstName' => $first_name,
            'middleName' => '',
            'lastName' => $last_name,
            'opEntity' => 'HO',
            'currencyCode' => 'NGN',
            'billingMedia' => 'EP',
            'referralCode' => null,
            'giftCouponCode' => null,
            'parent' => 'N',
            'groupId' => null,
            'gender' => 'M',
            'billingMode' => 'P',
            'contactInfo' => [
                'email' => $email,
                'mobilePhone' => $phone ? $phone : null
            ],
            'userInfo' => [
                'userName' => $email,
                'password' => $password
            ],
            'addressInfo' => [
                [
                    'addressTypeCode' => 'PRI',
                    'address1' => '',
                    'address2' => '',
                    'street' => '',
                    'area' => '',
                    'city' => '',
                    'district' => '',
                    'state' => '',
                    'country' => 'Nigeria',
                    'zipCode' => '',
                    'location' => ''
                ],
                [
                    'addressTypeCode' => 'BIL',
                    'address1' => '',
                    'address2' => '',
                    'street' => '',
                    'area' => '',
                    'city' => '',
                    'district' => '',
                    'state' => '',
                    'country' => 'Nigeria',
                    'zipCode' => '',
                    'location' => ''
                ]
            ],
            'consentInfo' => [
                'privPolcy' => 'Y',
                'termUse' => 'Y'
            ],
            'socialMediaInfo' => [
                'provider' => '',
                'external_id' => '',
                'name' => '',
                'email' => ''
            ],
            'flexAttributeInfo' => (object)[]
        ]
    ];

    $response = mq_api_request(MQ_API_REGISTER_URL, $payload, true);

    if (is_wp_error($response)) {
        if (ob_get_level()) { ob_end_clean(); }
        wp_send_json_error(['message' => 'Magnaquest registration server is currently unreachable. Please try again later.']);
        return;
    }

    $status_code = $response['status_code'];
    $response_body = $response['body'];

    $register_successful = false;
    $response_message = '';

    $error_no = null;
    $message = 'Customer added successfully';

    // Possible response structures (may differ between environments)
    if (isset($response_body['status']['errorNo'])) {
        $error_no = $response_body['status']['errorNo'];
        $message = $response_body['status']['message'] ?? $message;
    }
    elseif (isset($response_body['errorNo'])) {
        $error_no = $response_body['errorNo'];
        $message = $response_body['message'] ?? $message;
    }
    elseif (isset($response_body['Status']['ErrorNo'])) {
        $error_no = $response_body['Status']['ErrorNo'];
        $message = $response_body['Status']['Message'] ?? $message;
    }

    // Treat HTTP 200 as success if API says success
    if ($status_code == 200 && ($error_no === 0 || $error_no === '0')) {
        $register_successful = true;
        $response_message = $message;

        // Save JWT token object in session from register response
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $jwt_data = $response_body['data']['jwttoken'] ?? [];
        if (!empty($jwt_data['accessToken'])) {
            $_SESSION['selfcareJWT'] = wp_json_encode([
                'expiresIn'    => $jwt_data['expiresIn'] ?? '1440',
                'accessToken'  => $jwt_data['accessToken'] ?? '',
                'refreshToken' => $jwt_data['refreshToken'] ?? '',
                'userType'     => $jwt_data['userType'] ?? $response_body['data']['userType'] ?? 'C',
                'userDescr'    => $jwt_data['userDescr'] ?? $response_body['data']['userDescr'] ?? trim($first_name . ' ' . $last_name)
            ]);
        }

        // Create local WordPress user and log them in safely
        try {
            $new_user_id = wp_create_user($email, $password, $email);

            if (is_wp_error($new_user_id)) {
                // If the user was created during the API call (e.g. via webhook/sync), retrieve them instead
                if ($new_user_id->get_error_code() === 'existing_user_email' || $new_user_id->get_error_code() === 'existing_user_login') {
                    $existing_user = get_user_by('email', $email);
                    if ($existing_user) {
                        $new_user_id = $existing_user->ID;
                        // Update local password to match their Magnaquest password if different
                        if (!wp_check_password($password, $existing_user->user_pass, $existing_user->ID)) {
                            wp_set_password($password, $existing_user->ID);
                        }
                    } else {
                        throw new \Exception('wp_create_user failed: ' . $new_user_id->get_error_message());
                    }
                } else {
                    throw new \Exception('wp_create_user failed: ' . $new_user_id->get_error_message());
                }
            }

            $update_result = wp_update_user([
                'ID' => $new_user_id,
                'first_name' => $first_name,
                'last_name' => $last_name
            ]);

            if (is_wp_error($update_result)) {
                throw new \Exception('wp_update_user failed: ' . $update_result->get_error_message());
            }

            $wp_user = get_userdata($new_user_id);
            if ($wp_user) {
                $wp_user->set_role('subscriber');
            } else {
                throw new \Exception('Failed to retrieve user data after creation');
            }

            // Automatically log the user in locally on success
            wp_clear_auth_cookie();
            wp_set_current_user($new_user_id);
            wp_set_auth_cookie($new_user_id, true);
            update_user_caches($wp_user);

        } catch (\Throwable $e) {
            error_log('FATAL ERROR during local user registration sync: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            error_log('Stack trace: ' . $e->getTraceAsString());
            
            if (ob_get_level()) { ob_end_clean(); }
            wp_send_json_error([
                'message' => 'Local database/sync error: ' . $e->getMessage() . ' in ' . basename($e->getFile()) . ':' . $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return;
        }
    } else {
        // Full debug logging
        error_log('Magnaquest Registration Failed Response: ' . print_r($response_body, true));

        $api_error =
            $response_body['status']['message'] ??
            $response_body['message'] ??
            $response_body['Status']['Message'] ??
            'API registration failed';

        if (ob_get_level()) { ob_end_clean(); }
        wp_send_json_error([
            'message' => $api_error,
            'debug_response' => $response_body
        ]);
        return;
    }

    $redirect_to = !empty($_POST['redirect_to']) ? esc_url_raw($_POST['redirect_to']) : '';
    $redirect_to = wp_validate_redirect($redirect_to, home_url('/'));

    if ($register_successful) {
        if (ob_get_level()) { ob_end_clean(); }
        $jwt_data = $response_body['data']['jwttoken'] ?? [];
        wp_send_json_success([
            'message' => $response_message,
            'redirect' => $redirect_to,
            'tokenObject' => [
                'expiresIn'    => $jwt_data['expiresIn'] ?? '1440',
                'accessToken'  => $jwt_data['accessToken'] ?? '',
                'refreshToken' => $jwt_data['refreshToken'] ?? '',
                'userType'     => $jwt_data['userType'] ?? $response_body['data']['userType'] ?? 'C',
                'userDescr'    => $jwt_data['userDescr'] ?? $response_body['data']['userDescr'] ?? trim($first_name . ' ' . $last_name)
            ]
        ]);
    } else {
        if (ob_get_level()) { ob_end_clean(); }
        wp_send_json_error(['message' => $response_message]);
    }
}

/**
 * Handle Request Password Reset (Forgot Password) AJAX
 */
add_action('wp_ajax_nopriv_mq_request_reset_password', 'handle_mq_request_reset_password');
add_action('wp_ajax_mq_request_reset_password', 'handle_mq_request_reset_password');

function handle_mq_request_reset_password() {
    ob_start();
    check_ajax_referer('mq_auth_nonce', 'security');

    $email = sanitize_email($_POST['email'] ?? '');

    if (empty($email)) {
        if (ob_get_level()) { ob_end_clean(); }
        wp_send_json_error(['message' => 'Email address is required.']);
    }

    $payload = [
    	'email' => $email
    ];

    $response = mq_api_request(MQ_API_SEND_PASSWORD_RESET_LINK_URL, $payload,true);

    if (is_wp_error($response)) {
        if (ob_get_level()) { ob_end_clean(); }
        wp_send_json_error(['message' => 'Magnaquest password server API is currently unreachable. Please try again later.']);
        return;
    }

    $status_code = $response['status_code'];
    $response_body = is_array($response['body']) ? $response['body'] : json_decode($response['body'], true);

    if ($status_code == 200 && isset($response_body['status']['errorNo']) && (int)$response_body['status']['errorNo'] === 0) 
    {
    	if (ob_get_level()) {
        	ob_end_clean();
    }

    wp_send_json_success([
        	'message' => $response_body['status']['message']
    	]);

    } else {
     	if (ob_get_level()) {
        	ob_end_clean();
    }

    wp_send_json_error([
        	'message' => $response_body['status']['message'] ?? 'Unable to process password reset request.'
    	]);
     }
}

/**
 * Helper to decode JWT payload and extract the user's email address
 */
function mq_decode_jwt_email($token) {
    $parts = explode('.', $token);
    if (count($parts) < 2) {
        return '';
    }
    $payload_b64 = $parts[1];
    $remainder = strlen($payload_b64) % 4;
    if ($remainder) {
        $payload_b64 .= str_repeat('=', 4 - $remainder);
    }
    $payload_b64 = str_replace(['-', '_'], ['+', '/'], $payload_b64);
    $payload = json_decode(base64_decode($payload_b64), true);
    if (!$payload) {
        return '';
    }
    
    $user_info = $payload['userinfo'] ?? $payload['userInfo'] ?? null;
    if ($user_info) {
        if (is_string($user_info)) {
            $user_info_data = json_decode($user_info, true);
        } else {
            $user_info_data = $user_info;
        }
        if (is_array($user_info_data)) {
            $email = $user_info_data['username'] ?? $user_info_data['userName'] ?? $user_info_data['email'] ?? '';
            if (!empty($email)) {
                return $email;
            }
        }
    }
    
    return $payload['username'] ?? $payload['userName'] ?? $payload['email'] ?? '';
}

/**
 * Handle Confirm Reset Password AJAX
 */
add_action('wp_ajax_nopriv_mq_confirm_reset_password', 'handle_mq_confirm_reset_password');
add_action('wp_ajax_mq_confirm_reset_password', 'handle_mq_confirm_reset_password');

function handle_mq_confirm_reset_password() {
    ob_start();
    check_ajax_referer('mq_auth_nonce', 'security');

    $token = sanitize_text_field($_POST['token']);
    $new_password = $_POST['new_password'];

    if (empty($token) || empty($new_password)) {
        if (ob_get_level()) { ob_end_clean(); }
        wp_send_json_error(['message' => 'Token and new password are required.']);
        return;
    }

    // Step 1: Decode JWT token to get email
    $email = mq_decode_jwt_email($token);
    if (empty($email)) {
        if (is_email($token)) {
            $email = $token;
        } else {
            if (ob_get_level()) { ob_end_clean(); }
            wp_send_json_error(['message' => 'Invalid password reset token payload.']);
            return;
        }
    }

    // Step 2: Retrieve user_id from FindAppuserByLogintypeAndLoginName
    $find_payload = [
        'findAppUserByLoginOptions' => [
            'loginName' => $email,
            'loginType' => 'E'
        ]
    ];

    $find_response = mq_api_request(MQ_API_FIND_APPUSER_URL, $find_payload, true);

    if (is_wp_error($find_response) || $find_response['status_code'] != 200) {
        if (ob_get_level()) { ob_end_clean(); }
        wp_send_json_error(['message' => 'Failed to reach Magnaquest server to verify user.']);
        return;
    }

    $find_body = $find_response['body'];
    if (!isset($find_body['status']['errorNo']) || $find_body['status']['errorNo'] != 0) {
        $error_msg = $find_body['status']['message'] ?? 'Failed to retrieve user details from Magnaquest.';
        if (ob_get_level()) { ob_end_clean(); }
        wp_send_json_error(['message' => $error_msg]);
        return;
    }

    $user_id = $find_body['data']['userInfo']['user_id'] ?? '';
    if (empty($user_id)) {
        if (ob_get_level()) { ob_end_clean(); }
        wp_send_json_error(['message' => 'User ID not found for this account on Magnaquest.']);
        return;
    }

    // Step 3: Call SelfcareResetPassword using user_id
    $reset_payload = [
        'selfcareResetPassword' => [
            'userId' => intval($user_id),
            'newPassword' => $new_password,
            'confirmPassword' => $new_password
        ]
    ];

    $response = mq_api_request(MQ_API_SELFCARE_RESET_PASSWORD_URL, $reset_payload, true);

    if (is_wp_error($response)) {
        if (ob_get_level()) { ob_end_clean(); }
        wp_send_json_error(['message' => 'Magnaquest password reset server is currently unreachable. Please try again later.']);
        return;
    }

    $status_code = $response['status_code'];
    $response_body = $response['body'];

    if ($status_code == 200 && isset($response_body['status']['errorNo']) && $response_body['status']['errorNo'] == 0) {
        if (ob_get_level()) { ob_end_clean(); }
        wp_send_json_success(['message' => 'Your password has been successfully reset! Redirecting to login...']);
    } else {
        $error_msg = $response_body['status']['message'] ?? 'Unable to reset password on Magnaquest.';
        if (ob_get_level()) { ob_end_clean(); }
        wp_send_json_error(['message' => $error_msg]);
    }
}

/**
 * Handle Change Password (when logged in) AJAX
 */
add_action('wp_ajax_mq_change_password', 'handle_mq_change_password');

function handle_mq_change_password() {
    ob_start();
    check_ajax_referer('mq_auth_nonce', 'security');

    if (!is_user_logged_in()) {
        if (ob_get_level()) { ob_end_clean(); }
        wp_send_json_error(['message' => 'You must be logged in to change your password.']);
    }

    $current_user = wp_get_current_user();
    $email = $current_user->user_email;
    
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];

    if (empty($old_password) || empty($new_password)) {
        if (ob_get_level()) { ob_end_clean(); }
        wp_send_json_error(['message' => 'Both old and new passwords are required.']);
    }

    $payload = [
        'changePassword' => [
            'userName'          => $email,
            'oldPassword'       => $old_password,
            'password'          => $new_password,
            'confirmPassword'   => $new_password
        ]
    ];

    $response = mq_api_request(MQ_API_CHANGE_PASSWORD_URL, $payload, true);

    if (is_wp_error($response)) {
        if (ob_get_level()) { ob_end_clean(); }
        wp_send_json_error(['message' => 'Magnaquest password server is currently unreachable. Please try again later.']);
        return;
    }

    $status_code = $response['status_code'];
    $response_body = $response['body'];

    $change_successful = false;
    $response_message = '';

    if ($status_code == 200 && isset($response_body['status']['errorNo']) && $response_body['status']['errorNo'] == 0) {
        $change_successful = true;
        $response_message = $response_body['status']['message'] ?? 'Password changed successfully';
        
        // Also update local WP password if it's different
        if (!wp_check_password($new_password, $current_user->user_pass, $current_user->ID)) {
            wp_set_password($new_password, $current_user->ID);
        }

        // Refresh session JWT
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['selfcareJWT'] = wp_json_encode([
            'expiresIn'    => $response_body['data']['expiresIn'] ?? '1440',
            'accessToken'  => $response_body['data']['accessToken'] ?? '',
            'refreshToken' => $response_body['data']['refreshToken'] ?? '',
            'userType'     => $response_body['data']['userType'] ?? 'C',
            'userDescr'    => $response_body['data']['userDescr'] ?? ''
        ]);
    } else {
        $response_message = $response_body['status']['message'] ?? 'Password change failed.';
    }

    if ($change_successful) {
        // Re-authenticate user so they don't get logged out of WP
        wp_clear_auth_cookie();
        wp_set_current_user($current_user->ID);
        wp_set_auth_cookie($current_user->ID, true);
        update_user_caches($current_user);

        if (ob_get_level()) { ob_end_clean(); }
        wp_send_json_success([
            'message' => $response_message,
            'tokenObject' => [
                'expiresIn'    => $response_body['data']['expiresIn'] ?? '1440',
                'accessToken'  => $response_body['data']['accessToken'] ?? '',
                'refreshToken' => $response_body['data']['refreshToken'] ?? '',
                'userType'     => $response_body['data']['userType'] ?? 'C',
                'userDescr'    => $response_body['data']['userDescr'] ?? ''
            ]
        ]);
    } else {
        if (ob_get_level()) { ob_end_clean(); }
        wp_send_json_error(['message' => $response_message]);
    }
}

/**
 * Intercept all standard WordPress login flows and validate credentials against Magnaquest API.
 * This ensures that standard login forms (like WooCommerce, wp-login.php, etc.) do not bypass Magnaquest.
 */
function mq_custom_authenticate($user, $username, $password) {
    // If username or password is empty, let default handlers return the empty error
    if (empty($username) || empty($password)) {
        return $user;
    }

    // FIX (2026-07-18): re-applying a fix that was lost when this theme folder
    // was rebuilt from a fresh staging pull earlier — the hardcoded allowlist
    // here had regressed to only 'administrator'/'editor', which routes any
    // other staff role (author, wpseo_manager, bddraft, bdeditor, wpseo_editor)
    // through the Magnaquest customer-login API instead of local WP auth.
    // Since those are internal-only accounts with no Magnaquest record, this
    // broke login for them ("user not found" despite a correct password).
    // Keep this list in sync with $staff_roles in bd_hide_admin_bar_for_non_staff()
    // in functions.php.
    $local_user = get_user_by('login', $username);
    if (!$local_user) {
        $local_user = get_user_by('email', $username);
    }

    if ($local_user) {
        $staff_roles = ['administrator', 'editor', 'author', 'wpseo_manager', 'bddraft', 'bdeditor', 'wpseo_editor'];
        if (array_intersect($staff_roles, (array) $local_user->roles)) {
            return $user; // Allow staff accounts to log in using WP local authentication
        }
    }

    // Determine the user's email to pass to Magnaquest
    $email = $username;
    if ($local_user) {
        $email = $local_user->user_email;
    }

    // Call Magnaquest Login API
    $payload = [
        'login' => [
            'userName' => $email,
            'password' => $password
        ]
    ];

    $response = mq_api_request(MQ_API_LOGIN_URL, $payload);

    if (is_wp_error($response)) {
        // If Magnaquest API is down, fallback to local DB validation
        error_log('Magnaquest Login API is down during authenticate. Falling back to local check.');
        return $user;
    }

    $status_code = $response['status_code'];
    $response_body = $response['body'];

    $login_successful = false;
    $user_id_from_api = '';
    $response_message = '';

    if ($status_code == 200 && isset($response_body['status']['errorNo']) && $response_body['status']['errorNo'] == 0) {
        $login_successful = true;
        $user_id_from_api = $response_body['data']['userInfo']['user_id'] ?? 'mq_user_' . time();
        $response_message = $response_body['status']['message'] ?? 'Login successful';
        
        // Save JWT token in session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!empty($response_body['data']['accessToken'])) {
            $user_descr = $response_body['data']['userDescr'] ?? '';
            if (empty($user_descr) && $local_user) {
                $user_descr = trim($local_user->first_name . ' ' . $local_user->last_name);
                if (empty($user_descr)) {
                    $user_descr = $local_user->display_name;
                }
            }
            $_SESSION['selfcareJWT'] = wp_json_encode([
                'expiresIn'    => $response_body['data']['expiresIn'] ?? '1440',
                'accessToken'  => $response_body['data']['accessToken'] ?? '',
                'refreshToken' => $response_body['data']['refreshToken'] ?? '',
                'userType'     => $response_body['data']['userType'] ?? 'C',
                'userDescr'    => $user_descr
            ]);
        }
    } else {
        $response_message = $response_body['status']['message'] ?? 'Authentication failed on Magnaquest.';
    }

    if (!$login_successful) {
        // Magnaquest rejected the login, block entry
        return new WP_Error('mq_login_failed', $response_message);
    }

    // Sync or create local user
    if (!$local_user) {
        if (email_exists($email)) {
            $local_user = get_user_by('email', $email);
        } elseif (username_exists($email)) {
            $local_user = get_user_by('login', $email);
        }
    }

    if (!$local_user) {
        // Create user dynamically if they exist on MQ but not locally in WP
        $new_user_id = wp_create_user($email, $password, $email);
        if (!is_wp_error($new_user_id)) {
            $local_user = get_userdata($new_user_id);
            $local_user->set_role('subscriber');
        } else {
            return $new_user_id; // Return WP_Error
        }
    } else {
        // Update local password to match their Magnaquest password if different
        if (!wp_check_password($password, $local_user->user_pass, $local_user->ID)) {
            wp_set_password($password, $local_user->ID);
        }
    }

    // Set secure cookie for Magnaquest session
    $cookie_val = $user_id_from_api ? $user_id_from_api : 'mq_user_' . $local_user->ID;
    setcookie('mq_session_token', base64_encode($cookie_val . ':' . $email), time() + (86400 * 30), "/", "", is_ssl(), true);

    return $local_user;
}

add_filter('authenticate', 'mq_custom_authenticate', 30, 3);

/**
 * Initialize PHP Session early on 'init' hook so that $_SESSION is accessible throughout WordPress.
 */
function mq_init_session() {
    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }
}
add_action('init', 'mq_init_session', 1);

/**
 * Clean up Magnaquest JWT access token when the user logs out.
 */
function mq_custom_logout() {
    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }
    if (isset($_SESSION['selfcareJWT'])) {
        unset($_SESSION['selfcareJWT']);
    }
}
add_action('wp_logout', 'mq_custom_logout');

