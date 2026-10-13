<?php
/**
 * Product Categories – extra image, admin scripts
 * Auto-split from functions.php – edit this file, not the monolith.
 */

/**
 * GLOW Category Extra Image
 * - Adds an "Extra Image" field to WooCommerce product categories (add + edit screens).
 * - Adds an Elementor dynamic tag: GLOW > Category Extra Image.
 * Paste into your child theme's functions.php or a Code Snippets PHP snippet
 * (separate from the filters snippet is fine).
 */
 
if ( ! defined( 'GLOW_CAT_IMAGE_META' ) ) {
    define( 'GLOW_CAT_IMAGE_META', 'glow_extra_image' );
}
 
/* ---------------------------------------------------------------
 * 1. Admin field
 * ------------------------------------------------------------- */
 
if ( ! function_exists( 'glow_cat_image_field_html' ) ) {
    function glow_cat_image_field_html( $image_id = 0 ) {
        $image_id = absint( $image_id );
        $preview  = $image_id ? wp_get_attachment_image( $image_id, 'thumbnail', false, array( 'style' => 'max-width:120px;height:auto;' ) ) : '';
        ?>
        <div class="glow-extra-image-field">
            <?php wp_nonce_field( 'glow_extra_image', 'glow_extra_image_nonce' ); ?>
            <input type="hidden" class="glow-extra-image-id" name="glow_extra_image_id" value="<?php echo esc_attr( $image_id ? $image_id : '' ); ?>">
            <div class="glow-extra-image-preview" style="margin-bottom:8px;"><?php echo $preview; // phpcs:ignore ?></div>
            <button type="button" class="button glow-extra-image-upload">Upload / Select Image</button>
            <button type="button" class="button glow-extra-image-remove" style="<?php echo $image_id ? '' : 'display:none;'; ?>">Remove Image</button>
            <p class="description">Ye image Elementor mein dynamic tag "Category Extra Image" se show hogi.</p>
        </div>
        <?php
    }
}
 
/* Add new category screen */
add_action( 'product_cat_add_form_fields', function () {
    ?>
    <div class="form-field term-glow-extra-image-wrap">
        <label>Extra Image</label>
        <?php glow_cat_image_field_html(); ?>
    </div>
    <?php
}, 20 );
 
/* Edit category screen */
add_action( 'product_cat_edit_form_fields', function ( $term ) {
    ?>
    <tr class="form-field term-glow-extra-image-wrap">
        <th scope="row"><label>Extra Image</label></th>
        <td><?php glow_cat_image_field_html( get_term_meta( $term->term_id, GLOW_CAT_IMAGE_META, true ) ); ?></td>
    </tr>
    <?php
}, 20 );
 
/* Save */
if ( ! function_exists( 'glow_save_cat_extra_image' ) ) {
    function glow_save_cat_extra_image( $term_id ) {
        if ( ! isset( $_POST['glow_extra_image_nonce'] )
            || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['glow_extra_image_nonce'] ) ), 'glow_extra_image' ) ) {
            return;
        }
        if ( ! current_user_can( 'manage_product_terms' ) ) {
            return;
        }
 
        $image_id = isset( $_POST['glow_extra_image_id'] ) ? absint( $_POST['glow_extra_image_id'] ) : 0;
 
        if ( $image_id && wp_attachment_is_image( $image_id ) ) {
            update_term_meta( $term_id, GLOW_CAT_IMAGE_META, $image_id );
        } else {
            delete_term_meta( $term_id, GLOW_CAT_IMAGE_META );
        }
    }
}
add_action( 'created_product_cat', 'glow_save_cat_extra_image' );
add_action( 'edited_product_cat', 'glow_save_cat_extra_image' );
 
/* Media uploader script (only on product category screens) */
add_action( 'admin_enqueue_scripts', function () {
    $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
    if ( ! $screen || 'product_cat' !== $screen->taxonomy ) {
        return;
    }
 
    wp_enqueue_media();
    wp_add_inline_script( 'jquery-core', <<<'JS'
jQuery(function ($) {
    function resetField(wrap) {
        wrap.find('.glow-extra-image-id').val('');
        wrap.find('.glow-extra-image-preview').empty();
        wrap.find('.glow-extra-image-remove').hide();
    }
 
    $(document).on('click', '.glow-extra-image-upload', function (e) {
        e.preventDefault();
        var wrap = $(this).closest('.glow-extra-image-field');
        var frame = wp.media({
            title: 'Select Extra Image',
            button: { text: 'Use this image' },
            library: { type: 'image' },
            multiple: false
        });
        frame.on('select', function () {
            var att = frame.state().get('selection').first().toJSON();
            var url = att.sizes && att.sizes.thumbnail ? att.sizes.thumbnail.url : att.url;
            wrap.find('.glow-extra-image-id').val(att.id);
            wrap.find('.glow-extra-image-preview').empty().append(
                $('<img>', { src: url, alt: '' }).css({ maxWidth: '120px', height: 'auto' })
            );
            wrap.find('.glow-extra-image-remove').show();
        });
        frame.open();
    });
 
    $(document).on('click', '.glow-extra-image-remove', function (e) {
        e.preventDefault();
        resetField($(this).closest('.glow-extra-image-field'));
    });
 
    /* "Add New Category" saves via AJAX: clear the field after a successful add. */
    $(document).ajaxComplete(function (event, xhr, settings) {
        if (typeof settings.data === 'string'
            && settings.data.indexOf('action=add-tag') !== -1
            && xhr.responseText && xhr.responseText.indexOf('wp_error') === -1) {
            resetField($('#addtag .glow-extra-image-field'));
        }
    });
});
JS
    );
} );
 
/* Helper for use in PHP templates: glow_get_category_extra_image_url( $term_id, 'large' ) */
if ( ! function_exists( 'glow_get_category_extra_image_url' ) ) {
    function glow_get_category_extra_image_url( $term_id, $size = 'full' ) {
        $image_id = (int) get_term_meta( $term_id, GLOW_CAT_IMAGE_META, true );
        return $image_id ? wp_get_attachment_image_url( $image_id, $size ) : '';
    }
}
 
/* ---------------------------------------------------------------
 * 2. Elementor dynamic tag: GLOW > Category Extra Image
 * ------------------------------------------------------------- */
 