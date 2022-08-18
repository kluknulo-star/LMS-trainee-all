@component('components.header')
@endcomponent

@component('components.aside')
@endcomponent

<div class="container">
    <img src="" alt="" class="courses__img">
    <div class="h3 mb20">Title: {{ $course->title }}</div>
    <div class="margin5">
        Author: <a
            href="{{ route('users.show', ['id' => $course->user()->value("user_id")]) }}">{{ $course->user()->value("email") }}</a>
    </div>
    <div class="courses__course-description mb30">Description: {{ Str::limit($course->description, 200, '...') }}</div>
    <div class="h3 mb20">Course content:</div>
    @foreach(json_decode($course->content) as $element)
        <div class="margin20-0">
            <b>{{$element->title}}</b>
            ❮{{$element->type}}❯

            <button style="padding: 5px; background: rgba(0,0,0,0); color: #eb4432;"
                    class="send-stmt-button"
                    id="{{$element->section_id}}launched"
                    verb="launched"
                    sectionId="{{$element->section_id}}"
                    courseId="{{$course->course_id}}">
                <i class="fas fa-check"></i>
            </button>

            <button style="padding: 5px; background: rgba(0,0,0,0); color: #eb4432;"
                    class="send-stmt-button"
                    id="{{$element->section_id}}passed"
                    verb="passed"
                    sectionId="{{$element->section_id}}"
                    courseId="{{$course->course_id}}">
                <i class="fas fa-check-double"></i>
            </button>

            <form>
                @csrf
                @method('post')
            </form>

            <p>{{$element->content ?? ""}}
            </p>
            <br>
        </div>
    @endforeach
</div>

<script>
    $(".send-stmt-button").click(function() {
        var sectionId = $(this).attr('sectionId');
        var courseId = $(this).attr('courseId');
        var verb = $(this).attr('verb');

        console.log('начала работать');
        $.ajax({
            headers: {
                'X-Csrf-Token': $('input[name="_token"]').val()
            },
            type: 'POST',  // http method
            // data: { sectionId: sectionId, courseId: courseId, verb: verb },  // data to submit
            dataType: 'html',
            url: '/send-'+verb+'/'+courseId+'/'+sectionId,
            timeout: 500,
            success: function (html) {
                console.log('/send-'+verb+'/'+courseId+'/'+sectionId);
                if (verb === 'passed') {
                    $('#' + sectionId + 'passed').prop('disabled', true).css('color', 'green');
                }
                if (verb === 'launched') {
                    $('#' + sectionId + 'launched').prop('disabled', true).css('color', 'green');;
                }
                console.log('отработала');
            },
            error: function (jqXhr, textStatus, errorMessage) {
                $(this).text('Error' + errorMessage);
            }
        });
    });
</script>

@component('components.footer')
@endcomponent
