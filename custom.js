jQuery(document).ready(function() {

    if (jQuery('.pagination .active.pagination_book').data('current_page') == 1) {
        jQuery('.pagination .pagination_prev_book').hide();
    }

    jQuery(function() {
        jQuery("#datepicker").datepicker();
    });
});

jQuery(document).on('click change', '.pagination .pagination_book, .tablinks, .pagination_next_book, .pagination_prev_book, .slct_writer, .book_evnt_date, .search_btn', function() {

    var book_evnt = jQuery('.book_evnt_date').val();
    var writer = jQuery('.slct_writer :selected').data('writer_slug');
    var search_book = (jQuery('.search_book').val() != '') ? jQuery('.search_book').val() : '';

    var current_page = '';
    var total_pages = '';
    if (typeof jQuery(this).data('current_page') === 'undefined') {

        current_page = jQuery('.pagination .active.pagination_book').data('current_page');
        total_pages = jQuery('.pagination .active.pagination_book').data('total_pages');

    } else {
        current_page = jQuery(this).data('current_page');
        total_pages = jQuery(this).data('total_pages');
    }

    if (current_page == 'next_book') {
        current_page = jQuery('.pagination .active.pagination_book').data('current_page');
        current_page++;
    }

    if (current_page == 'prev_book') {
        current_page = jQuery('.pagination .active.pagination_book').data('current_page');
        current_page--;
    }
    var cat_slug = '';
    if (typeof jQuery(this).data('slug') === 'undefined') {
        cat_slug = jQuery('.tab .active').data('slug');
    } else {
        jQuery('.tab .tablinks').removeClass('active');
        jQuery(this).addClass('active');
        cat_slug = jQuery(this).data('slug');
    }

    filter_books(current_page, cat_slug, writer, book_evnt, search_book);

})

function filter_books(current_page, cat_slug, writer, book_evnt, search_book) {

    jQuery.ajax({
        url: my_ajax_object.ajax_url,
        method: 'POST',
        data: {
            action: 'get_books',
            current_page: current_page,
            cat_slug: cat_slug,
            writer: writer,
            book_evnt: book_evnt,
            search_book: search_book
        },
        success: function(data) {

            var obj = jQuery.parseJSON(data);

            if (typeof obj.books_html != 'undefined') {
                jQuery('.data').html(obj.books_html);
            } else {
                jQuery('.data').html('Book not found....');
            }

            if (typeof obj.pagination != 'undefined') {
                jQuery('.pagination').html(obj.pagination);
                updateVisiblePages(jQuery('.pagination .active.pagination_book').data('current_page'));
            } else {
                jQuery('.pagination').html('');
            }
        }
    })
}


let maxAround = 1;
let maxRange = 3;
var currentPage = 2;
let $paginator = jQuery('.pagination .pagination_book');

jQuery(document).ready(function() {
    updateVisiblePages(currentPage);
});

function updateVisiblePages(currentPage) {

    // Count amount of pages
    let totalPages = jQuery('.pagination .pagination_book').length;
    let endRange = totalPages - maxRange;
    // Check if we're in the starting section
    let inStartRange = currentPage <= maxRange;
    // Check if we're in the ending section
    let inEndRange = currentPage >= endRange;

    // We need this for the span(s)
    let lastDotIndex = -1;

    // Remove all dots
    jQuery('a.dots').remove();
    // Loop the pages
    jQuery('.pagination .pagination_book').each(function(page, element) {
        // Index starts at 0, pages at 1
        ++page;
        let $element = jQuery(element);
        if (page === 1 || page === totalPages) {
            // Always show first and last
            $element.show();
        } else if (inStartRange && page <= maxRange) {
            // Show element if in start range
            $element.show();
        } else if (inEndRange && page >= endRange) {
            // Show the element if in ending range
            $element.show();
        } else if (page === currentPage - maxAround || page === currentPage || page === currentPage + maxAround) {
            // Show element if in the wrap around
            $element.show();
        } else {
            // Doesn't validate, hide it.
            // Append dot if needed
            $element.hide();

            if (lastDotIndex === -1 || (!inStartRange && !inEndRange && jQuery('a.dots').length < 2 && page > currentPage)) {
                lastDotIndex = page;
                // Insert dots after this page, we only have one or 2, and we can only insert the second one
                // if we're not in the start or end range, and it's past the current page
                $element.after('<a href="javascript:void(0);" class="dots">...</a>');
            }
        }
    });
}

/** 08-02-2023 */

// jQuery(document).ready(function() {
//     jQuery('.acf-checkbox-list input[type="checkbox"]').on('click', function() {
//         if (jQuery(this).is(':checked')) {
//             //jQuery(this).prop('checked',false);
//             // jQuery(this).parents('li').find('.children input[type="checkbox"]').attr('checked', true);
//             jQuery(this).parents('li').children('.children input[type="checkbox"]').attr('checked', true);
//         } else {
//             //jQuery(this).prop('checked',true);
//             // alert("not checked");

//             jQuery(this).parents('li').find('.children input[type="checkbox"]').attr('checked', false);
//         }
//     })
// })