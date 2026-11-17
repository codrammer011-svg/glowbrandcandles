<?php
/**
 * Chairpersons often type the school name into the one street box on the booking form:
 * "Camden County Technical School 6008 Browning Road". ShipStation's address verification
 * rewrites Address Line 1 to the USPS street format and drops the school name. When Line 2
 * is empty, send the name before the house number as Address Line 2 instead, which
 * ShipStation keeps. The order on the site is not changed.
 */

defined('ABSPATH') || exit;

/**
 * Split "Name 123 Street" into array( street, name ), or return null when the line
 * does not look like a name in front of a street address.
 */
function glow_split_name_from_street($line) {
    $line = trim(preg_replace('/\s+/', ' ', (string) $line));
    // Name starts with a letter (it may hold numbers, e.g. "Class of 2027"); the street is the
    // first house number that is followed by a word: "6008 Browning Road".
    if (!preg_match('/^(\p{L}.*?)[\s,\-]+(\d+[a-zA-Z]?\s+\p{L}.*)$/u', $line, $m)) {
        return null;
    }
    $name   = trim($m[1], " ,-");
    $street = trim($m[2]);
    $words  = preg_split('/\s+/', $name);
    // Street addresses that start with words: County Road 12, State Route 9, Highway 50, PO Box 7.
    $last = strtolower(rtrim(end($words), '.#'));
    $street_words = array('road', 'rd', 'route', 'rte', 'rt', 'highway', 'hwy', 'box', 'suite', 'ste', 'apt', 'apartment', 'unit', 'bldg', 'building', 'floor', 'fl', 'room', 'rm', 'lot', 'space', 'no', 'number', 'pier', 'dock', 'gate', 'door', 'hc', 'rr', 'interstate', 'i');
    if (count($words) < 2 || in_array($last, $street_words, true) || '' === $last) {
        return null;
    }
    return array($street, $name);
}

add_filter('woocommerce_shipstation_export_address_data', function ($address) {
    if (!is_array($address) || '' !== trim((string) ($address['address2'] ?? ''))) {
        return $address;
    }
    $split = glow_split_name_from_street($address['address1'] ?? '');
    if ($split) {
        $address['address1'] = $split[0];
        $address['address2'] = $split[1];
    }
    return $address;
}, 20);
