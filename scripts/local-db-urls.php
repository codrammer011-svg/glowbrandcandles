<?php
// CLI-only helper; never expose this as a web endpoint.
if (PHP_SAPI !== 'cli') { http_response_code(403); exit; }
function glow_local_replace($value, $local, $depth = 0) {
    if ($depth > 30) { throw new RuntimeException('Serialized data exceeds the supported depth'); }
    if (is_array($value)) {
        foreach ($value as $key => $item) { $value[$key] = glow_local_replace($item, $local, $depth + 1); }
        return $value;
    }
    if (!is_string($value)) { return $value; }
    $decoded = @unserialize($value, array('allowed_classes' => false));
    if (is_object($decoded) || (is_array($decoded) && preg_match('/[Rr]:[0-9]+;/', $value))) { return $value; }
    if (is_array($decoded) || is_string($decoded)) {
        return serialize(glow_local_replace($decoded, $local, $depth + 1));
    }
    $value = preg_replace_callback('~https?://(?:www\.)?glow(?:brandscandles|scentedcandles)\.com~i', function() use ($local) { return $local; }, $value);
    return preg_replace_callback('~https?:\\\\/\\\\/(?:www\.)?glow(?:brandscandles|scentedcandles)\.com~i', function() use ($local) { return str_replace('/', '\\/', $local); }, $value);
}
if (($argv[1] ?? '') === '--self-test') {
    $local = 'http://glow.local';
    $plain = 'https://glowbrandscandles.com/profile/1/';
    $nested = serialize(array('url' => $plain, 'json' => json_encode(array('url' => $plain))));
    $result = unserialize(glow_local_replace($nested, $local));
    if ($result['url'] !== $local . '/profile/1/' || json_decode($result['json'], true)['url'] !== $result['url']) { throw new RuntimeException('URL test failed'); }
    if (glow_local_replace('candace@glowscentedcandles.com', $local) !== 'candace@glowscentedcandles.com') { throw new RuntimeException('Email modified'); }
    echo "Serialized, JSON and email-preservation tests passed.\n";
    exit;
}
$config = json_decode(file_get_contents($argv[1] ?? ''), true, 512, JSON_THROW_ON_ERROR);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db = new mysqli($config['host'], $config['user'], $config['password'], $config['database'], $config['port']);
$db->set_charset('utf8mb4');
$prefix = $config['prefix'];
if (!preg_match('/^[A-Za-z0-9_]+$/', $prefix)) { throw new RuntimeException('Invalid prefix'); }
$local = rtrim($config['localUrl'], '/');
$options = $prefix . 'options';
$db->query("DELETE FROM `$options` WHERE LEFT(option_name,11)='_transient_' OR LEFT(option_name,16)='_site_transient_'");
$tables = array('options'=>array('option_id','option_value'), 'posts'=>array('ID','post_content','post_excerpt'), 'postmeta'=>array('meta_id','meta_value'), 'usermeta'=>array('umeta_id','meta_value'), 'users'=>array('ID','user_url'), 'termmeta'=>array('meta_id','meta_value'), 'term_taxonomy'=>array('term_taxonomy_id','description'));
$updated = 0;
foreach ($tables as $suffix => $columns) {
    $table = $prefix . $suffix; $pk = array_shift($columns);
    foreach ($columns as $column) {
        $rows = $db->query("SELECT `$pk`,`$column` FROM `$table` WHERE `$column` LIKE '%glowbrandscandles.com%' OR `$column` LIKE '%glowscentedcandles.com%'");
        $db->begin_transaction();
        try {
            $statement = $db->prepare("UPDATE `$table` SET `$column`=? WHERE `$pk`=?");
            while ($row = $rows->fetch_assoc()) {
                $new = glow_local_replace($row[$column], $local);
                if ($new === $row[$column]) { continue; }
                $id = $row[$pk]; $statement->bind_param('ss', $new, $id); $statement->execute(); $updated++;
            }
            $statement->close(); $db->commit();
        } catch (Throwable $error) { $db->rollback(); throw $error; }
    }
}
$statement = $db->prepare("UPDATE `$options` SET option_value=? WHERE option_name IN ('home','siteurl')");
$statement->bind_param('s', $local); $statement->execute();
echo "Updated URL fields: $updated\n";
foreach ($db->query("SELECT option_name,option_value FROM `$options` WHERE option_name IN ('home','siteurl')") as $row) { echo implode(': ', $row) . "\n"; }
