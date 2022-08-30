<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('css/quizzes/style.css') }}">
    <title>Question options</title>
</head>
<body>
<p style="text-align: center;">
    <a href="{{ route('quiz.questions.show', ['id' => $id, 'section_id' => $section_id, 'quiz' => $quiz]) }}">Back</a>
</p>
    <div class="container">
        <div class="form">
            <select id="options">
                @forelse($options as $option)
                    <option class="quiz-option" {{ $option->is_correct ? 'selected' : '' }} value='{{ $option->option_body }}'>{{ $option->option_body }}</option>
                    @empty
                @endforelse
            </select>
            <input type="text" class="optionInput">
            <button type="button" class="deleteOption" name="deleteOption" style="display:none">delete option</button>
            <button type="button" class="addOption" name="addOption" onclick="addOption();">new option</button>
            <button type="button" class="saveChanges" name="saveChanges" onclick="saveChanges();">save changes</button>
        </div>
    </div>
<script>
    let options = document.getElementById('options');
    let inputField = document.querySelector('.optionInput');
    let addOptionBtn = document.querySelector('.addOption');
    let deleteOptionBtn = document.querySelector('.deleteOption');

    options.addEventListener('change', e => {
        let value = e.target.value;
        inputField.value = value;
        addOptionBtn.style.display = 'none';
        deleteOptionBtn.style.display = 'inline';

        deleteOptionBtn.addEventListener('click', e => {
            inputField.value = '';
            addOptionBtn.style.display = 'inline';
            deleteOptionBtn.style.display = 'none';
            let option = document.querySelector(`option[value="${value}"]`)

            if (option) {
                value = '';
                option.remove();
            }
        });
    });


    function addOption() {
        let options = document.getElementById('options');
        let inputField = document.querySelector('.optionInput');
        let value = inputField.value;

        if (value !== '') {
            opt = document.createElement('option');
            opt.classList.add('quiz-option');
            opt.value = value;
            opt.innerHTML = value;
            options.appendChild(opt);
            inputField.value = '';
        }
    }
    function saveChanges() {
        let options = document.querySelectorAll('.quiz-option');
        let obj = [];
        options.forEach(option => {
            obj.push({
                "optionBody": option.textContent,
                "isCorrect": option.selected,
            });
        });
        fetch("{{ route('quiz.options.store', ['id' => $id, 'section_id' => $section_id, 'quiz' => $quiz, 'question' => $question]) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'url': "{{ route('quiz.options.store', ['id' => $id, 'section_id' => $section_id, 'quiz' => $quiz, 'question' => $question]) }}",
                "X-CSRF-Token": document.head.querySelector("[name=csrf-token]").content
            },
            body: JSON.stringify(obj),
        }).then(response => {
            response.text().then(text => {
                alert(text);
            });
        });
    }
</script>
</body>
</html>
