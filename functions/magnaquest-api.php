<?php
/**
 * Magnaquest API Integration
 * Handles proxying authentication, registration, and password changes to Magnaquest APIs.
 */

@ini_set('display_errors', 0);

/**
 * Theme environment (live/staging) config lookup — see functions/features.php for the
 * "Theme Environment" admin settings page that sets the bd_theme_environment option.
 * Centralizes every Magnaquest/domain URL that used to be a hardcoded literal here (and in
 * functions.php, header.php, and the magnaquest template-parts) so switching between the
 * live and staging Magnaquest environments no longer requires editing PHP by hand.
 *
 * @return string 'live' or 'staging' (defaults to 'staging' to match this codebase's
 *                previous hardcoded default).
 */
function bd_get_theme_environment() {
    $opts = get_option('bd_theme_environment', []);
    $env = is_array($opts) && isset($opts['environment']) ? $opts['environment'] : 'staging';
    return $env === 'live' ? 'live' : 'staging';
}

/**
 * Resolve an environment-sensitive URL by key. See bd_get_theme_environment() above.
 *
 * @param string $key One of: login, register, change_password, forgot_password,
 *                     reset_password, find_appuser, selfcare_reset_password,
 *                     send_password_reset_link, selfcare_origin, checkout_origin,
 *                     home_url, login_page_url, home_dropdown_url.
 * @return string
 */
function bd_get_env_url($key) {
    $env = bd_get_theme_environment();
    $urls = [
        'login' => [
            'live'    => 'https://businessday.magnaquest.com/WebApi/Restapi/Login',
            'staging' => 'https://businessdaytest.magnaquest.com/WebApi/Restapi/Login',
        ],
        'register' => [
            'live'    => 'https://businessday.magnaquest.com/WebApi/Restapi/CreateCustomer',
            'staging' => 'https://businessdaytest.magnaquest.com/WebApi/Restapi/CreateCustomer',
        ],
        'change_password' => [
            'live'    => 'https://businessday.magnaquest.com/WebApi/Restapi/ChangePassword',
            'staging' => 'https://businessdaytest.magnaquest.com/WebApi/Restapi/ChangePassword',
        ],
        'forgot_password' => [
            'live'    => 'https://businessday.magnaquest.com/WebApi/Restapi/ForgotPassword',
            'staging' => 'https://businessdaytest.magnaquest.com/WebApi/Restapi/ForgotPassword',
        ],
        'reset_password' => [
            'live'    => 'https://businessday.magnaquest.com/WebApi/Restapi/ResetPassword',
            'staging' => 'https://businessdaytest.magnaquest.com/WebApi/Restapi/ResetPassword',
        ],
        'find_appuser' => [
            'live'    => 'https://businessday.magnaquest.com/WebApi/Restapi/FindAppuserByLogintypeAndLoginName',
            'staging' => 'https://businessdaytest.magnaquest.com/WebApi/Restapi/FindAppuserByLogintypeAndLoginName',
        ],
        'selfcare_reset_password' => [
            'live'    => 'https://businessday.magnaquest.com/WebApi/Restapi/SelfcareResetPassword',
            'staging' => 'https://businessdaytest.magnaquest.com/WebApi/Restapi/SelfcareResetPassword',
        ],
        'send_password_reset_link' => [
            'live'    => 'https://businessday.magnaquest.com/WebApi/Restapi/SendPasswordResetLink',
            'staging' => 'https://businessdaytest.magnaquest.com/WebApi/Restapi/SendPasswordResetLink',
        ],
        // Selfcare/checkout origins and site URLs used outside the WebApi/Restapi endpoints above.
        'selfcare_origin' => [
            'live'    => 'https://businessday-selfcare.magnaquest.com',
            'staging' => 'https://businessdaytest-selfcare.magnaquest.com',
        ],
        'checkout_origin' => [
            'live'    => 'https://businessday.magnaquest.com',
            'staging' => 'https://businessdaytest.magnaquest.com',
        ],
        'home_url' => [
            'live'    => 'https://businessday.ng/',
            'staging' => 'https://stg18326.businessday.ng/',
        ],
        'login_page_url' => [
            'live'    => 'https://businessday.ng/Login/',
            'staging' => 'https://stg18326.businessday.ng/Login/',
        ],
        // No trailing slash — used for the header.php user-menu "Home" dropdown link.
        'home_dropdown_url' => [
            'live'    => 'https://businessday.ng',
            'staging' => 'https://stg18326.businessday.ng',
        ],
    ];

    return $urls[$key][$env] ?? '';
}

