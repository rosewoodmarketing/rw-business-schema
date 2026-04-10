(function ($) {
  'use strict';

  var typeGroups        = (window.rwBsData && window.rwBsData.typeGroups)       ? window.rwBsData.typeGroups       : {};
  var typeDescriptions  = (window.rwBsData && window.rwBsData.typeDescriptions) ? window.rwBsData.typeDescriptions : {};
  var i18n              = (window.rwBsData && window.rwBsData.i18n)             ? window.rwBsData.i18n             : {};

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
