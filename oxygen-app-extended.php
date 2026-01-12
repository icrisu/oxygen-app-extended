<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/*
Plugin Name: Oxygen Extended - Working and relaxing app
Plugin URI: https://www.sakuraleaf.com
Description: Oxygen - Improve focus and boost productivity
Author: SakuraLeaf
Version: 1.0.0
Author URI: https://sakuraleaf.com/support/
License: NU General Public License v2.0 / MIT License
Text Domain: oxygen-app-extended
*/

define('WP_OXYGEN_APP_EXTENDED_PUBLIC_URL', plugins_url('',  __FILE__ ));

// Handle user registration or re-send activation link
function on_oxygen_action_user_register(array $data) {
    $userEmail = $data['userEmail'] ?? '';
    $activationLink = $data['activationLink'] ?? '';
    // style/brand email content 

    ob_start();
    ?>
    <p>Please verify your email address.</p>
    <br><br>
    <p>Use the following link to confirm your email address:
        <br><a href="<?php echo esc_url($activationLink)?>">Activate</a>
    </p>
    <p>If you did not sign up for OxygenApp, please ignore this email.</p>
    <br><br>
    <p>This is an automated message. Please do NOT reply to this email.</p>
    <br><br>
    <p>Thanks!</p>
    <?php
    $mailBody = ob_get_clean();

    file_put_contents('php://stderr', print_r(['mailBody' => $mailBody], TRUE));
    
    wp_mail($userEmail, 'Oxygen-app - Activate your account', $mailBody);
    // Note!
    // To ensure email deliverability, you might consider to install an SMTP plugin:
    // https://wordpress.org/plugins/wp-smtp/
    // https://wordpress.org/plugins/wp-mail-smtp/
}
add_action('oxygen_action_user_register', 'on_oxygen_action_user_register', 20, 1);
// end Handle user registration


// Handle user reset password 
function on_oxygen_app_action_user_reset_password(array $data) {
    $userEmail = $data['userEmail'] ?? '';
    $resetLink = esc_url($data['resetLink'] ?? '');

    // file_put_contents('php://stderr', print_r(['resetLink' => $resetLink], TRUE));
    
    // style/brand email content 
    wp_mail($userEmail, 'Oxygen-app - Reset your password', "Please reset your password by clicking the link: <a href='$resetLink'>Activate</a>");
    // Note!
    // To ensure email deliverability, you might consider to install an SMTP plugin:
    // https://wordpress.org/plugins/wp-smtp/
    // https://wordpress.org/plugins/wp-mail-smtp/
}
add_action('oxygen_app_action_user_reset_password', 'on_oxygen_app_action_user_reset_password', 20, 1);
// End Handle user reset password 


// Sample to override app logo
function on_oxygen_app_filter_logo_html(string $logoHtml, array $settings) {
    $location = $settings['location'] ?? '';
    // location example: main_page_nav
    ob_start();
    ?>
    <div class="text-xl font-bold">
        <a href="#"><?php echo esc_html('Some other text') ?></a>
    </div>
    <?php
    return ob_get_clean();
}
// uncomment the line below to override the app logo
// add_filter('oxygen_app_filter_logo_html', 'on_oxygen_app_filter_logo_html', 20, 2);
// End Sample to override app logo


// Sample to override app top menu
function on_oxygen_app_filter_top_menu_html(string $topMenuHtml, bool $userIsLoggedIn, array $userData, string $langCode) {
    return $topMenuHtml;
}
// uncomment the line below to override the top menu
// add_filter('oxygen_app_filter_top_menu_html', 'on_oxygen_app_filter_top_menu_html', 20, 4);
// End Sample to override app top menu

// // Override frontend routes sample
function on_oxygen_app_filter_frontend_routes($routes, $currentLangCode = 'en') {
    $newRoutes = [
        'root' => [
            'hash' => '',
            'page_title' => esc_html__('Oxygen - Working and relaxing', 'oxygen-app-extended'),
        ],
        'signup' => [
            'hash' => 'signup',
            'page_title' => esc_html__('Oxygen - signup', 'oxygen-app-extended'),
        ],
        'login' => [
            'hash' => 'login',
            'page_title' => esc_html__('Oxygen - login', 'oxygen-app-extended'),
        ],
        'forgot_password' => [
            'hash' => 'forgot_password',
            'page_title' => esc_html__('Oxygen - forgot password', 'oxygen-app-extended'),
        ],
        'upgrade' => [
            'hash' => 'upgrade',
            'page_title' => esc_html__('Oxygen - upgrade', 'oxygen-app-extended'),
        ],
        'my_account' => [
            'hash' => 'my_account',
            'page_title' => esc_html__('Oxygen - my account', 'oxygen-app-extended'),
        ]
    ];
    return $newRoutes;
}
// uncomment the line below to override the routes
// add_filter('oxygen_app_filter_frontend_routes', 'on_oxygen_app_filter_frontend_routes', 20, 2);
// // End Override frontend routes sample


// sample adding stuff to the HTML header
function on_oxygen_app_page_header(string $existingContent, string $currentLangCode) {
        
    ob_start();
    $assetsUrl = WP_OXYGEN_APP_EXTENDED_PUBLIC_URL . '/assets/img';
    ?>
        <meta name="description" content="Oxygen - Working and relaxing app. Improve focus and boost productivity with ambient sounds and beautiful backgrounds.">

        <meta property="og:title" content="Oxygen - Working and Relaxing App">
        <meta property="og:type" content="website">
        <meta property="og:url" content="<?php echo esc_url(home_url()); ?>">
        <meta property="og:image" content="<?php echo esc_url($assetsUrl . '/1_og_1200x630.jpg'); ?>">
        <meta property="og:image:alt" content="Oxygen App - Productivity and Focus">

        <link rel="icon" href="<?php echo esc_url($assetsUrl . '/favicon.ico'); ?>" sizes="any">
        <link rel="icon" href="<?php echo esc_url($assetsUrl . '/icon.svg'); ?>" type="image/svg+xml">
        <link rel="apple-touch-icon" href="<?php echo esc_url($assetsUrl . '/icon.png'); ?>">
    <?php
    echo ob_get_clean();
}
// uncomment this line to override or insert additional data to the header
// add_filter('oxygen_app_page_header', 'on_oxygen_app_page_header', 20, 2);

// sample to add things before the closing body
function on_oxygen_app_page_page_body_end() {
    ob_start();
    ?>
    <?php
    return ob_get_clean();
}
// add_filter('oxygen_app_page_page_body_end', 'on_oxygen_app_page_page_body_end', 20, 2);

// when a user delets his own account
function on_oxygen_app_user_account_deleted(array $userData) {
    // 'user_id' and 'email' are available within userData
}

add_action('oxygen_app_user_account_deleted', 'on_oxygen_app_user_account_deleted');