@component('components.header')
@endcomponent

@component('components.aside')
@endcomponent

<div class="container">
    <img src="" alt="" class="courses__img">
    <div class="h1 mb20">{{ $course->title }}
        @if ($course->author->user_id == auth()->id())
            <a class="ml20 h1" href="{{ route('courses.edit', ['id' => $course->course_id]) }}"><i class="fa-solid fa-pen"></i></a>
        @endif
    </div>
    <div class="margin5">
        {{ __('main.author') }}: <a
            href="{{ route('users.show', ['id' => $course->author->user_id]) }}">
            {{ $course->author->email }}
        </a>
    </div>
    <div class="courses__course-description mb30">{{ __('main.description') }}: {{ Str::limit($course->description, 200, '...') }}</div>

    <p style="color: whitesmoke;" allContent="{{ count($course->content) }}"
                passedContent="{{ round(count($myCourseProgress['passed'])) }}"
                class="progress h3">{{ $myCourseProgress['progress'] }}</p>
    <br><br>
    <div class="h2 mb20">{{ __('main.courseContent') }}:</div>
    @foreach($course->content as $element)
        @if ($element->deleted_at === NULL)
        <div class="margin20-0">
            <b class="h3">{{$element->title}}</b><br><br>
            ❮{{$element->type->type}}❯

            <button class="send-stmt-button"
                    id="{{$element->item_id}}launched"
                    verb="launched"
                    sectionId="{{$element->item_id}}"
                    courseId="{{$course->course_id}}"
                    @if(in_array($element->item_id, $myCourseProgress['launched']))
                        disabled
                        style="padding: 5px; background: rgba(0,0,0,0); color: #c4aa33 !important;"
                    @else
                        style="padding: 5px; background: rgba(0,0,0,0); color: #eb4432;"
                    @endif>
                <i class="fas fa-check"></i>
            </button>

            <button class="send-stmt-button"
                    id="{{$element->item_id}}passed"
                    verb="passed"
                    sectionId="{{$element->item_id}}"
                    courseId="{{$course->course_id}}"
                    @if(in_array($element->item_id, $myCourseProgress['passed'])))
                        disabled
                        style="padding: 5px; background: rgba(0,0,0,0); color: #c4aa33 !important;"
                    @else
                        style="padding: 5px; background: rgba(0,0,0,0); color: #eb4432;"
                    @endif>
                <i class="fas fa-check-double"></i>
            </button>

            @if($element->type->type == 'Test')
                <a href="{{ route('quiz.play', ['id' => $course->getKey(), 'section_id' => $element->getKey(), 'quiz' => json_decode($element->item_content, true)['quiz_id']]) }}">Play quiz</a>
            @endif

            <form>
                @csrf
                @method('post')
            </form><br>


            @if($element->type->type != 'Test')
                <p>{{ json_decode($element->item_content) ?? ""}}</p>
            @endif
            <br>
        </div>
        @endif
    @endforeach

    <button id="send-stmt-passed-button"
            class="rounded-black-button"
            courseId="{{ $course->course_id }}">
            {{ __('main.completeCourse') }}
    </button>

</div>

<script>
var passedContent = $('.progress').attr('passedContent');
var allContent = $('.progress').attr('allContent');
if (allContent == 0) {
    $('.progress').text('{{ __('main.progress') }}: 0%');
} else {
    $('.progress').text('{{ __('main.progress') }}: '+ Math.round((passedContent) / allContent * 100) +'%');
}

var myCourseProgressPassed = {{ json_encode($myCourseProgress['passed']) }};
var myCourseProgressLaunched = {{ json_encode($myCourseProgress['launched']) }};

$(".send-stmt-button").click(function() {
    var sectionId = $(this).attr('sectionId');
    var courseId = $(this).attr('courseId');
    var verb = $(this).attr('verb');

    $.ajax({
        headers: {
            'X-Csrf-Token': $('input[name="_token"]').val()
        },
        type: 'POST',
        dataType: 'html',
        url: '/send-'+verb+'/'+courseId+'/'+sectionId,
        data: {'myCourseProgressPassed': myCourseProgressPassed, 'myCourseProgressLaunched': myCourseProgressLaunched},
        timeout: 500,
        success: function (html) {
            if (verb === 'passed') {
                $('#' + sectionId + 'passed').text(html).prop('disabled', true).css('color', '#c4aa33');
                setTimeout(() => {s
                    $('#' + sectionId + 'passed').html('<i class="fas fa-check-double"></i>');
                }, 3000);
                $('.progress').text('Progress: '+ Math.round(++passedContent / allContent * 100) +'%');
            }
            if (verb === 'launched') {
                $('#' + sectionId + 'launched').text(html).prop('disabled', true).css('color', '#c4aa33');;
                setTimeout(() => {
                    $('#' + sectionId + 'launched').html('<i class="fas fa-check"></i>');
                }, 3000);
            }
        },
        error: function (jqXhr, textStatus, errorMessage) {
            console.log('Error' + errorMessage);
        }
    });
});

$("#send-stmt-passed-button").click(function() {
    var courseId = $(this).attr('courseId');

    $.ajax({
        headers: {
            'X-Csrf-Token': $('input[name="_token"]').val()
        },
        type: 'POST',
        dataType: 'html',
        url: '/send-passed/'+courseId,
        data: {'myCourseProgressPassed': myCourseProgressPassed, 'myCourseProgressLaunched': myCourseProgressLaunched},
        timeout: 500,
        success: function (html) {
            $('#send-stmt-passed-button').text(html).prop('disabled', true).css('background', '#3f3f3f');
            setTimeout(() => {
                $('#send-stmt-passed-button').text('{{ __('main.courseCompleted') }}');
            }, 1500);
        },
        error: function (jqXhr, textStatus, errorMessage) {
            $(this).text('Error' + errorMessage);
        }
    });
});
</script>

@component('components.footer')
@endcomponent
