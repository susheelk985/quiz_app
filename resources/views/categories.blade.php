@extends('layouts.app')

@section('content')
    <h1 class="text-center text-2xl font-bold mb-4">Quiz Categories</h1>

    <!-- Categories Grid -->
    <div id="categories-container" class="grid grid-cols-2 gap-4 mx-auto max-w-lg">
        <!-- Categories will be dynamically loaded here -->
    </div>

    <!-- Pagination Dots -->
    <div class="flex justify-center mt-4">
        <div id="pagination-dots" class="flex space-x-2">
            <!-- Dots for pagination -->
        </div>
    </div>

    <script>
        //    $(document).ready(function() {
        let currentPage = 1;

        // Setup CSRF Token in header (for POST requests; not strictly needed for GET)
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Load categories via AJAX
        const loadCategories = (page = 1) => {
            $.ajax({
                url: '/categories-data?page=' + page, // Updated URL to the new data-fetching route
                type: 'GET',
                dataType: 'JSON',
                success: function(data) {
                    currentPage = data.currentPage;
                    renderCategories(data.categories);
                    renderPagination(data.totalPages, currentPage); // Pass currentPage for pagination
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", error);
                    alert('Failed to load categories. Please try again later.');
                }
            });
        };

        // Render categories in a grid layout
        const renderCategories = (categories) => {
            const container = $('#categories-container');
            container.empty(); // Clear previous categories
            categories.forEach(category => {
                const categoryItem = `
                <div class="category-item p-4 bg-blue-200 rounded text-center hover:bg-blue-300 cursor-pointer"
                    data-category-id="${category.id}">
                    <a href="/quiz/${category.id}" class="text-lg font-medium">${category.name}</a>
                </div>
            `;
                container.append(categoryItem);
            });
        };

        // Render pagination dots
        const renderPagination = (totalPages, currentPage) => {
            const pagination = $('#pagination-dots');
            pagination.empty(); // Clear existing pagination dots
            for (let i = 1; i <= totalPages; i++) {
                const dotClass = i === currentPage ? 'pagination-dot selected' : 'pagination-dot';
                pagination.append(`
                <span class="${dotClass}" onclick="loadCategories(${i})"></span>
            `);
            }
            // Highlight the selected dot
            updateSelectedDot(currentPage);
        };

        // Update the selected dot by removing the 'selected' class from all and adding it to the current page
        const updateSelectedDot = (currentPage) => {
            $('.pagination-dot').removeClass('selected'); // Remove 'selected' class from all dots
            $(`.pagination-dot:nth-child(${currentPage})`).addClass(
            'selected'); // Add 'selected' class to the correct dot
        };

        // Load the first page initially
        loadCategories();
        // });
    </script>
@endsection
