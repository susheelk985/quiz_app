@extends('layouts.app')

@section('content')
    <div class="text-center">
        <h1 class="text-2xl font-bold mb-6">Quiz Results</h1>

        <!-- Questions and Answers -->
        <div class="max-w-4xl mx-auto">
            @if (is_array($questions) && is_array($userAnswers))
                @foreach ($questions as $index => $question)
                    <div class="grid grid-cols-2 gap-4 p-4 mb-4 border-b rounded-lg bg-gray-50 shadow-md">
                        <!-- Question -->
                        <div class="text-left">
                            <p class="font-bold text-lg">Q{{ $index + 1 }}:</p>
                            <p class="text-gray-700">{{ $question['question'] }}</p>
                        </div>
                        <!-- Answer -->
                        <div class="text-left">
                            <p>
                                <span class="font-bold">Your Answer:</span>
                                <span
                                    class="{{ $userAnswers[$index]['selected'] === $question['correct_answer'] ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $userAnswers[$index]['selected'] ?? 'No Answer' }}
                                </span>
                            </p>
                            <p>
                                <span class="font-bold">Correct Answer:</span>
                                <span class="text-green-600">{{ $question['correct_answer'] }}</span>
                            </p>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-red-600">Error: Invalid data provided for the results.</p>
            @endif
        </div>

        <!-- Result Status -->
        <div class="mt-6 max-w-md mx-auto p-6 bg-gray-100 border rounded-lg shadow-md">
            <div
                class="text-xl font-bold {{ $status === 'Winner' ? 'text-green-700' : ($status === 'Better' ? 'text-orange-700' : 'text-red-700') }}">
                Result Status: {{ $status }}
            </div>
            <p class="mt-2">You scored {{ $score }} out of {{ count($questions) }}
                ({{ number_format($percentage, 2) }}%)</p>
        </div>

        <!-- Reset Button -->
        <div class="mt-6">
            <a href="{{ route('categories') }}"
                class="px-6 py-2 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600">
                Reset
            </a>
        </div>
    </div>
@endsection
