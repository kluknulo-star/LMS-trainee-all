<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('css/quizzes/style.css') }}">
    <title>Quiz results</title>
</head>
<body>
    <div class="container">
        <div id="result-bar" class="justify-center flex-column">
            <div class="result-container">
            </div>
        </div>
    </div>
<script>
    async function start() {
        let response = await fetch("{{ route('quiz.results.retrieve', ['id' => $id, 'section_id' => $section_id, 'quiz' => $quiz]) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'url': "{{ route('quiz.results.retrieve', ['id' => $id, 'section_id' => $section_id, 'quiz' => $quiz]) }}",
                "X-CSRF-Token": document.head.querySelector("[name=csrf-token]").content
            },
        });

        let result = await response.json();
        let resultContainer = document.querySelector('.result-container');
        resultContainer.innerHTML += `<p class="result-text">
                        You answered ${result.count_correct_questions} out of ${result.count_questions} questions correctly
                    </p>
                    <p class="result-text">
                        To successfully pass the quiz, you need to correctly answer ${result.count_questions_to_pass} questions
                    </p>`;

        if (result.count_correct_questions < result.count_questions_to_pass) {
            resultContainer.innerHTML += `<p class="result-text incorrect-text">
                        Failed
                    </p> <p style="text-align:center; "> <a href="{{ route('courses.play', ['id' => $id]) }}">Go to course</a> <p>`;
        } else {
            resultContainer.innerHTML += `<p class="result-text correct-text">
                        Passed
                    </p> <p style="text-align:center; "> <a href="{{ route('courses.play', ['id' => $id]) }}">Go to course</a> <p>`;
        }
    }

    start();
</script>
</body>
</html>
