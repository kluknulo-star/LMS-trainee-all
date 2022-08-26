<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>Quiz</title>
    <link rel="stylesheet" type="text/css" href="{{ url('css/quizzes/style.css') }}">

</head>
<body>
<div class="container">
    <div id="game" class="justify-center flex-column">
    </div>
</div>
<script type="text/javascript">
    async function start() {
        let response =  await fetch("{{ route('quiz.retrieve', ['id' => $id, 'section_id' => $section_id, 'quiz' => $quiz]) }}", {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'url': "{{ route('quiz.play', ['id' => $id, 'section_id' => $section_id, 'quiz' => $quiz]) }}",
                "X-CSRF-Token": document.head.querySelector("[name=csrf-token]").content
            },
        });

        let questions = await response.json();
        let countQuestions = questions.length;
        let game = document.getElementById("game");
        let answer = '';
        let correctAnswersCount = 0;
        let currentQuestionNumber = 0;

        startQuiz = () => {
            currentQuestionNumber = 0;
            getNewQuestion();
        }

        getNewQuestion = () => {
            if (questions.length === 0) {
                let $json = JSON.stringify({
                    'correctAnswersCount': correctAnswersCount,
                });

                let request = fetch("{{ route('quiz.results.store', ['id' => $id, 'section_id' => $section_id, 'quiz' => $quiz]) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'url': "{{ route('quiz.results.store', ['id' => $id, 'section_id' => $section_id, 'quiz' => $quiz]) }}",
                        "X-CSRF-Token": document.head.querySelector("[name=csrf-token]").content
                    },
                    body: $json,
                }).then(response => {
                    if (response.status === 302) {
                        window.location.replace("{{ route('quiz.results.show', ['id' => $id, 'section_id' => $section_id, 'quiz' => $quiz]) }}");
                    }
                });
            }

            currentQuestionNumber++;
            const questionsIndex = 0
            let currentQuestion = questions[questionsIndex];
            game.innerHTML += `<p class="progress-bar">question ${currentQuestionNumber} of ${countQuestions}</p>
                                <h2 id="question">${currentQuestion.question}</h2>`;

            for (let key in currentQuestion.options) {
                if (currentQuestion.options.hasOwnProperty(key)) {
                    game.innerHTML += `<div class="choice-container">
                <p class="choice-text">${currentQuestion.options[key].optionBody}</p></div>`;
                    if (currentQuestion.options[key].isCorrect === 1) {
                        answer = currentQuestion.options[key].optionBody;
                    }
                }
            }

            const choices = Array.from(document.querySelectorAll('.choice-text'));

            choices.forEach(choice => {
                choice.addEventListener('click', e => {
                    const selectedChoice = e.target;
                    const selectedAnswer = selectedChoice.textContent;
                    let classToApply = 'incorrect';

                    if (selectedAnswer === answer) {
                        correctAnswersCount ++;
                        classToApply = 'correct';
                    }

                    selectedChoice.parentElement.classList.add(classToApply);

                    setTimeout(() => {
                        game.innerHTML = ``;
                        getNewQuestion();
                    }, 1000);
                });
            });


            questions.splice(questionsIndex, 1);
        }

        startQuiz();

    }

    start();

</script>
</body>
</html>

