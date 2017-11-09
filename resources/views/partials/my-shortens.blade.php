@if($myShortens != null)
    <div class="container is-hidden-mobile" style="margin-top: 20px">
        {{-- Handle Message --}}
        @if(session('message'))
            <div class="notification {{ session('message')['message_class'] }}">
                <button class="delete" onclick="disposeMessage()"></button> {{ session('message')['message_text'] }}
            </div>
        @endif

        <h1 class="title">My Shortens</h1>
        <table class="table is-striped is-fullwidth">
            <thead>
            <tr>
                <th>URL Destination</th>
                <th>Shorten URL</th>
                <th></th>
                <th>Created</th>
                <th>Total Redirects</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($myShortens as $myShorten)
                <tr>
                    {{-- URL Destination --}}
                    <td><a href="{{ $myShorten->url }}">{{ $myShorten->url }}</a></td>

                    {{-- Shorten URL --}}
                    <td><a href="{{ $myShorten->shortenUrl() }}">{{ $myShorten->shortenUrl() }}</a></td>
                    <td><a data-clipboard-text="{{ $myShorten->shortenUrl() }}"
                           title="Copy to clipboard!" class="button is-small clipboard">Copy</a></td>

                    {{-- Created --}}
                    <td>{{ $myShorten->created_at->diffForHumans() }}</td>

                    {{-- Total Redirects --}}
                    <td>{{ $myShorten->getRedirectsCount() }}</td>

                    {{-- Actions [statistics, hide] --}}
                    <td>
                        <a title="Show shorten's statistics!" href="{{ action('ShortenController@stats', $myShorten['slug']) }}" class="button is-small"><i class="fa fa-bar-chart"></i></a>
                        <a title="Hide this shorten!" href="{{ action('ShortenController@hideShorten', $myShorten['slug']) }}" class="button is-small"><i class="fa fa-eye-slash"></i></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{-- Pagination --}}
        {{ $myShortens->links() }}
    </div>
@endif
