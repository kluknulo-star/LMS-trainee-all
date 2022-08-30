@component('components.header')
@endcomponent

@component('components.aside')
@endcomponent

<div class="container">
    @if (!empty(session()->get('success')))
        <div class="success">{{ session()->get('success') }}</div>
    @endif
    <div class="edit flex">
        <div class="edit__container edit__container-course classic-box mrauto">
            <div class="edit__title h2 mb30">{{ __('main.edit') }} {{ Lang::choice('main.sectionVin', 1) }}</div>
            <form method="post" action="{{ route('courses.update.section', ['id' => $courseId, 'section_id' => $section->item_id]) }}" class="edit__form form">
                @csrf
                @method('patch')
{{--                @if ($errors->has('sectionType'))<div class="alert alert-danger"><ul><li>{{ $errors->first('sectionType') }}</li></ul></div>@endif--}}
{{--                <select class="select mb20" name="sectionType" id="">--}}
{{--                    <option name="Article" value="1" @if ($section->type_id == 1) selected @endif class="edit__input col-input input">{{ __('main.article') }}</option>--}}
{{--                    <option name="YoutubeVideoLink" value="2"  @if ($section->type_id == 2) selected @endif class="edit__input col-input input">{{ __('main.YouTubeLink') }}</option>--}}
{{--                    <option name="Test" value="3"  @if ($section->type_id == 3) selected @endif class="edit__input col-input input">{{ __('main.quiz') }}</option>--}}
{{--                </select>--}}
                @if ($errors->has('sectionTitle')) <div class="alert alert-danger"><ul><li>{{ $errors->first('sectionTitle') }}</li></ul></div>@endif
                <input name="sectionTitle" value="{{ old('sectionTitle') ?? $section->title }}" placeholder="{{ __('main.sectionTitle') }}" class="edit__input col-input input">
                @if ($section->type_id !== 3) @if ($errors->has('sectionContent')) <div class="alert alert-danger"><ul><li>{{ $errors->first('sectionContent') }}</li></ul></div>@endif
                <textarea oninput="countContent()"
                          id="section-content"
                          name="sectionContent"
                          placeholder="@if ($section->type_id != 3) {{ __('main.sectionContent') }} @else Enter count of questions to pass the quiz @endif "
                          style="width: 1298px; min-height: 300px; max-height: 300px;"
                          class="edit__input col-input input">@if ($section->type_id != 3){{ old('sectionContent') ?? json_decode($section->item_content) ?? '' }}@else @endif</textarea>
                <p id="content-count"></p>/2048
                @elseif($section->type_id === 3)
                    <a href="{{ route('quiz.questions.show', ['id' => $courseId, 'section_id' => $section->item_id, 'quiz' => json_decode($section->item_content, true)['quiz_id']]) }}" class="text-al-cent rounded-red-button mb15 whitesmoke-text">Edit quiz questions</a>
                @endif
                <button type="submit" class="rounded-black-button button mb15">{{ __('main.save')}} {{ __('main.changes') }}</button>
                <a href="{{ route('courses.edit', ['id' => $courseId]) }}" class="back-button"><i class="fas fa-arrow-left"></i> {{ __('main.back') }}</a>
            </form>
        </div>
    </div>
</div>

<script>
    function countContent() {
        var content = document.getElementById('section-content').value;
        document.getElementById('content-count').textContent = content.length;
    }
    countContent();
</script>

@component('components.footer')
@endcomponent
