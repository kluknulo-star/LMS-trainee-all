@component('components.header')
@endcomponent

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">To confirm your email address, please, click the link below</div>

                <div class="card-body">
                    <a href="{{ route('users.email.confirmed', ['id' => $user->getKey(), 'token' => $token]) }}">Confirm my email</a>
                </div>
            </div>
        </div>
    </div>
</div>

@component('components.footer')
@endcomponent

