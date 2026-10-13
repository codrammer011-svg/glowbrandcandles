<?php
/**
 * GLOW — Shared seller order grid
 *
 * Expected variables:
 *   $glow_title    — page heading
 *   $glow_catalog  — 'spring-summer' | 'perfect-scents' | 'fall-winter'
 *   $glow_sections — array of sections
 *
 * Section formats:
 *   array( 'menu'=>'', 'heading'=>'', 'category'=>'slug', 'allow'  =>array('SKU1','SKU2') )
 *   array( 'menu'=>'', 'heading'=>'', 'category'=>'slug', 'exclude'=>array('SKU1') )
 *   array( 'menu'=>'', 'heading'=>'', 'ids'=>array(123,456), 'codes'=>array('H1','H2') )
 */
defined( 'ABSPATH' ) || exit;
get_header();

$sid   = get_current_user_id();
$user  = $sid ? get_userdata( $sid ) : null;
$sname = '';
if ( $user ) {
    $sname = trim( $user->first_name . ' ' . $user->last_name );
    if ( '' === $sname ) $sname = $user->display_name;
}

/* ---------- Har section ke products nikaalo ---------- */
$glow_data = array();

foreach ( $glow_sections as $i => $sec ) {

    $rows = array();

    if ( ! empty( $sec['category'] ) ) {

        $allow   = ! empty( $sec['allow'] )   ? $sec['allow']   : array();
        $exclude = ! empty( $sec['exclude'] ) ? $sec['exclude'] : array();

        $products = wc_get_products( array(
            'status'   => 'publish',
            'limit'    => -1,
            'category' => $sec['category'],
            'orderby'  => 'menu_order',
            'order'    => 'ASC',
        ) );

        if ( $allow ) {

            /* SKU se lookup map — phir allow list ke order me lagao */
            $bySku = array();
            foreach ( $products as $product ) {
                $bySku[ $product->get_sku() ] = $product;
            }
            foreach ( $allow as $sku ) {
                if ( isset( $bySku[ $sku ] ) ) {
                    $rows[] = array( 'p' => $bySku[ $sku ], 'code' => $sku );
                }
            }

        } else {

            foreach ( $products as $product ) {
                $sku = $product->get_sku();
                if ( $exclude && in_array( $sku, $exclude, true ) ) continue;
                $rows[] = array( 'p' => $product, 'code' => $sku );
            }
        }

    } elseif ( ! empty( $sec['ids'] ) ) {

        $n = 0;
        foreach ( $sec['ids'] as $pid ) {
            $product = wc_get_product( $pid );
            $code    = isset( $sec['codes'][ $n ] ) ? $sec['codes'][ $n ] : ( $product ? $product->get_sku() : '' );
            $n++;
            if ( ! $product ) continue;
            $rows[] = array( 'p' => $product, 'code' => $code );
        }
    }

    if ( $rows ) {
        $glow_data[ $i ] = array( 'sec' => $sec, 'rows' => $rows );
    }
}
?>

