@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h1 class="">MESAJE</h1>
        <p class="mb-4">Aici puteți comunica prin mesaje private cu diferite persoane</p>
        <div class="row">
            <div class="col-md-4">
                <div class="user-wrapper shadow-sm">
                    <ul class="users">
                        @foreach ($users as $user)
                            <li class="user" id="{{ $user->id }}">
                                @if ($user->unread)
                                    <span class="pending">{{ $user->unread }}</span>
                                @endif
                                <div class="media">
                                    <div class="media-left">
                                        <img src="https://www.gravatar.com/avatar/?d=mp" alt="" class="media-object">
                                    </div>
                                    <div class="media-body">
                                        <p class="name">{{ $user->name }}</p>
                                        <p class="email">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-md-8" id="messages">

            </div>
        </div>
    </div>

<script defer>
    $(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let receiverId = '';
        let myId = "{{ Auth::user()->id }}";

        // pusher service
        Pusher.logToConsole = true;

        let pusher = new Pusher('088d6703ce3dba36c29d', {
            cluster: 'eu'
        });

        let channel = pusher.subscribe('my-channel');
        channel.bind('my-event', function(data) {
            // alert(JSON.stringify(data));
            if (myId == data.from) {
                $(`#${data.to}`).click();
            } else if (myId == data.to) {
                if (receiverId == data.from) {
                    $(`#${data.from}`).click();
                } else {
                    let pending = parseInt($(`#${data.from}`).find('.pending').html());
                    if (pending) {
                        $(`#${data.from}`).find('.pending').html(pending + 1);
                    } else {
                        $(`#${data.from}`).append('<span class="pending">1</span>');
                    }
                }
            }
        });

        $('.user').click(function() {
            $('.user').removeClass('active');
            $(this).addClass('active');
            $(this).find('.pending').remove();
            receiverId = $(this).attr('id');

            $.ajax({
                type: "get",
                url: "{{ route('chat.messages') }}",
                data: {
                    receiverId: receiverId
                },
                cache: false
            }).done((response, status, jqXhr) => {
                $('#messages').html(response);
                scrollToBottom();
            }).fail((jqXhr, status, error) => {
                console.error(`error ${status}, ${error}`);
            });
        });

        $(document).on('keyup', '.input-text input', sendMessage);
        $(document).on('click', '.btn-send-message', sendMessageBtn);

        function sendMessage(e) {
            let message = $(this).val();
            if (e.keyCode == 13 && message != '' && receiverId != '') {
                $(this).val('');
                $.ajax({
                    type: "post",
                    url: "{{ route('chat.messages') }}",
                    // dataType: 'json',
                    data: {
                        receiverId: receiverId,
                        message: message
                    },
                    cache: false
                }).done((response, status, jqXhr) => {
                }).fail((jqXhr, status, error) => {
                    console.error(`error ${status}, ${error}`);
                }).always(() => {
                    scrollToBottom();
                });
            }
        };

        function sendMessageBtn(e) {
            let message = $('.input-text input').val();
            if (message != '' && receiverId != '') {
                $('.input-text input').val('');
                $.ajax({
                    type: "post",
                    url: "{{ route('chat.messages') }}",
                    // dataType: 'json',
                    data: {
                        receiverId: receiverId,
                        message: message
                    },
                    cache: false
                }).done((response, status, jqXhr) => {
                }).fail((jqXhr, status, error) => {
                    console.error(`error ${status}, ${error}`);
                }).always(() => {
                    scrollToBottom();
                });
            }
        };

        function scrollToBottom() {
            $('.message-wrapper').animate({
                scrollTop: $('.message-wrapper').get(0).scrollHeight
            }, 100)
        }
    });
</script>
@endsection
