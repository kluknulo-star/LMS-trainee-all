@component('components.header')
@endcomponent

    <div class="welcome__container" style="margin-top: 100px; text-align: center;">
        @if (\Session::has('success'))
            <div class="alert" style="background: #5ca143; color: #171923 !important; width: 500px">
                <ul>
                    <li><p class="success-message" style="font-size: 30px">{{ \Session::get('success') }}</p></li>
                </ul>
            </div>
        @endif
        <div class="row justify-content-center">
            <div class="col-md-8">
                <p class="users__title" style="height: 150px; width: 500px; font-size: 30px">To continue using this site, you need to check your mail</p>
                        <form class="d-inline" method="POST" action="{{ route('users.send.email.confirmation', ['id' => $user->getKey()]) }}">
                            @csrf
                            <input type="hidden" value="{{ $user->email }}" name="email">
                            @if (!\Session::has('success'))
                                <button type="submit" class="cancel-button" style="border-radius: 10px; width: 170px; height: 50px; font-size: 30px;">Get mail</button>
                            @endif
                        </form>
                </div>
            </div>
        </div>

@component('components.footer')
@endcomponent

