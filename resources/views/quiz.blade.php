@extends('layouts.app')

@section('content')
    <div id="quiz-container" class="max-w-lg mx-auto">
        @if (!empty($error))
            <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                {{ $error }}
            </div>
        @else
            <!-- Question Number and Timer -->
            <div class="flex justify-between items-center mb-4 p-4 bg-gray-100 rounded shadow-md">
                <!-- Question Number -->
                <div class="flex items-center">
                    <div id="question-number-circle"
                        class="rounded-full bg-blue-500 text-white w-12 h-12 flex items-center justify-center text-lg font-semibold shadow-md border-2 border-blue-700">
                        <span id="question-number">1</span>
                    </div>
                    <span class="ml-3 text-gray-700 font-semibold text-lg" id="total-questions">of 15</span>
                </div>
                <!-- Timer -->
                <div id="timer-box"
                    class="bg-red-100 text-red-600 w-28 h-12 flex items-center justify-center rounded-lg shadow-md border-2 border-red-500">
                    <p id="timer" class="text-lg font-bold">30</p>
                </div>
            </div>

            <!-- Question Section -->
            <div id="question-box" class="text-left">
                <p id="question-text" class="text-xl font-semibold mt-4"></p>
                <div id="options-container" class="grid grid-cols-2 gap-4 mt-4"></div>
            </div>

            <!-- Reset Button -->
            <div class="mt-6 flex justify-center">
                <a href="{{ route('categories') }}" id="reset-btn"
                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                    Reset
                </a>
            </div>

            <!-- Submit Form -->
            <form id="result-form" action="{{ route('quiz.result') }}" method="POST">
                @csrf
                <input type="hidden" name="userAnswers" id="userAnswersInput">
                <input type="hidden" name="questions" value='@json($questions)'>
            </form>
        @endif


    </div>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const questions = @json($questions);
            let currentIndex = 0;
            let userAnswers = [];
            let timerInterval;

            const questionNumber = document.getElementById('question-number');
            const totalQuestions = document.getElementById('total-questions');
            const questionText = document.getElementById('question-text');
            const optionsContainer = document.getElementById('options-container');
            const timerDisplay = document.getElementById('timer');
            const userAnswersInput = document.getElementById('userAnswersInput');
            const resultForm = document.getElementById('result-form');

            // Display a question
            function displayQuestion(index) {
                if (index >= questions.length) {
                    submitResults();
                    return;
                }

                const question = questions[index];
                questionNumber.textContent = index + 1;
                totalQuestions.textContent = `of ${questions.length}`;
                questionText.innerHTML = question.question;
                optionsContainer.innerHTML = ''; // Clear previous options

                const options = [...question.incorrect_answers, question.correct_answer];
                shuffleArray(options).forEach(option => {
                    const optionBtn = document.createElement('button');
                    optionBtn.textContent = option;
                    optionBtn.className = 'option-btn bg-gray-200';
                    optionBtn.onclick = () => selectAnswer(option, question.correct_answer, optionBtn);
                    optionsContainer.appendChild(optionBtn);
                });

                resetTimer();
            }

            // Shuffle options array
            function shuffleArray(array) {
                return array.sort(() => Math.random() - 0.5);
            }

            // Select an answer
            function selectAnswer(selected, correct, button) {
                if (userAnswers[currentIndex] !== undefined) return;

                userAnswers.push({
                    selected,
                    correct
                });

                Array.from(optionsContainer.children).forEach(child => {
                    child.classList.remove('correct', 'incorrect');
                });

                if (selected === correct) {
                    button.classList.add('correct');
                } else {
                    button.classList.add('incorrect');
                }

                setTimeout(() => {
                    currentIndex++;
                    displayQuestion(currentIndex);
                }, 500);
            }

            // Timer countdown
            function resetTimer() {
                clearInterval(timerInterval);
                let timeLeft = 30;
                timerDisplay.textContent = timeLeft;
                timerInterval = setInterval(() => {
                    timeLeft--;
                    timerDisplay.textContent = timeLeft;
                    if (timeLeft <= 0) {
                        clearInterval(timerInterval);
                        if (userAnswers[currentIndex] === undefined) {
                            const correctAnswer = questions[currentIndex].correct_answer;
                            userAnswers.push({
                                selected: "No answer",
                                correct: correctAnswer
                            });
                            setTimeout(() => {
                                currentIndex++;
                                displayQuestion(currentIndex);
                            }, 500);
                        }
                    }
                }, 1000);
            }

            // Submit results
            function submitResults() {
                clearInterval(timerInterval);
                userAnswersInput.value = JSON.stringify(userAnswers);
                resultForm.submit();
            }

            // Initialize the quiz
            displayQuestion(currentIndex);
        });
    </script>
@endsection
