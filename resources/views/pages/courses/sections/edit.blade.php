@component('components.header')
@endcomponent

@component('components.aside')
@endcomponent

<div class="container">
    <div class="edit flex">
        <div class="edit__container edit__container-course classic-box mrauto">
            <div class="edit__title h2 mb30">Edit Course Section</div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="post" action="{{ route('courses.update.section', ['id' => $courseId, 'section_id' => $section['section_id']]) }}" class="edit__form form">
                @csrf
                @method('patch')
                <select class="select mb20" name="type" id="">
                    {{ $section['type'] }}
                    <option name="Article" value="Article" @if ($section['type'] == 'Article') selected @endif class="edit__input col-input input">Article</option>
                    <option name="YoutubeVideoLink" value="YoutubeVideoLink"  @if ($section['type'] == 'YoutubeVideoLink') selected @endif class="edit__input col-input input">YouTube Video Link</option>
                    <option name="Test" value="Test"  @if ($section['type'] == 'Test') selected @endif class="edit__input col-input input">Test</option>
                </select>
                <input name="title" value="{{ old('title') ?? $section['title'] }}" class="edit__input col-input input">
                <input name="content" value="{{ old('content') ?? $section['content'] ?? '' }}" class="edit__input col-input input">
                <button type="submit" class="edit__button rounded-black-button button mb15">Save changes</button>
                <a href="{{ route('courses.edit', ['id' => $courseId]) }}" class="back-button"><i class="fas fa-arrow-left"></i> Back</a>
            </form>
        </div>
    </div>
</div>

@component('components.footer')
@endcomponent