// Magnaquest API Endpoints (environment-driven — see bd_get_env_url() above).
// Previously hardcoded to the staging domain (businessdaytest.magnaquest.com) here.
if (!defined('MQ_API_LOGIN_URL')) {
    define('MQ_API_LOGIN_URL', bd_get_env_url('login'));
}
if (!defined('MQ_API_REGISTER_URL')) {
    define('MQ_API_REGISTER_URL', bd_get_env_url('register'));
}
if (!defined('MQ_API_CHANGE_PASSWORD_URL')) {
    define('MQ_API_CHANGE_PASSWORD_URL', bd_get_env_url('change_password'));
}
if (!defined('MQ_API_FORGOT_PASSWORD_URL')) {
    define('MQ_API_FORGOT_PASSWORD_URL', bd_get_env_url('forgot_password'));
}
if (!defined('MQ_API_RESET_PASSWORD_URL')) {
    define('MQ_API_RESET_PASSWORD_URL', bd_get_env_url('reset_password'));
}
if (!defined('MQ_API_FIND_APPUSER_URL')) {
    define('MQ_API_FIND_APPUSER_URL', bd_get_env_url('find_appuser'));
}
if (!defined('MQ_API_SELFCARE_RESET_PASSWORD_URL')) {
    define('MQ_API_SELFCARE_RESET_PASSWORD_URL', bd_get_env_url('selfcare_reset_password'));
}

