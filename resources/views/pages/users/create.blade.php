@component('components.header')
@endcomponent

@component('components.aside')
@endcomponent


<div class="container">
    <div class="create">
        <div class="create__container classic-box mrauto">
            <div class="create__title h2 mb30">Create Accounts</div>

            <form method="post" action="{{ url('/users') }}" class="create__form form">
                <input name="count" type="number" placeholder="Count" class="create__input col-input input">
                <button type="submit" class="create__button rounded-red-button button">Create</button>
            </form>

        </div>
    </div>
</div>
@component('components.footer')
@endcomponent
