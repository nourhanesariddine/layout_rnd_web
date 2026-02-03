<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Search Pagination Configuration
    |--------------------------------------------------------------------------
    |
    | Configure how search results are paginated. You can choose between
    | 'pagination' (traditional page numbers) or 'load_more' (infinite scroll).
    |
    */

    'pagination_type' => env('SEARCH_PAGINATION_TYPE', 'load_more'), // 'load_more' (default, most professional)

    'per_page' => env('SEARCH_PER_PAGE', 5), // Results per page

    'min_results_for_pagination' => env('SEARCH_MIN_RESULTS', 5), // Minimum results to show pagination/load more
];