if (!defined('MQ_API_SEND_PASSWORD_RESET_LINK_URL')) {
    define('MQ_API_SEND_PASSWORD_RESET_LINK_URL', bd_get_env_url('send_password_reset_link'));
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
    } elseif ($require_operator_headers) {
        // DIAGNOSTIC: operator headers were requested but MQ_API_OPERATOR_USER/PASS aren't
        // defined (they must live in wp-config.php on the server, not this repo) -- the
        // request goes out with no operator credentials at all. Added while investigating
        // why bd_get_subscription_status_by_email()'s FindAppuser/GetCustomerDetails calls
        // may be silently failing.
        error_log('Magnaquest API Request: Operator headers requested but MQ_API_OPERATOR_USER/MQ_API_OPERATOR_PASS are not defined -- request sent without them.');
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

            // Sync subscription status immediately on successful login
            bd_sync_user_subscription_from_magnaquest($wp_user->ID, true);
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

    // Sync subscription status immediately on successful login
    bd_sync_user_subscription_from_magnaquest($local_user->ID, true);

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

/**
 * Check if the user has an active subscription.
 *
 * @param int $user_id The WordPress User ID.
 * @return bool True if the user has an active premium/standard subscription, false otherwise.
 */
function bd_user_has_active_subscription( $user_id ) {
    if ( ! $user_id ) {
        return false;
    }

    // 1. Staff check (Administrators, editors, etc. bypass subscription check)
    $user = get_userdata( $user_id );
    if ( $user ) {
        $staff_roles = ['administrator', 'editor', 'author', 'wpseo_manager', 'bddraft', 'bdeditor', 'wpseo_editor'];
        if ( array_intersect( $staff_roles, (array) $user->roles ) ) {
            return true;
        }
    }

    // 2. Perform background sync from Magnaquest if token is available and cache is expired
    bd_sync_user_subscription_from_magnaquest( $user_id );

    // 3. Check local Leaky Paywall metadata
    $status      = strtolower( get_user_meta( $user_id, '_issuem_leaky_paywall_live_payment_status', true ) );
    $level_id    = get_user_meta( $user_id, '_issuem_leaky_paywall_live_level_id', true );
    $description = strtolower( get_user_meta( $user_id, '_issuem_leaky_paywall_live_description', true ) );
    $expires     = get_user_meta( $user_id, '_issuem_leaky_paywall_live_expires', true );

    if ( $status === 'active' && $level_id != '4' && strpos( $description, 'free' ) === false ) {
        if ( empty( $expires ) || strtotime( $expires ) > time() ) {
            return true;
        }
    }

    return false;
}

/**
 * Sync user subscription data from Magnaquest and update local Leaky Paywall metadata.
 *
 * @param int  $user_id The WordPress User ID.
 * @param bool $force   Optional. Force sync bypassing the cache.
 */
function bd_sync_user_subscription_from_magnaquest( $user_id, $force = false ) {
    if ( session_status() === PHP_SESSION_NONE && ! headers_sent() ) {
        session_start();
    }

    if ( empty( $_SESSION['selfcareJWT'] ) ) {
        return;
    }

    $jwt_data = json_decode( $_SESSION['selfcareJWT'], true );
    $accessToken = $jwt_data['accessToken'] ?? '';
    if ( empty( $accessToken ) ) {
        return;
    }

    // Decode JWT to retrieve customerNo
    $parts = explode( '.', $accessToken );
    if ( count( $parts ) < 2 ) {
        return;
    }
    $payload_b64 = $parts[1];
    $payload = json_decode( base64_decode( str_replace( ['-', '_'], ['+', '/'], $payload_b64 ) ), true );
    $customerNo = $payload['customerNo'] ?? '';
    if ( empty( $customerNo ) ) {
        return;
    }

    // Cache check: only check every 12 hours unless forced (reduces AWS cost / resource usage)
    $last_checked = get_user_meta( $user_id, '_mq_subscription_last_checked', true );
    if ( ! $force && ! empty( $last_checked ) && ( time() - $last_checked ) < 43200 ) {
        return;
    }

    // Query GetCustomerDetails API
    if (!defined('MQ_API_GET_CUSTOMER_DETAILS_URL')) {
        define('MQ_API_GET_CUSTOMER_DETAILS_URL', 'https://businessday.magnaquest.com/WebApi/Restapi/GetCustomerDetails');
    }

    $guid = mq_generate_guid();
    $full_url = add_query_arg('ReferenceNo', $guid, MQ_API_GET_CUSTOMER_DETAILS_URL);

    $headers = [
        'Content-Type'  => 'application/json',
        'Authorization' => 'Bearer ' . $accessToken
    ];

    $payload_req = [
        'customerNo' => $customerNo
    ];

    error_log('Magnaquest GetCustomerDetails Request for User: ' . $user_id);

    $response = wp_remote_post( $full_url, [
        'body'    => wp_json_encode( $payload_req ),
        'headers' => $headers,
        'timeout' => 20,
    ] );

    if ( is_wp_error( $response ) ) {
        error_log('Magnaquest GetCustomerDetails Connection Error: ' . $response->get_error_message());
        return;
    }

    $status_code = wp_remote_retrieve_response_code( $response );
    $response_body_str = wp_remote_retrieve_body( $response );

    if ( $status_code !== 200 ) {
        error_log('Magnaquest GetCustomerDetails HTTP Error: ' . $status_code);
        return;
    }

    $response_body = json_decode( $response_body_str, true );
    if ( ! is_array( $response_body ) ) {
        return;
    }

    error_log('Magnaquest GetCustomerDetails Response: ' . wp_json_encode($response_body));

    $errorNo = $response_body['status']['errorNo'] ?? -1;
    if ( $errorNo === 0 && ! empty( $response_body['data']['subscriptionInfo'] ) ) {
        $sub_info = $response_body['data']['subscriptionInfo'];

        $sub_status = $sub_info['subscriptionStatus'] ?? '';
        $plan_arr = $sub_info['currentActivePlan'] ?? [];
        $plan_name = ! empty( $plan_arr ) ? $plan_arr[0] : '';
        $expiry_date = $sub_info['subscriptionExpiryDateTime'] ?? '';

        if ( strtolower( $sub_status ) === 'active' ) {
            // Update local metadata to match active subscription
            update_user_meta( $user_id, '_issuem_leaky_paywall_live_payment_status', 'active' );
            update_user_meta( $user_id, '_issuem_leaky_paywall_live_level_id', '1' ); // 1 is standard active level
            update_user_meta( $user_id, '_issuem_leaky_paywall_live_description', $plan_name );

            if ( ! empty( $expiry_date ) ) {
                $expiry_timestamp = strtotime( $expiry_date );
                if ( $expiry_timestamp !== false ) {
                    update_user_meta( $user_id, '_issuem_leaky_paywall_live_expires', date( 'Y-m-d H:i:s', $expiry_timestamp ) );
                }
            } else {
                update_user_meta( $user_id, '_issuem_leaky_paywall_live_expires', '' );
            }
        } else {
            // User does not have an active subscription
            update_user_meta( $user_id, '_issuem_leaky_paywall_live_payment_status', 'deactivated' );
            update_user_meta( $user_id, '_issuem_leaky_paywall_live_expires', date( 'Y-m-d H:i:s', time() - 3600 ) ); // Expired 1 hour ago
        }

        update_user_meta( $user_id, '_mq_subscription_last_checked', time() );
    }
}

/**
 * Handle Restore Session AJAX
 */
add_action('wp_ajax_mq_restore_session', 'handle_mq_restore_session');
add_action('wp_ajax_nopriv_mq_restore_session', 'handle_mq_restore_session');

function handle_mq_restore_session() {
    check_ajax_referer('mq_auth_nonce', 'security');

    $jwt_str = $_POST['jwt'] ?? '';
    if (empty($jwt_str)) {
        wp_send_json_error(['message' => 'JWT is empty.']);
    }

    $jwt_data = json_decode(stripslashes($jwt_str), true);
    if (empty($jwt_data) || empty($jwt_data['accessToken'])) {
        wp_send_json_error(['message' => 'Invalid JWT data.']);
    }

    // Decode JWT and verify that the email inside matches the currently logged in user (if logged in)
    $accessToken = $jwt_data['accessToken'];
    $parts = explode('.', $accessToken);
    if (count($parts) >= 2) {
        $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[1])), true);
        $user_info_raw = $payload['userinfo'] ?? '';
        if (!empty($user_info_raw)) {
            $user_info = json_decode($user_info_raw, true);
            $jwt_email = $user_info['username'] ?? '';

            if (is_user_logged_in()) {
                $current_user = wp_get_current_user();
                if (strcasecmp($current_user->user_email, $jwt_email) !== 0) {
                    wp_send_json_error(['message' => 'JWT email mismatch.']);
                }
            }
        }
    }

    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }

    $_SESSION['mq_restore_attempted'] = true;

    $_SESSION['selfcareJWT'] = wp_json_encode([
        'expiresIn'    => $jwt_data['expiresIn'] ?? '1440',
        'accessToken'  => $jwt_data['accessToken'] ?? '',
        'refreshToken' => $jwt_data['refreshToken'] ?? '',
        'userType'     => $jwt_data['userType'] ?? 'C',
        'userDescr'    => $jwt_data['userDescr'] ?? ''
    ]);

    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        // Immediately sync subscription status
        bd_sync_user_subscription_from_magnaquest($user_id, true);
    }

    wp_send_json_success(['message' => 'Session restored.']);
}

