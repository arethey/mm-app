@if(Session::has('post-register'))
    <script>
        iziToast.success({
            close: false,
            displayMode: 2,
            layout: 2,
            drag: false,
            position: 'topCenter',
            title: 'Success!',
            message: "{{ Session::get('post-register') }}",
            transitionIn: 'bounceInDown',
            transitionOut: 'fadeOutUp',
        });
    </script>
@elseif(Session::has('account-verification-error'))
    <script>
        iziToast.warning({
            close: false,
            displayMode: 2,
            position: 'topCenter',
            drag: false,
            title: 'Oops!',
            message: "{{ Session::get('account-verification-error') }}",
            transitionIn: 'bounceInDown',
            transitionOut: 'fadeOutUp',
        });
    </script>
@elseif(Session::has('login-error'))
    <script>
        iziToast.error({
            close: false,
            displayMode: 2,
            position: 'topCenter',
            drag: false,
            title: 'Oops!',
            message: "{{ Session::get('login-error') }}",
            transitionIn: 'bounceInDown',
            transitionOut: 'fadeOutUp',
        });
        </script>
@elseif(Session::has('auth-error'))
    <script>
        iziToast.error({
            close: false,
            displayMode: 2,
            position: 'topCenter',
            drag: false,
            title: 'Oops!',
            message: "{{ Session::get('auth-error') }}",
            transitionIn: 'bounceInDown',
            transitionOut: 'fadeOutUp',
        });
    </script>
@endif