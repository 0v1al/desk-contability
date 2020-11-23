<div class="message-wrapper shadow-sm">
    <ul class="messages">
        @if (count($messages) > 0)
            @foreach ($messages as $message)
                <li class="message clearfix">
                    <div class="{{ ($message->from == Auth::user()->id) ? 'sent' : 'received' }} shadow-sm">
                        <p>{{ $message->message  }}</p>
                        <p class="text-muted">{{ date('d M y, h:i a', strtotime($message->created_at)) }}</p>
                        @if (Auth::user()->id === $message->from)
                            <p class="text-muted">{{ Auth::user()->name }}</p>
                        @else
                            <p class="text-muted">{{ $userNameTo  }}</p>
                        @endif
                    </div>
                </li>
            @endforeach
        @else
            <p class="text-center p-3">Nu aveți niciun mesaj momentan</p>
        @endif
    </ul>
</div>
<div class="input-text shadow-sm">
    <input type="text" name="message" class="submit message-content">
    <button class="btn-send-message btn btn-action text-uppercase">Trimite</button>
</div>