/**
 * Email-based subscription fallback check.
 *
 * bd_sync_user_subscription_from_magnaquest() (above) reads a "customerNo" field directly off
 * the top level of the decoded JWT payload, while handle_mq_restore_session() (also above)
 * decodes the *same* token's identity fields from a nested "userinfo" JSON sub-string. If
 * customerNo actually lives inside that nested blob rather than at the top level, the sync
 * function silently no-ops for every user, and gating falls back to local Leaky Paywall
 * usermeta that a Magnaquest-only subscriber never had populated -- shown up as active
 * subscribers still seeing the paywall on the e-paper.
 *
 * Per instruction, bd_sync_user_subscription_from_magnaquest() and handle_mq_restore_session()
 * above are left untouched. This is a wholly independent, additive check: it looks the
 * customer up by their WordPress account email via FindAppuserByLogintypeAndLoginName (the
 * same endpoint handle_mq_confirm_reset_password() already uses successfully, with
 * operator-level credentials -- no per-user JWT/session required), then feeds the resulting
 * user_id into GetCustomerDetails using operator headers instead of a per-user Bearer token.
 *
 * UNVERIFIED ASSUMPTION (needs a real staging test): that GetCustomerDetails accepts
 * operator-header auth the same way FindAppuser/Register/SelfcareResetPassword do, and that
 * the "user_id" FindAppuser returns is accepted as "customerNo" by GetCustomerDetails. Fails
 * safe -- any mismatch just logs and returns false, never grants access it can't confirm.
 *
 * @param string $email WordPress account email to look up.
 * @return bool True if Magnaquest confirms an active subscription for this email.
 */