<div class="glow-bo-wrap glow-locked" id="glow-wrap">

   <div class="glow-bo-head">
      <h1><?php echo esc_html( $glow_title ); ?></h1>
      <p class="glow-bo-sub">Ordering as <b id="glow-shown-name"><?php echo esc_html( $sname ); ?></b></p>
   </div>

   <div class="glow-bo-menu">
      <?php foreach ( $glow_data as $i => $d ) :
         $label = ! empty( $d['sec']['menu'] ) ? $d['sec']['menu'] : $d['sec']['heading']; ?>
         <a href="#glow-sec-<?php echo $i; ?>" class="glow-jump"><?php echo esc_html( $label ); ?></a>
      <?php endforeach; ?>
   </div>

   <?php if ( empty( $glow_data ) ) : ?>
      <div class="glow-empty">No products found.</div>
   <?php endif; ?>

   <?php foreach ( $glow_data as $i => $d ) : ?>

      <div class="glow-sec" id="glow-sec-<?php echo $i; ?>">

         <div class="glow-sec-head">
            <?php echo esc_html( $d['sec']['heading'] ); ?>
         </div>

         <table class="glow-table">
            <thead>
               <tr>
                  <th class="glow-th-img">Image</th>
                  <th class="glow-th-name">Name</th>
                  <th class="glow-th-sku">SKU</th>
                  <th class="glow-th-qty">Qty</th>
                  <th class="glow-th-price">Price</th>
               </tr>
            </thead>
            <tbody>
            <?php foreach ( $d['rows'] as $row ) :
               $product = $row['p'];
               $pid     = $product->get_id();
               $price   = (float) $product->get_regular_price();
               $img     = get_the_post_thumbnail_url( $pid, 'thumbnail' );
            ?>
               <tr>
                  <td class="glow-td-img">
                     <img src="<?php echo esc_url( $img ? $img : 'https://glowscentedcandles.com/wp-content/uploads/2020/09/news-20190130-202923.png' ); ?>" alt="">
                  </td>
                  <td class="glow-td-name"><?php echo esc_html( strip_tags( $product->get_name() ) ); ?></td>
                  <td class="glow-td-sku"><?php echo esc_html( $row['code'] ); ?></td>
                  <td class="glow-td-qty">
                     <input type="number" class="glow-qty-input" min="0" step="1" placeholder="0"
                            data-id="<?php echo esc_attr( $pid ); ?>"
                            data-price="<?php echo esc_attr( $price ); ?>">
                  </td>
                  <td class="glow-td-price">
                     $<span class="glow-line"><?php echo number_format( $price, 2 ); ?></span>
                  </td>
               </tr>
            <?php endforeach; ?>
            </tbody>
         </table>

      </div>

   <?php endforeach; ?>

   <div class="glow-bo-bar">
      <div class="glow-bar-info">
         <span id="glow-count">0</span> items
         <span class="glow-dot">·</span>
         <span id="glow-total">$0.00</span>
      </div>
      <button type="button" id="glow-done" class="glow-done-btn" disabled>
         DONE BROCHURE ORDER
      </button>
   </div>

</div>

<!-- Start popup — page khulte hi -->
<div class="glow-modal glow-start show" id="glow-start">
   <div class="glow-modal-box">
      <h3>Do you want to place brochure order and send to chairperson?</h3>

      <label class="glow-name-label" for="glow-seller-name">Seller Name</label>
      <input type="text" id="glow-seller-name" class="glow-name-input"
             value="<?php echo esc_attr( $sname ); ?>"
             placeholder="Enter your name">

      <p class="glow-name-err"></p>

      <div class="glow-modal-btns">
         <button type="button" id="glow-start-yes" class="glow-done-btn">SUBMIT</button>
         <a href="<?php echo esc_url( home_url( '/seller-dashboard/' ) ); ?>" class="glow-cancel">Cancel</a>
      </div>
   </div>
</div>

<!-- Confirm popup -->
<div class="glow-modal" id="glow-confirm">
   <div class="glow-modal-box">
      <h3>Confirm Your Order</h3>
      <p class="glow-modal-line">
         <b><span id="glow-c-count">0</span></b> items ·
         <b>$<span id="glow-c-total">0.00</span></b>
      </p>
      <p class="glow-modal-sub">
         Do you want to save and send this order to your chairperson?
      </p>
      <div class="glow-modal-btns">
         <button type="button" id="glow-yes" class="glow-done-btn">YES, SEND IT</button>
         <button type="button" class="glow-cancel glow-confirm-cancel">Cancel</button>
      </div>
      <p class="glow-modal-msg"></p>
   </div>
</div>

<script>
var GLOW_AJAX  = "<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>";
var GLOW_NONCE = "<?php echo esc_attr( wp_create_nonce( 'glow_submit_order' ) ); ?>";
var GLOW_CAT   = "<?php echo esc_attr( isset( $glow_catalog ) ? $glow_catalog : '' ); ?>";
</script>

<style>
.page-id-60114 .header-wrapper,.page-id-60115 .header-wrapper, .page-id-60113 .header-wrapper{
   display:none;
}
.page-id-60114 .main-footer, .page-id-60115 .main-footer, .page-id-60113 .main-footer{
   display:none;
}
.page-id-60114 .fixed-header-area, .page-id-60115 .fixed-header-area, .page-id-60113 .fixed-header-area{
   display:none;
}

