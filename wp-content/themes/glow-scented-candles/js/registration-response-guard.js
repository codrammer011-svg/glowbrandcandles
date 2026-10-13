(function ($) {
    'use strict';

    var schema = window.glowFundraiserFields;

    function blank(value) {
        return value === null || value === undefined || value === false
            || (typeof value === 'string' && value.trim() === '')
            || (Array.isArray(value) && value.every(blank));
    }

    function applyOptionalRules() {
        if (!schema) { return; }
        $('#user-registration-form-' + schema.formId).find('input, select, textarea').each(function () {
            var name = (this.name || '').replace(/\[\]$/, '');
            var definition = schema.fields[name];
            if (!definition || definition.required) { return; }
            var field = $(this);
            field.removeAttr('required').attr('aria-required', 'false').data('required', false);
            var validator = field.closest('form').data('validator');
            if (validator && typeof field.rules === 'function') {
                field.rules('remove', 'required');
            }
        });
    }

    $(applyOptionalRules);
    // Apply before delegated submit-button validation, including forms initialized later.
    document.addEventListener('click', function (event) {
        if (event.target.closest && event.target.closest('#user-registration-form-62316 .ur-submit-button')) {
            applyOptionalRules();
        }
    }, true);

    $.ajaxPrefilter(function (options) {
        var data = options.data;
        var action = typeof data === 'string'
            ? new URLSearchParams(data).get('action')
            : data && data.action;
        if (action !== 'user_registration_user_form_submit') {
            return;
        }

        // Send explicit nulls for optional fields rather than depending on which
        // empty controls UR's browser serializer happens to include.
        if (schema) {
            var params = typeof data === 'string' ? new URLSearchParams(data) : null;
            var formId = params ? params.get('form_id') : data.form_id;
            var raw = params ? params.get('form_data') : data.form_data;
            if (String(formId) === String(schema.formId)) {
                try {
                    var fields = JSON.parse(raw);
                    if (Array.isArray(fields)) {
                        var present = {};
                        fields.forEach(function (field) {
                            present[field.field_name] = true;
                            var definition = schema.fields[field.field_name];
                            if (definition && !definition.required && blank(field.value)) {
                                field.value = null;
                            }
                        });
                        Object.keys(schema.fields).forEach(function (name) {
                            var definition = schema.fields[name];
                            if (!definition.required && !present[name]) {
                                fields.push({field_name: name, label: definition.label, field_type: definition.type, value: null});
                            }
                        });
                        if (params) {
                            params.set('form_data', JSON.stringify(fields));
                            options.data = params.toString();
                        } else {
                            data.form_data = JSON.stringify(fields);
                        }
                    }
                } catch (e) {
                    // Malformed payloads still go through normal server validation.
                }
            }
        }

        var complete = options.complete;
        var error = options.error;
        var failureReported = false;

        function validResponse(xhr) {
            try {
                var response = JSON.parse(xhr.responseText);
                return response && typeof response === 'object' && typeof response.success === 'boolean';
            } catch (e) {
                return false;
            }
        }

        function showFailure(xhr) {
            if (!failureReported && window.console) {
                console.warn('Glow registration request did not return a valid JSON response.', {
                    url: options.url,
                    status: xhr.status,
                    responseLength: (xhr.responseText || '').length
                });
                failureReported = true;
            }
            // Only touch forms currently submitting, including any failed transport.
            $('.ur-frontend-form, .user-registration-form').each(function () {
                var form = $(this);
                var button = form.find('.ur-submit-button');
                if (!button.prop('disabled') && !form.find('.ur-front-spinner').length) {
                    return;
                }
                button.prop('disabled', false).find('span').removeClass('ur-front-spinner');
                form.find('.glow-registration-response-error').remove();
                $('<div>', {
                    'class': 'user-registration-error glow-registration-response-error',
                    'role': 'alert'
                }).text('We could not confirm your registration. Check your email or try signing in before submitting again.').appendTo(form);
            });
        }

        options.error = function (xhr) {
            if (!validResponse(xhr)) {
                // Avoid the plugin's automatic second POST after an uncertain result.
                showFailure(xhr);
                return;
            }
            if (typeof error === 'function') {
                return error.apply(this, arguments);
            }
        };

        options.complete = function (xhr) {
            if (!validResponse(xhr)) {
                showFailure(xhr);
                return;
            }
            $('.glow-registration-response-error').remove();
            if (typeof complete === 'function') {
                return complete.apply(this, arguments);
            }
        };
    });
})(jQuery);