function bd_get_subscription_status_by_email($email) {
    if (empty($email) || !is_email($email)) {
        return false;
    }

    $user = get_user_by('email', $email);
    if (!$user) {
        return false;
    }

    // 12h cache, same pattern as bd_sync_user_subscription_from_magnaquest(), but stored
    // under its own meta keys so it never collides with that function's writes.
    $last_checked = get_user_meta($user->ID, '_bd_email_verified_last_checked', true);
    if (!empty($last_checked) && (time() - $last_checked) < 43200) {
        return get_user_meta($user->ID, '_bd_email_verified_sub_status', true) === 'active';
    }

    $find_payload = [
        'findAppUserByLoginOptions' => [
            'loginName' => $email,
            'loginType' => 'E'
        ]
    ];

    $find_response = mq_api_request(MQ_API_FIND_APPUSER_URL, $find_payload, true);

    if (is_wp_error($find_response) || $find_response['status_code'] != 200) {
        error_log('bd_get_subscription_status_by_email: FindAppuser request failed for ' . $email);
        return false;
    }

    $find_body = $find_response['body'];
    if (!isset($find_body['status']['errorNo']) || $find_body['status']['errorNo'] != 0) {
        error_log('bd_get_subscription_status_by_email: FindAppuser returned an error for ' . $email);
        return false;
    }

    $customer_no = $find_body['data']['userInfo']['user_id'] ?? '';
    if (empty($customer_no)) {
        error_log('bd_get_subscription_status_by_email: no user_id returned for ' . $email);
        return false;
    }

    if (!defined('MQ_API_GET_CUSTOMER_DETAILS_URL')) {
        define('MQ_API_GET_CUSTOMER_DETAILS_URL', bd_get_env_url('checkout_origin') . '/WebApi/Restapi/GetCustomerDetails');
    }

    $details_response = mq_api_request(MQ_API_GET_CUSTOMER_DETAILS_URL, ['customerNo' => $customer_no], true);

    if (is_wp_error($details_response) || $details_response['status_code'] != 200) {
        error_log('bd_get_subscription_status_by_email: GetCustomerDetails request failed for ' . $email);
        return false;
    }

    $details_body = $details_response['body'];
    $error_no = $details_body['status']['errorNo'] ?? -1;
    if ($error_no !== 0 || empty($details_body['data']['subscriptionInfo'])) {
        error_log('bd_get_subscription_status_by_email: GetCustomerDetails returned no subscriptionInfo for ' . $email);
        return false;
    }

    $sub_status = strtolower($details_body['data']['subscriptionInfo']['subscriptionStatus'] ?? '');
    $is_active = $sub_status === 'active';

    update_user_meta($user->ID, '_bd_email_verified_sub_status', $is_active ? 'active' : 'inactive');
    update_user_meta($user->ID, '_bd_email_verified_last_checked', time());

    return $is_active;
}

/**
 * Handle Group Login AJAX.
 *
 * Group members exist only in WordPress + Leaky Paywall -- Magnaquest never has a record
 * of them. This deliberately does NOT use wp_signon()/wp_authenticate(), because
 * mq_custom_authenticate() above is hooked to the global "authenticate" filter and would
 * force this login through the Magnaquest API too, which is exactly what the Group Login
 * flow must avoid. Instead this authenticates manually (wp_check_password +
 * wp_set_auth_cookie), the same low-level approach handle_mq_login() above uses, just
 * without any Magnaquest call at all.
 */
add_action('wp_ajax_mq_group_login', 'handle_group_login');
add_action('wp_ajax_nopriv_mq_group_login', 'handle_group_login');