.glow-bo-wrap{max-width:1220px;margin:0 auto;padding:30px 20px 120px;font-family:Arial,Helvetica,sans-serif}
.glow-bo-wrap.glow-locked{pointer-events:none;opacity:.45}
.glow-bo-head{margin-bottom:18px}
.glow-bo-head h1{margin:0;font-size:28px;color:#4a4a4a;line-height:1.2}
.glow-bo-sub{margin:6px 0 0;font-size:14px;color:#8c8c8c}
.glow-bo-menu{position:sticky;top:0;z-index:30;display:flex;flex-wrap:wrap;gap:6px;padding:12px 0;background:#fff;border-top:1px solid #ececec;border-bottom:1px solid #ececec;margin-bottom:30px}
.glow-bo-menu a{font-size:13px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:#000!important;text-decoration:none!important;padding:8px 15px;border:1px solid #000;border-radius:30px}
.glow-bo-menu a:hover{background:#ddc49e;border-color:#ddc49e;color:#000!important}
.glow-sec{margin-bottom:45px;scroll-margin-top:80px}
.glow-sec-head{text-align:center;font-size:17px;font-weight:700;text-transform:uppercase;color:#4a4a4a;padding:12px;background:#f7f3ec;border-radius:4px 4px 0 0}
.glow-table{width:100%;border-collapse:collapse;margin:0}
.glow-table th{background:#01aa6d;color:#fff;font-size:12px;text-transform:uppercase;letter-spacing:.4px;font-weight:700;padding:9px 8px;text-align:left;border:0}
.glow-table td{border:0;border-bottom:1px solid #eee;padding:9px 8px;vertical-align:middle}
.glow-table tbody tr:nth-child(even){background:#fafafa}
.glow-table tbody tr.has-qty{background:#eefaf3}
.glow-th-img,.glow-td-img{width:74px}
.glow-td-img img{width:56px;height:56px;object-fit:contain;display:block}
.glow-td-name{font-size:14px;color:#4a4a4a;line-height:1.35}
.glow-th-sku,.glow-td-sku{width:110px;font-size:14px;color:#000;font-weight:600;letter-spacing:.3px}
.glow-th-qty,.glow-td-qty{width:90px}
.glow-qty-input{width:70px;height:36px;text-align:center;border:1px solid #ddd;border-radius:4px;font-size:15px;margin:0;padding:0;-moz-appearance:textfield}
.glow-qty-input::-webkit-outer-spin-button,.glow-qty-input::-webkit-inner-spin-button{-webkit-appearance:none;margin:0}
.glow-th-price,.glow-td-price{width:95px;text-align:right;font-size:14px;font-weight:700;color:#4a4a4a}
.glow-empty{padding:40px;text-align:center;color:#999;border:1px solid #ececec;border-radius:6px}
.glow-bo-bar{position:fixed;left:0;right:0;bottom:0;background:#fff;border-top:1px solid #e0e0e0;box-shadow:0 -2px 14px rgba(0,0,0,.08);padding:14px 20px;display:flex;align-items:center;justify-content:space-between;gap:15px;z-index:60}
.glow-bar-info{font-size:16px;font-weight:700;color:#4a4a4a}
.glow-dot{color:#ccc;padding:0 6px}
.glow-done-btn{background:#17c671;color:#fff;border:0;border-radius:30px;padding:12px 32px;font-size:13px;font-weight:700;letter-spacing:.6px;cursor:pointer}
.glow-done-btn:disabled{background:#d5d5d5;cursor:not-allowed}

.glow-modal{display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:200}
.glow-modal.show{display:block}
.glow-modal-box{position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);background:#fff;padding:32px 26px;width:100%;max-width:420px;border-radius:8px;text-align:center}
.glow-modal-box h3{margin:0 0 14px;font-size:20px;color:#4a4a4a}
.glow-modal-line{font-size:22px;color:#17c671;margin:0 0 10px}
.glow-modal-sub{font-size:14px;color:#777;margin:0 0 22px;line-height:1.5}
.glow-modal-btns{display:flex;flex-direction:column;gap:10px;align-items:center}
.glow-cancel{background:none;border:0;color:#999!important;cursor:pointer;font-size:13px;text-decoration:underline!important}
.glow-modal-msg{margin:14px 0 0;font-size:14px;min-height:20px}

.glow-start .glow-modal-box h3{font-size:18px;line-height:1.4;margin-bottom:22px}
.glow-name-label{display:block;text-align:left;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:#8c8c8c;margin-bottom:6px}
.glow-name-input{width:100%;height:44px;border:1px solid #ddd;border-radius:4px;padding:0 12px;font-size:15px;margin:0 0 6px;box-sizing:border-box}
.glow-name-input:focus{border-color:#17c671;outline:0}
.glow-name-err{min-height:18px;margin:0 0 16px;font-size:13px;color:#d33;text-align:left}

@media(max-width:640px){
.glow-bo-wrap{padding:20px 12px 120px}
.glow-bo-head h1{font-size:21px}
.glow-sec-head{font-size:14px}
.glow-table th{font-size:10px;padding:7px 5px}
.glow-table td{padding:7px 5px}
.glow-th-img,.glow-td-img{width:54px}
.glow-td-img img{width:44px;height:44px}
.glow-th-sku,.glow-td-sku{display:none}
.glow-td-name{font-size:12px}
.glow-th-qty,.glow-td-qty{width:64px}
.glow-qty-input{width:54px;height:32px;font-size:14px}
.glow-th-price,.glow-td-price{width:66px;font-size:12px}
.glow-bo-bar{flex-direction:column;align-items:stretch;gap:10px}
.glow-done-btn{width:100%}
.glow-modal-box{max-width:92%;padding:26px 20px}
}
</style>

<script>
jQuery(function ($) {

    var GLOW_NAME = '';

    /* ---------- Start popup ---------- */

    $(document).on('click', '#glow-start-yes', function () {

        var name = $.trim($('#glow-seller-name').val());

        if (name.length < 2) {
            $('.glow-name-err').text('Please enter your name');
            $('#glow-seller-name').focus();
            return;
        }

        GLOW_NAME = name;
        $('#glow-shown-name').text(name);
        $('#glow-start').removeClass('show');
        $('#glow-wrap').removeClass('glow-locked');
    });

    $(document).on('keydown', '#glow-seller-name', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            $('#glow-start-yes').click();
        }
    });

    $(document).on('input', '#glow-seller-name', function () {
        $('.glow-name-err').text('');
    });

    /* ---------- Grid ---------- */

    function recalc() {
        var count = 0, total = 0;

        $('.glow-qty-input').each(function () {
            var $i = $(this);
            var q  = parseInt(this.value, 10) || 0;
            if (q < 0) { q = 0; this.value = ''; }

            var unit = parseFloat($i.data('price')) || 0;
            var line = q > 0 ? q * unit : unit;

            count += q;
            total += q * unit;

            $i.closest('tr').toggleClass('has-qty', q > 0)
              .find('.glow-line').text(line.toFixed(2));
        });

        $('#glow-count').text(count);
        $('#glow-total').text('$' + total.toFixed(2));
        $('#glow-done').prop('disabled', count === 0);
    }

    $(document).on('input change', '.glow-qty-input', recalc);

    /* Enter dabane par agli row */
    $(document).on('keydown', '.glow-qty-input', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            var $all = $('.glow-qty-input');
            var next = $all.index(this) + 1;
            if ($all.eq(next).length) $all.eq(next).focus();
        }
    });

    /* Smooth scroll */
    $(document).on('click', '.glow-jump', function (e) {
        var $t = $($(this).attr('href'));
        if ($t.length) {
            e.preventDefault();
            $('html,body').animate({ scrollTop: $t.offset().top - 80 }, 500);
        }
    });

    /* ---------- Confirm popup ---------- */

    $(document).on('click', '#glow-done', function () {
        $('#glow-c-count').text($('#glow-count').text());
        $('#glow-c-total').text($('#glow-total').text().replace('$', ''));
        $('.glow-modal-msg').text('');
        $('#glow-confirm').addClass('show');
    });

    $(document).on('click', '.glow-confirm-cancel', function () {
        $('#glow-confirm').removeClass('show');
    });

    /* ---------- Submit ---------- */

    $(document).on('click', '#glow-yes', function () {

        var items = {};
        $('.glow-qty-input').each(function () {
            var q = parseInt(this.value, 10) || 0;
            if (q > 0) items[$(this).data('id')] = q;
        });

        var $btn = $(this).prop('disabled', true).text('SENDING...');

        $.post(GLOW_AJAX, {
            action:      'glow_submit_order',
            nonce:       GLOW_NONCE,
            catalog:     GLOW_CAT,
            seller_name: GLOW_NAME,
            items:       items
        }, function (res) {
            if (res.success) {
                $('#glow-confirm .glow-modal-box').html(
                    '<h3>Order Sent</h3>' +
                    '<p class="glow-modal-sub">Your order has been sent to your chairperson.</p>'
                );
                setTimeout(function () { location.reload(); }, 2500);
            } else {
                $btn.prop('disabled', false).text('YES, SEND IT');
                $('.glow-modal-msg').css('color', '#d33')
                    .text(res.data && res.data.msg ? res.data.msg : 'Something went wrong');
            }
        }).fail(function () {
            $btn.prop('disabled', false).text('YES, SEND IT');
            $('.glow-modal-msg').css('color', '#d33').text('Network error, please try again');
        });
    });

    $('#glow-seller-name').focus();
    recalc();
});
</script>
<?php get_footer(); ?>