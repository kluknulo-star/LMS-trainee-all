@component('components.header')
@endcomponent

@component('components.aside')
@endcomponent

<div class="container">
    <div class="margin5">
        <p class="welcome__logo fontBig">About CourseZone</p>
    </div>
    <div class="margin5">
        <p class="font24">CourseZone is a multidisciplinary, independent learning platform.</p>
    </div>
    <br>
    <div class="margin5">
        <p class="font24">The mission of Skillbox is to enable everyone to be relevant and in—demand specialist right now.
            Regardless of age and geography.</p>
    </div>
    <br>
    <div class="margin5">
        <p class="font24">CourseZone is suitable both for the organization of training in universities and training centers,
            and for corporate training. CourseZone is relatively more difficult to set up than commercial platforms.
            But its administration may be more expensive, due to the involvement of third-party specialists and the
            maintenance of its own server. As an analogue, you can try iSpring Learn.</p>
    </div>
    <br>
    <p class="welcome__logo font24 margin-top-55">The founders of CourseZone</p>
    <div class="row">
        <div>
            <div><img class="size-img" src="images/system-images/dev1.jpg" alt=""></div>
            <div class="margin5">Anatoliy Denezhniy</div>
        </div>
        <div>
            <div><img class="size-img" src="images/system-images/dev2.jpg" alt=""></div>
            <div class="margin5">Alexander Zuev</div>
        </div>
        <div>
            <div><img class="size-img" src="images/system-images/dev3.jpg" alt=""></div>
            <div class="margin5">Kirill Tsybulsky</div>
        </div>
        <div>
            <div><img class="size-img" src="images/system-images/dev4.jpg" alt=""></div>
            <div class="margin5">Kirill Kilyakov</div>
        </div>
    </div>
    <div class="margin-top-55">
        <p class="welcome__logo font24">Contact us</p>
        <form class="decor" method="post" action="">
            <div class="form-left-decoration"></div>
            <div class="form-right-decoration"></div>
            <div class="circle"></div>
            <div class="form-inner">
                <h3>Write to us</h3>
                <input type="text" placeholder="Your name">
                <input type="email" placeholder="Email">
                <textarea placeholder="Message..." rows="3"></textarea>
                <input type="submit" value="Send a message">
            </div>
        </form>
        <ul class="social-icons">
            <li><a class="social-icon-twitter" href="https://twitter.com/coursezone_ru" title="Официальная страница CourseZone в Twitter" target="_blank" rel="noopener"></a></li>
            <li><a class="social-icon-fb" href="https://www.facebook.com/coursezone.ru" title="Официальная страница CourseZone в Facebook" target="_blank" rel="noopener"></a></li>
            <li><a class="social-icon-vk" href="https://vk.com/coursezone" title="Официальная страница CourseZone в ВКонтакте" target="_blank" rel="noopener"></a></li>
            <li><a class="social-icon-telegram" href="https://t.me/coursezone_ru" title="Официальная страница CourseZone в Telegram" target="_blank" rel="noopener"></a></li>
            <li><a class="social-icon-youtube" href="https://www.youtube.com/channel/CourseZone" title="Официальный канал CourseZone на Youtube" target="_blank" rel="noopener"></a></li>
        </ul>
    </div>
</div>

@component('components.footer')
@endcomponent