function handle_group_login() {
    check_ajax_referer('mq_auth_nonce', 'security');

    $email = sanitize_email($_POST['group_login_username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        wp_send_json_error(['message' => 'Email and password are required.']);
    }

    $user = get_user_by('email', $email);
    if (!$user || !wp_check_password($password, $user->user_pass, $user->ID)) {
        wp_send_json_error(['message' => 'Invalid email or password.']);
    }

    // BUGFIX: this tab is for group members only -- any WP account (including regular
    // Magnaquest subscribers) could previously log in here, which is wrong since Group
    // Login is meant to be a separate, WordPress/Leaky-Paywall-only path. Only accounts
    // flagged by handle_group_signup() below may use it; everyone else is pointed at
    // Member Login instead.
    if (!get_user_meta($user->ID, '_bd_is_group_member', true)) {
        wp_send_json_error(['message' => 'This account is not a group member. Please use Member Login instead.']);
    }

    wp_clear_auth_cookie();
    wp_set_current_user($user->ID);
    wp_set_auth_cookie($user->ID, true);
    update_user_caches($user);

    wp_send_json_success([
        'message'  => 'Login successful',
        'redirect' => home_url('/'),
    ]);
}

/**
 * Handle Group Signup AJAX.
 *
 * Finalizes a group member invite: creates (or authenticates an existing) WordPress
 * user for the invited email, then calls Leaky Paywall's group-member finalize API.
 * Access/paid status is only granted if that finalize call succeeds -- on any failure
 * this logs the error and returns a validation message without creating a session or
 * marking the account as an active group member. No Magnaquest API is called anywhere
 * in this flow (see bd_fetch_group_invite() in functions.php and
 * bd_get_lpw_basic_auth_header() for the shared Leaky Paywall REST plumbing this reuses).
 */
add_action('wp_ajax_handle_group_signup', 'handle_group_signup');
add_action('wp_ajax_nopriv_handle_group_signup', 'handle_group_signup');

function handle_group_signup() {
    check_ajax_referer('mq_auth_nonce', 'security');

    $invite_key = sanitize_text_field($_POST['invite_key'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($invite_key) || empty($password)) {
        wp_send_json_error(['message' => 'Invite key and password are required.']);
    }

    // Re-validate the invite server-side -- never trust client-submitted email/group_id alone.
    $invite = bd_fetch_group_invite($invite_key);
    if (empty($invite['success']) || ($invite['status'] ?? '') !== 'pending') {
        error_log('handle_group_signup: invite lookup failed or not pending for key ' . $invite_key);
        wp_send_json_error(['message' => 'This invite link is invalid or has already been used.']);
    }

    $email = sanitize_email($invite['email']);
    $group_id = $invite['group_id'];
    $first_name = sanitize_text_field($invite['first_name']);
    $last_name = sanitize_text_field($invite['last_name']);

    if (empty($email) || empty($group_id)) {
        error_log('handle_group_signup: invite response missing email/group_id for key ' . $invite_key);
        wp_send_json_error(['message' => 'This invite link is invalid.']);
    }

    $user = get_user_by('email', $email);

    if ($user) {
        // Existing account -- this is the "log in with the fixed email" branch of the flow.
        if (!wp_check_password($password, $user->user_pass, $user->ID)) {
            wp_send_json_error(['message' => 'Incorrect password for this account.']);
        }
    } else {
        // New account -- this is the "create your password" branch of the flow.
        $new_user_id = wp_create_user($email, $password, $email);
        if (is_wp_error($new_user_id)) {
            error_log('handle_group_signup: wp_create_user failed for ' . $email . ': ' . $new_user_id->get_error_message());
            wp_send_json_error(['message' => 'Unable to create your account. Please try again.']);
        }
        $user = get_userdata($new_user_id);
        $user->set_role('subscriber');
        wp_update_user([
            'ID'         => $new_user_id,
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'display_name' => trim($first_name . ' ' . $last_name),
        ]);
    }

    // Finalize: only after this succeeds does the member get paid access.
    $finalize_url = home_url('/wp-json/leaky-paywall/v1/groups/' . rawurlencode($group_id) . '/members');
    $finalize_response = wp_remote_post($finalize_url, [
        'headers' => [
            'Authorization' => bd_get_lpw_basic_auth_header(),
            'Content-Type'  => 'application/json',
        ],
        'body'    => wp_json_encode([
            'email'      => $email,
            'invite_key' => $invite_key,
        ]),
        'timeout' => 20,
    ]);

    if (is_wp_error($finalize_response)) {
        error_log('handle_group_signup: finalize request failed for ' . $email . ': ' . $finalize_response->get_error_message());
        wp_send_json_error(['message' => 'Unable to activate your group membership right now. Please try again shortly.']);
    }

    $finalize_status = wp_remote_retrieve_response_code($finalize_response);
    $finalize_body = json_decode(wp_remote_retrieve_body($finalize_response), true);

    if ($finalize_status < 200 || $finalize_status >= 300) {
        error_log('handle_group_signup: finalize API returned ' . $finalize_status . ' for ' . $email . ': ' . wp_remote_retrieve_body($finalize_response));
        wp_send_json_error(['message' => $finalize_body['message'] ?? 'Unable to activate your group membership. Please contact your group owner.']);
    }

    // Success -- mark as a group member (see header.php / my-account.php / register.php /
    // functions.php's bd_custom_menu_visibility() for where this flag hides My Account/Subscribe),
    // then log them in directly (same manual-cookie approach as handle_group_login() above).
    update_user_meta($user->ID, '_bd_is_group_member', '1');

    wp_clear_auth_cookie();
    wp_set_current_user($user->ID);
    wp_set_auth_cookie($user->ID, true);
    update_user_caches($user);

    wp_send_json_success([
        'message'  => 'Your group membership is now active.',
        'redirect' => home_url('/'),
    ]);
}

