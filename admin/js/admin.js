(function ($) {
  'use strict';

  var typeGroups = (window.rwBsData && window.rwBsData.typeGroups) ? window.rwBsData.typeGroups : {};

  function toggleTypeFields(selectEl) {
    var $select = $(selectEl);
    var type = $select.val();
    var $wrapper = $select.closest('form, #poststuff, .rw-bs-admin-wrap');
    $wrapper.find('.rw-bs-type-group').hide();
    if (typeGroups[type]) {
      $wrapper.find('.rw-bs-type-group[data-group="' + typeGroups[type] + '"]').show();
    }
  }

  $(document).ready(function () {
    // Settings page main selector
    toggleTypeFields('#rw_bs_business_type');
    $('#rw_bs_business_type').on('change', function () {
      toggleTypeFields(this);
    });

    // Location metabox selector (post edit screen)
    $('.rw-bs-type-select').each(function () {
      toggleTypeFields(this);
    });
    $(document).on('change', '.rw-bs-type-select', function () {
      toggleTypeFields(this);
    });
  });
})(jQuery);
