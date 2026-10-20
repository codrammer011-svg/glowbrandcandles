<?php
/**
 * Plugin Name: Glow Admin Email Routing
 * Description: Routes temporary review recipients and fundraiser summaries back to Candace.
 */

defined( 'ABSPATH' ) || exit;

// Set this constant to false to disable this recipient correction.
if ( defined( 'GLOW_ADMIN_EMAIL_REDIRECT' ) && ! GLOW_ADMIN_EMAIL_REDIRECT ) {
    return;
}

add_filter( 'wp_mail', 'glow_temporary_admin_email_redirect', PHP_INT_MAX );

function glow_temporary_admin_email_redirect( $mail ) {
    $destination = 'candace@glowscentedcandles.com';
    $subject = (string) ($mail['subject'] ?? '');
    $is_admin_notice = strpos($subject, 'New Fundraiser Registration - ') === 0
        || strpos($subject, 'A Member registration:') === 0;
    $pattern = '/(?<![a-z0-9.!#$%&\x27*+\/=?^_`{|}~@-])(?:samraimi786@gmail\.com|samraaimi786@gmail\.com)(?![a-z0-9.@-])/i';
    $replace = static function ( $recipient ) use ( $pattern, $destination, $is_admin_notice ) {
        return $is_admin_notice ? preg_replace( $pattern, $destination, $recipient ) : $recipient;
    };

    if ( isset( $mail['to'] ) ) {
        $mail['to'] = is_array( $mail['to'] )
            ? array_values( array_unique( array_map( $replace, $mail['to'] ) ) )
            : $replace( $mail['to'] );
    }

    // Send the fundraiser admin summary to Candace instead of its testing recipient.
    if ( strpos( (string) ( $mail['subject'] ?? '' ), 'New Fundraiser Registration - ' ) === 0 ) {
        $mail['to'] = array( $destination );
    }

    // Elementor forms (Wholesale, Contact Us, footer) still notify a developer inbox; send them to Candace.
    if ( isset( $mail['to'] ) ) {
        $developer = '/(?<![a-z0-9.!#$%&\x27*+\/=?^_`{|}~@-])saadtflames@gmail\.com(?![a-z0-9.@-])/i';
        $mail['to'] = is_array( $mail['to'] )
            ? array_values( array_unique( preg_replace( $developer, $destination, $mail['to'] ) ) )
            : preg_replace( $developer, $destination, $mail['to'] );
    }

    // Admin notifications addressed to either Candace inbox go to both.
    $recipients = isset($mail['to']) ? (is_array($mail['to']) ? $mail['to'] : preg_split('/\s*,\s*/', $mail['to'])) : array();
    foreach ($recipients as $recipient) {
        if (preg_match('/(?<![a-z0-9.!#$%&\x27*+\/=?^_`{|}~@-])(?:candace@glowscentedcandles\.com|candacethompson12@gmail\.com)(?![a-z0-9.@-])/i', $recipient)) {
            foreach (array('candace@glowscentedcandles.com', 'candacethompson12@gmail.com') as $address) {
                $present = false;
                foreach ($recipients as $existing) {
                    if (preg_match('/(?<![a-z0-9.@-])' . preg_quote($address, '/') . '(?![a-z0-9.@-])/i', $existing)) { $present = true; break; }
                }
                if (!$present) { $recipients[] = $address; }
            }
            $mail['to'] = $recipients;
            break;
        }
    }

    // Redirect copies as well, while preserving sender and reply-to headers.
    if ( ! empty( $mail['headers'] ) ) {
        $headers_are_array = is_array( $mail['headers'] );
        $headers = $headers_are_array ? $mail['headers'] : preg_split( '/\r\n|\r|\n/', $mail['headers'] );
        foreach ( $headers as &$header ) {
            if ( preg_match( '/^\s*(?:Cc|Bcc)\s*:/i', $header ) ) {
                $header = $replace( $header );
            }
        }
        unset( $header );
        $mail['headers'] = $headers_are_array ? $headers : implode( "\r\n", $headers );
    }

    return $mail;
}
