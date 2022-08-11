@component('components.header')
@endcomponent

@component('components.aside')
@endcomponent

<div class="container">
    <div class="edit">
        <div class="edit__container edit__container-course classic-box mrauto">
            <div class="edit__title h2 mb30">Edit Course</div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="post" action="{{ route('courses.update', ['id' => $course->course_id]) }}" class="edit__form form">
                @csrf
                @method('patch')
                <input name="course_id" value="{{ old('course_id') ?? $course->course_id }}" type="hidden" class="edit__input col-input input">
                <input name="title" value="{{ old('title') ?? $course->title }}" class="edit__input col-input input">
                <textarea name="description" class="edit__input col-input input">{{ old('description') ?? $course->description }}</textarea>
                <button type="submit" class="edit__button rounded-black-button button mb15">Save changes</button>
            </form>

            <button type="submit" class="edit__button rounded-black-button button mb15">
                <a href="{{ route('courses.edit.assignments', ['id' => $course->course_id]) }}" >Assign students</a>
            </button>
            <button class="edit__button rounded-red-button button mb15" onclick="document.getElementById('delete-modal-<?= $course->course_id  ?>').style.display = 'flex'">
                Delete
            </button>

        </div>
    </div>
    <br>
    <div class="edit">
        <div class="edit__container edit__container-course classic-box mrauto">
            <div class="users__title h3">
                Content:
                <div class="users__after-title-links">
                    <a href="" class="users__title-link">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
            </div>
            {{var_dump($course->content)}}
        </div>
    </div>
</div>



<div class="modal" id="delete-modal-{{ $course->course_id }}">
    <div class="modal-box">
        <p class="modal-text modal-text-delete mb20 mr20">You sure to <span>delete</span> course: "{{ $course->title }}"?</p>
        <div class="modal-buttons">
            <form class="table-action-form" action="{{ route('courses.delete', ['id' => $course->course_id]) }}" method="post">
                @csrf
                @method('delete')
                <input name="user_id" type="hidden" value="{{ $course->course_id }}">
                <button type="submit" class="table-action-button confirm-button">Confirm</button>
            </form>
            <button onclick="document.getElementById('delete-modal-<?= $course->course_id ?>').style.display = 'none'" class="table-action-button cancel-button">Cancel</button>
        </div>

    </div>
</div>

@component('components.footer')
@endcomponent
