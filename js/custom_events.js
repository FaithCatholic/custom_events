(function ($) {
  /**
   * Set active class on views AJAX filter on selected item.
   */
  Drupal.behaviors.exposedfilter_buttons = {
    attach: function(context, settings) {
      // $('a.filter-item').on('click', function(e) {
      $('.calendar-calendar .month-view td a[rel]').on('click', function(e) {
        e.preventDefault();
        // Get the ID of clicked element.
        var id = $(e.target).attr('rel');
        id_min = id + ' 00:00:00';
        id_max = id + ' 23:59:59';
        // Set the value in the select element.
        var filter_min = $('#views-exposed-form-events-page-1 input[name="date[min]"]');
        filter_min.val(id_min);
        var filter_max = $('#views-exposed-form-events-page-1 input[name="date[max]"]');
        filter_max.val(id_max);
        // Unset and re-set the active class.
        // $('.filter-item a').removeClass('active');
        // $(e.target).addClass('active');
        // Trigger the select.
        $('#views-exposed-form-events-page-1 input[name="date[min]"]').trigger('change');
        $('#views-exposed-form-events-page-1 input.form-submit').trigger('click');
      });
    }
  };

  // jQuery(document).ajaxComplete(function(event, xhr, settings) {
  //   switch(settings.extraData.view_name){
  //     case "events":
  //       var filter_id = $('#views-exposed-form-events-page-1 input[name="date[min]"]').val();
  //       $('.filter-item a').removeClass('active');
  //       $('.filter-item').find('#' + filter_id).addClass('active');
  //       break;
  //     default:
  //       break;
  //   };
  // });

})(jQuery);
