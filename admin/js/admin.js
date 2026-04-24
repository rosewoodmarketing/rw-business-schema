(function ($) {
  'use strict';

  var typeGroups        = (window.rwBsData && window.rwBsData.typeGroups)       ? window.rwBsData.typeGroups       : {};
  var typeDescriptions  = (window.rwBsData && window.rwBsData.typeDescriptions) ? window.rwBsData.typeDescriptions : {};
  var dialingCodes      = (window.rwBsData && window.rwBsData.dialingCodes)     ? window.rwBsData.dialingCodes     : {};
  var i18n              = (window.rwBsData && window.rwBsData.i18n)             ? window.rwBsData.i18n             : {};

  /* ---- phone tel-link auto-generation ---- */
  function computeTelLink(phone, country) {
    phone = String(phone || '').trim();
    if (!phone) { return ''; }
    // Already internationalized — strip formatting only.
    if (phone.charAt(0) === '+') {
      return 'tel:' + phone.replace(/[^\d+]/g, '');
    }
    var code   = dialingCodes[country] || '';
    var digits = phone.replace(/\D/g, '');
    if (!code) { return 'tel:' + digits; }
    // US/Canada: strip leading 1 from a full 11-digit NANP number to avoid +114155551234.
    if (code === '+1' && digits.length === 11 && digits.charAt(0) === '1') {
      digits = digits.substring(1);
    }
    return 'tel:' + code + digits;
  }

  function updatePhoneLinks($wrapper) {
    var country = $wrapper.find('.rw-bs-country-select').val() || '';
    $wrapper.find('.rw-bs-phone-primary-link').val(
      computeTelLink($wrapper.find('.rw-bs-phone-primary').val(), country)
    );
    $wrapper.find('.rw-bs-phone-secondary-link').val(
      computeTelLink($wrapper.find('.rw-bs-phone-secondary').val(), country)
    );
  }

  /* ---- business type field toggling ---- */
  function toggleTypeFields(selectEl) {
    var $select  = $(selectEl);
    var type     = $select.val();
    var $wrapper = $select.closest('form, #poststuff, .rw-bs-admin-wrap');
    $wrapper.find('.rw-bs-type-group').hide();
    if (typeGroups[type]) {
      $wrapper.find('.rw-bs-type-group[data-group="' + typeGroups[type] + '"]').show();
    }
  }

  /* ---- business type description display ---- */
  function updateTypeDescription(selectEl) {
    var $select = $(selectEl);
    var type    = $select.val();
    var desc    = typeDescriptions[type] || '';
    $select.closest('td, th').find('.rw-bs-type-description').text(desc);
  }

  /* ---- target validation helper ---- */
  function targetIsSet($context) {
    var pageId = $context.find('.rw-bs-target-page').val();
    var path   = $context.find('.rw-bs-target-path').val();
    return (pageId && pageId !== '0') || (path && path.trim() !== '');
  }

  function showTargetError($context) {
    $context.find('.rw-bs-target-error').remove();
    var msg = i18n.targetRequired || 'Please select a Target Page or enter a path before saving.';
    $context.find('.rw-bs-target-wrap').append('<p class="rw-bs-target-error" style="color:#d63638;margin-top:4px;">' + msg + '</p>');
  }

  $(document).ready(function () {

    /* phone links — initialize readonly link fields on page load */
    $('form[action="options.php"], #poststuff').each(function () {
      updatePhoneLinks($(this));
    });

    /* phone links — live-update when country or phone changes */
    $(document).on('change input', '.rw-bs-country-select, .rw-bs-phone-primary, .rw-bs-phone-secondary', function () {
      var $wrapper = $(this).closest('form, #poststuff, .rw-bs-admin-wrap');
      updatePhoneLinks($wrapper);
    });

    /* settings page — business type */
    toggleTypeFields('#rw_bs_business_type');
    updateTypeDescription('#rw_bs_business_type');
    $('#rw_bs_business_type').on('change', function () {
      toggleTypeFields(this);
      updateTypeDescription(this);
    });

    /* location metabox — business type */
    $('.rw-bs-type-select').each(function () {
      toggleTypeFields(this);
      updateTypeDescription(this);
    });
    $(document).on('change', '.rw-bs-type-select', function () {
      toggleTypeFields(this);
      updateTypeDescription(this);
    });

    /* settings page — require target before save */
    $('form[action="options.php"]').on('submit', function (e) {
      var $form      = $(this);
      var $targetRow = $form.find('.rw-bs-target-wrap');
      if ($targetRow.length && !targetIsSet($form)) {
        e.preventDefault();
        showTargetError($form);
        $form.find('.rw-bs-target-wrap').get(0).scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    });

    /* location metabox — require target before publish/update */
    $('#publish, #save-post').on('click', function (e) {
      var $metabox = $('#rw_bs_location_details');
      if ($metabox.length && !targetIsSet($metabox)) {
        e.preventDefault();
        showTargetError($metabox);
        $metabox.get(0).scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    });

  });
})(jQuery);
