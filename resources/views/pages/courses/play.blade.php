@component('components.header')
@endcomponent

@component('components.aside')
@endcomponent

<div class="container">
    <img src="" alt="" class="courses__img">
    <div class="h1 mb20">{{ __('main.title') }}: {{ $course->title }}</div>
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

            <form>
                @csrf
                @method('post')
            </form><br>

            <p>{{ json_decode($element->item_content) ?? ""}}
            </p>
            <br>
        </div>
    @endforeach
</div>

<div class="progress" id="progress"></div>

<script>
var passedContent = $('.progress').attr('passedContent');
var allContent = $('.progress').attr('allContent');
if (allContent == 0) {
    $('.progress').text('{{ __('main.progress') }}: 0%');
} else {
    $('.progress').text('{{ __('main.progress') }}: '+ Math.round((passedContent) / allContent * 100) +'%');
}

$(".send-stmt-button").click(function() {
    var sectionId = $(this).attr('sectionId');
    var courseId = $(this).attr('courseId');
    var verb = $(this).attr('verb');
    console.log('/send-'+verb+'/'+courseId+'/'+sectionId);

    console.log('начала работать');
    $.ajax({
        headers: {
            'X-Csrf-Token': $('input[name="_token"]').val()
        },
        type: 'POST',  // http method
        dataType: 'html',
        url: '/send-'+verb+'/'+courseId+'/'+sectionId,
        timeout: 500,
        success: function (html) {
            if (verb === 'passed') {
                $('#' + sectionId + 'passed').text(html).prop('disabled', true).css('color', '#c4aa33');
                setTimeout(() => {
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
