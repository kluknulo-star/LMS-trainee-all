@component('components.header')
@endcomponent

@component('components.aside')
@endcomponent

<div class="container">
    <div class="edit">
        <div class="edit__container edit__container-course classic-box mrauto">
            <div class="edit__title h2 mb30">Edit Course</div>
            <form method="post" action="{{ route('courses.update', ['id' => $course->course_id]) }}" class="edit__form form">
                @csrf
                @method('patch')
                <input name="course_id" value="{{ old('course_id') ?? $course->course_id }}" type="hidden" class="edit__input col-input input">
                <input name="title" value="{{ old('title') ?? $course->title }}" class="edit__input col-input input">
                <textarea name="description" class="edit__input col-input input">{{ old('description') ?? $course->description }}</textarea>
                <button type="submit" class="edit__button rounded-red-button button">Save changes</button>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

@component('components.footer')
@endcomponent
