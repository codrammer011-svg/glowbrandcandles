jQuery(document).ready(function ($) {

    /* switch product_id with variation_id when select changes */
    $(document).on('change', '.product-variations-dropdown select', function () {
        let variation_id = $(this).find(':selected').val();
        let variation_price = $(this).find(':selected').data('price');
        let variation_stock = $(this).find(':selected').data('stock');
        let variation_data = $(this).find(':selected').data('variation');
        let attributes = [];

        if (variation_data) {
            let data = variation_data.toString().split('|');
            $(data).each(function () {
                let split = this.toString().split(':');
                attributes.push(split);
            });
        }

        let product_id = $(this).next(".variation-add").data('product') || $(this).data('product');
        let home_url = (typeof quickster !== 'undefined' && quickster.home_url) ? quickster.home_url : '';
        let query = home_url + '/?add-to-cart=' + product_id + '&variation_id=' + variation_id;

        $(attributes).each(function () {
            let data = this;
            let attribute = data[0];
            let value = data[1];
            query += '&' + attribute + '=' + value;
        });

        $(this).next(".variation-add").prop("href", query);

        /* modify price for variation */
        let select_product_id = $(this).data('product');
        let current_price = $('span.price[data-product="' + select_product_id + '"]');
        let current_stock = $('span.stock[data-product="' + select_product_id + '"]');

        if (typeof quickster !== 'undefined' && select_product_id === current_price.data('product')) {
            let current_price_markup = $('span.price[data-product="' + select_product_id + '"] .woocommerce-Price-amount.amount');
            let result_price = '';
            let formatted_price = ($.isFunction($.number)) ? $.number(variation_price, quickster['number_decimals'], quickster['decimal_separator'], quickster['thousand_separator']).toString() : variation_price;

            switch (quickster['currency_position']) {
                case 'left':
                    result_price = '<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">' + quickster['currency'] + '</span>' + formatted_price + '</span>';
                    break;
                case 'right':
                    result_price = '<span class="woocommerce-Price-amount amount">' + formatted_price + '<span class="woocommerce-Price-currencySymbol">' + quickster['currency'] + '</span></span>';
                    break;
                case 'left_space':
                    result_price = '<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">' + quickster['currency'] + '</span> ' + formatted_price + '</span>';
                    break;
                case 'right_space':
                    result_price = '<span class="woocommerce-Price-amount amount">' + formatted_price + ' <span class="woocommerce-Price-currencySymbol">' + quickster['currency'] + '</span></span>';
                    break;
                default:
                    result_price = '<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">' + (quickster['currency'] || '$') + '</span>' + formatted_price + '</span>';
                    break;
            }

            $(current_price_markup).html(result_price);
        }

        if (select_product_id === current_stock.data('product')) {
            $(current_stock).html('<span class="stock" data-product="' + select_product_id + '">' + variation_stock + '</span>');
        }

        /* modify add input field */
        let add = $('input.add[data-product="' + product_id + '"]');
        let qty = $('input.qty[data-product="' + product_id + '"]');

        $(add).attr('data-product', variation_id);
        $(qty).attr('data-product', variation_id);
        $(add).attr('data-attributes', JSON.stringify(attributes));
    });

    /* refresh WooCommerce cart fragments */
    function refresh_fragments() {
        $(document.body).trigger('wc_fragment_refresh');
    }

    /* add quantity to 1 if checked and quantity input value is empty */
    $(document).on('change', '.quickster-table input.add', function () {
        let select_product_id = $(this).data('product');
        let qty = $('.quickster-table .qty[data-product="' + select_product_id + '"]');

        if ($(this).is(':checked')) {
            if (qty.val() === '' || parseInt(qty.val(), 10) <= 0) {
                qty.val(1);
            }
        } else {
            qty.val('');
        }
        update_cart_total();
    });

    $(document).on('input change', '.quickster-table input.qty', function () {
        let select_product_id = $(this).data('product');
        let add = $('.quickster-table input.add[data-product="' + select_product_id + '"]');
        let val = parseInt($(this).val(), 10);

        if (val > 0) {
            add.prop('checked', true);
        } else if (val === 0 || $(this).val() === '') {
            add.prop('checked', false);
        }
        update_cart_total();
    });

    /* dynamic calculate total for action bar */
    function update_cart_total() {
        if (typeof quickster === 'undefined') return;

        let cart_total = 0;
        let $table = $('.quickster-table table');
        let dt = ($.fn.DataTable && $.fn.DataTable.isDataTable($table)) ? $table.DataTable() : null;
        let $checkedBoxes = dt ? dt.$('input[name=add]:checked') : $('.quickster-table input[name=add]:checked');

        $checkedBoxes.each(function () {
            let product_id = $(this).data('product');
            let $qty = dt ? dt.$('.qty[data-product="' + product_id + '"]') : $('.quickster-table .qty[data-product="' + product_id + '"]');
            let qty = parseInt($qty.val(), 10) || 1;
            let current_price = parseFloat($(this).data('price')) || 0;
            cart_total += (qty * current_price);
        });

        if (cart_total > 0) {
            let formatted_total = ($.isFunction($.number)) ? $.number(cart_total, quickster['number_decimals'], quickster['decimal_separator'], quickster['thousand_separator']).toString() : cart_total.toFixed(2);
            let result_price = (quickster['currency'] || '$') + formatted_total;

            $('.current-cart-total').html(result_price);
            if ($('.footer-action-bar').length && $('.footer-action-bar').css('display') !== 'block') {
                $('.footer-action-bar').slideDown('fast');
            }
        } else {
            $('.current-cart-total').html('');
        }
    }

    /* ajax request for add to cart */
    $(document).on('click', '.add_selection', function (e) {
        e.preventDefault();

        if (typeof quickster === 'undefined') {
            alert('Quickster configuration is missing. Please refresh the page.');
            return;
        }

        let products = [];
        let quantities = [];
        let attributes = {};

        let $table = $('.quickster-table table');
        let dt = ($.fn.DataTable && $.fn.DataTable.isDataTable($table)) ? $table.DataTable() : null;
        let $checkedBoxes = dt ? dt.$('input[name=add]:checked') : $('.quickster-table input[name=add]:checked');

        $checkedBoxes.each(function () {
            let pid = $(this).data('product');
            if (pid) {
                products.push(pid);
                if ($(this).data('attributes')) {
                    attributes[pid] = $(this).data('attributes');
                }
            }
        });

        if (products.length === 0) {
            alert('Please select at least one product by checking the checkbox.');
            return;
        }

        // Collect quantities for checked products
        for (let i = 0; i < products.length; i++) {
            let pid = products[i];
            let $qty = dt ? dt.$('.qty[data-product="' + pid + '"]') : $('.quickster-table .qty[data-product="' + pid + '"]');
            let qval = parseInt($qty.val(), 10);
            if (!qval || qval <= 0) {
                qval = 1;
            }
            quantities.push({
                id: pid,
                quantity: qval
            });
        }

        let $btn = $(this);
        let origText = $btn.text();
        $btn.text('Adding to cart...').css('opacity', '0.7');

        let data = {
            action: 'quickster_add_to_cart',
            products: products,
            quantities: quantities,
            attributes: attributes,
            security: quickster.ajax_nonce
        };

        $.ajax({
            url: quickster.ajax_url,
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (res) {
                $btn.text('Added to cart ✓').css('opacity', '1');
                setTimeout(function () {
                    $btn.text(origText);
                }, 3500);

                refresh_fragments();

                let cart_url = (res && res.data && res.data.cart_url) ? res.data.cart_url : quickster.cart_url;
                let message_text = (res && res.data && res.data.message) ? res.data.message : 'Products added to cart!';
                
                let message = '<div class="quickster-cart-message" style="background-color:#4CAF50;color:#fff;padding:12px 18px;margin-bottom:15px;border-radius:4px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 2px 5px rgba(0,0,0,0.1);"><div class="left"><p style="margin:0;color:#fff;font-weight:600;">' + message_text + '</p></div><div class="right"><a class="button" style="color:#333 !important;background:#fff !important;margin:0;padding:8px 16px;border-radius:4px;font-weight:bold;text-decoration:none;" href="' + cart_url + '">View Cart</a></div></div>';

                $('.quickster-cart-message').remove();
                $(".quickster-table").prepend(message);

                // Scroll to message if needed
                $('html, body').animate({
                    scrollTop: $(".quickster-table").offset().top - 80
                }, 300);
            },
            error: function (xhr, status, error) {
                $btn.text(origText).css('opacity', '1');
                alert('Could not add to cart. Please try again.');
            }
        });
    });

    /* initialise datatable and set options */
    let quickster_table = $('.quickster-table table');

    if (quickster_table.length && $.fn.DataTable) {
        let infinity_active = (typeof quickster !== 'undefined' && quickster['activate_infinity'] === 'on');
        let dt_options = {
            language: {
                search: "",
                sLengthMenu: "_MENU_"
            },
            responsive: true,
            pageLength: 25
        };

        if (infinity_active) {
            dt_options.scrollY = (typeof quickster !== 'undefined' && quickster['infinity_scroll_container_height']) ? quickster['infinity_scroll_container_height'] : 400;
            dt_options.scroller = true;
        }

        if (!$.fn.DataTable.isDataTable(quickster_table)) {
            $(quickster_table).DataTable(dt_options);
        }

        $(quickster_table).addClass('hover stripe');
        $('.dataTables_filter label').addClass('quickster-search');
        if (typeof quickster !== 'undefined' && quickster.search_placeholder) {
            $(".quickster-search input").attr("placeholder", quickster.search_placeholder);
        }
    }

});