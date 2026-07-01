<?php
define('WP_USE_THEMES', false);
require_once('./wp-load.php');
set_time_limit(0);
ini_set('memory_limit', '1024M');

echo "<h1>Mindzone XML Import v2</h1><pre>\n";
echo "Start: " . date('H:i:s') . "\n\n";

$file = './import-clean.xml';
$reader = new XMLReader();
$reader->open($file);

$stats = array('posts' => 0, 'pages' => 0, 'warnungen' => 0, 'skipped' => 0, 'items' => 0, 'errors' => 0);

function mz_get_or_create_term($name, $slug, $taxonomy) {
    $term = term_exists($slug, $taxonomy);
    if ($term) {
        return is_array($term) ? $term['term_id'] : $term;
    }
    $result = wp_insert_term($name, $taxonomy, array('slug' => $slug));
    if (is_wp_error($result)) {
        return false;
    }
    return $result['term_id'];
}

while ($reader->read()) {
    if ($reader->nodeType == XMLReader::ELEMENT && $reader->name == 'item') {
        $stats['items']++;

        $xml_string = @$reader->readOuterXML();
        $item = @simplexml_load_string($xml_string, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_RECOVER | LIBXML_NOERROR | LIBXML_NOWARNING);

        if ($item === false) {
            $stats['errors']++;
            continue;
        }

        $namespaces = $item->getNameSpaces(true);
        $wp = isset($namespaces['wp']) ? $item->children($namespaces['wp']) : null;
        $content = isset($namespaces['content']) ? $item->children($namespaces['content']) : null;
        $excerpt = isset($namespaces['excerpt']) ? $item->children($namespaces['excerpt']) : null;

        $orig_type = $wp ? (string)$wp->post_type : 'post';

        // Skip attachments and nav menu items
        if ($orig_type === 'attachment' || $orig_type === 'nav_menu_item') {
            $stats['skipped']++;
            if ($stats['items'] % 1000 === 0) {
                echo "Verarbeitet: {$stats['items']} | Posts: {$stats['posts']} | Pages: {$stats['pages']} | Warnungen: {$stats['warnungen']} | Skip: {$stats['skipped']} | Errors: {$stats['errors']}\n";
                flush();
            }
            continue;
        }

        // Default: keep original post_type (post stays post, page stays page)
        $post_type = ($orig_type === 'page') ? 'page' : 'post';

        // Collect categories and tags
        $categories = array();
        $tags = array();

        if (isset($item->category)) {
            foreach ($item->category as $cat) {
                $domain = isset($cat['domain']) ? (string)$cat['domain'] : '';
                $nicename = isset($cat['nicename']) ? (string)$cat['nicename'] : '';
                $name = (string)$cat;

                if ($domain === 'category') {
                    $categories[] = array('name' => $name, 'slug' => $nicename);
                } elseif ($domain === 'post_tag') {
                    $tags[] = array('name' => $name, 'slug' => $nicename);
                }
            }
        }

        $tag_slugs = array_column($tags, 'slug');

        // Override to substanzwarnung CPT based on tags
        if (in_array('aktuelle-substanzwarnung', $tag_slugs) || in_array('aktuelle-pillenwarnung', $tag_slugs)) {
            $post_type = 'substanzwarnung';
        }

        // Determine date - fall back to gmt date if post_date is invalid
        $post_date = $wp ? (string)$wp->post_date : '';
        $post_date_gmt = $wp ? (string)$wp->post_date_gmt : '';
        if (empty($post_date) || substr($post_date, 0, 4) === '0000') {
            $post_date = (!empty($post_date_gmt) && substr($post_date_gmt, 0, 4) !== '0000') ? $post_date_gmt : current_time('mysql');
        }

        $status = $wp ? (string)$wp->status : 'draft';
        if (!in_array($status, array('publish', 'draft', 'pending', 'private', 'future'))) {
            $status = 'draft';
        }

        $post_id = wp_insert_post(array(
            'post_title'   => (string)$item->title,
            'post_name'    => $wp ? (string)$wp->post_name : '',
            'post_content' => $content ? (string)$content->encoded : '',
            'post_excerpt' => $excerpt ? (string)$excerpt->encoded : '',
            'post_type'    => $post_type,
            'post_status'  => $status,
            'post_date'    => $post_date,
        ), true);

        if (is_wp_error($post_id)) {
            $stats['errors']++;
            continue;
        }

        // Assign categories/tags only for post type that supports them
        if ($post_type === 'post' || $post_type === 'substanzwarnung') {
            if (!empty($categories) && taxonomy_exists('category')) {
                $cat_ids = array();
                foreach ($categories as $cat) {
                    $tid = mz_get_or_create_term($cat['name'], $cat['slug'], 'category');
                    if ($tid) $cat_ids[] = $tid;
                }
                if (!empty($cat_ids)) wp_set_post_terms($post_id, $cat_ids, 'category');
            }

            if (!empty($tags) && taxonomy_exists('post_tag')) {
                $tag_ids = array();
                foreach ($tags as $tag) {
                    $tid = mz_get_or_create_term($tag['name'], $tag['slug'], 'post_tag');
                    if ($tid) $tag_ids[] = $tid;
                }
                if (!empty($tag_ids)) wp_set_post_terms($post_id, $tag_ids, 'post_tag');
            }
        }

        if ($post_type === 'page') {
            $stats['pages']++;
        } elseif ($post_type === 'substanzwarnung') {
            $stats['warnungen']++;
        } else {
            $stats['posts']++;
        }

        $total_done = $stats['posts'] + $stats['pages'] + $stats['warnungen'];
        if ($total_done % 100 === 0) {
            echo ".";
            flush();
        }

        if ($stats['items'] % 1000 === 0) {
            echo "\nVerarbeitet: {$stats['items']} | Posts: {$stats['posts']} | Pages: {$stats['pages']} | Warnungen: {$stats['warnungen']} | Skip: {$stats['skipped']} | Errors: {$stats['errors']}\n";
            flush();
        }
    }
}

$reader->close();

echo "\n\n=== FERTIG ===\n";
echo "Items gesamt: {$stats['items']}\n";
echo "Posts: {$stats['posts']}\n";
echo "Pages: {$stats['pages']}\n";
echo "Warnungen: {$stats['warnungen']}\n";
echo "Attachments übersprungen: {$stats['skipped']}\n";
echo "Parse-Fehler: {$stats['errors']}\n";
echo "Ende: " . date('H:i:s') . "\n";
echo "</pre>";
