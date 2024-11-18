<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz App</title>
    <!-- Add your stylesheets here -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- jQuery (needed for AJAX requests) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .hidden {
            display: none;
        }

        .border-b {
            border-bottom: 1px solid #ccc;
        }

        .text-green-600 {
            color: #16a34a;
        }

        .text-red-600 {
            color: #dc2626;
        }

        .text-orange-700 {
            color: #b45309;
        }

        .bg-green-100 {
            background-color: #d1fae5;
        }

        .bg-red-100 {
            background-color: #fee2e2;
        }

        .bg-orange-100 {
            background-color: #ffedd5;
        }

        .p-4 {
            padding: 1rem;
        }

        .rounded {
            border-radius: 0.5rem;
        }

        .pagination-dot.selected {
            background-color: #2196F3;
            /* Blue for selected dot */
        }

        .pagination-dot {
            cursor: pointer;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #B0BEC5;
            /* Default color for unselected dots */
        }

        .pagination-dot:hover {
            background-color: #90A4AE;
            /* Hover color */
        }

        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
        }

        #content {
            flex: 1;
            /* This makes the content take up all available space */
        }

        footer {
            background-color: #f3f4f6;
            /* Light grey background */
            padding: 20px;
            text-align: center;
            width: 100%;
            margin-top: auto;
            /* Ensures the footer stays at the bottom */
        }

        #quiz-container {
        margin: 2rem auto;
    }

    #options-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .option-btn {
        padding: 1rem;
        background-color: #f3f4f6;
        border-radius: 0.5rem;
        text-align: center;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .option-btn:hover {
        background-color: #dbeafe; /* Light blue on hover */
        transform: scale(1.02); /* Slight scaling effect on hover */
    }

    .option-btn.correct {
        background-color: #d1fae5; /* Green for correct answers */
        color: #065f46;
        font-weight: bold;
        box-shadow: 0 2px 8px rgba(0, 128, 0, 0.3);
    }

    .option-btn.incorrect {
        background-color: #fee2e2; /* Red for incorrect answers */
        color: #991b1b;
        font-weight: bold;
        box-shadow: 0 2px 8px rgba(255, 0, 0, 0.3);
    }

    /* Timer Box Styling */
    #timer-box {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #fee2e2;
        color: #991b1b;
        font-size: 1.2rem;
        font-weight: bold;
        width: 80px;
        height: 40px;
        border-radius: 0.5rem;
        border: 2px solid #991b1b;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    #error-message {
    display: none; /* Or hidden by default */
}

    </style>
</head>

<body class="bg-gray-100">

    <!-- Navigation Bar -->
    <nav class="bg-blue-500 p-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center text-white">
            <a href="/" class="text-xl font-semibold">Quiz App</a>
            <div class="flex items-center">
                @if (Auth::check())
                    <!-- Check if the user is logged in -->
                    <!-- Dropdown for User Menu -->
                    <div class="relative">
                        <button class="mr-4 text-lg focus:outline-none">
                            {{ Auth::user()->name }} <!-- Display the user's name -->
                        </button>
                        <div
                            class="absolute right-0 w-48 mt-2 bg-white border rounded-md shadow-lg hidden dropdown-menu">
                            <ul class="py-2">
                                <li><a href="/categories"
                                        class="block px-4 py-2 text-sm text-gray-800 hover:bg-gray-200">Categories</a>
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="block">
                                        @csrf
                                        <button type="submit"
                                            class="block px-4 py-2 text-sm text-gray-800 hover:bg-gray-200 w-full text-left">
                                            Logout
                                        </button>
                                    </form>

                                </li>
                            </ul>
                        </div>
                    </div>
                @else
                    <a href="/login" class="mr-4 text-lg">Login</a>
                    <a href="/categories" class="mr-4 text-lg">Categories</a>
                @endif
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div id="content" class="container mx-auto p-6">
        @yield('content') <!-- Placeholder for child views content -->
    </div>

    <!-- Footer (optional) -->
    <footer>
        &copy; {{ date('Y') }} Quiz App. All rights reserved.
    </footer>

    <script>
        // Dropdown functionality
        const userMenuButton = document.querySelector('button');
        const dropdownMenu = document.querySelector('.dropdown-menu');

        userMenuButton.addEventListener('click', () => {
            dropdownMenu.classList.toggle('hidden');
        });

        // Close dropdown if clicked outside
        window.addEventListener('click', (event) => {
            if (!userMenuButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.add('hidden');
            }
        });
    </script>

</body>

</html>
