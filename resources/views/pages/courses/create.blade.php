@component('components.header')
@endcomponent

@component('components.aside')
@endcomponent


<div class="container">
    <div class="create">
        <div class="create__container classic-box mrauto">
            <div class="create__title h2 mb30">Create Course</div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="post" action="{{ route('courses.store') }}" class="create__form form">
                @csrf
                @method('post')
                <input name="title" value="{{ old('title') }}" type="text" placeholder="Title" class="create__input col-input input" required>
                <textarea name="description" placeholder="Description" class="create__input col-input input">{{ old('description') }}</textarea>
                <button type="submit" class="create__button rounded-red-button button">Create</button>
            </form>

        </div>
    </div>
</div>
@component('components.footer')
@endcomponent
