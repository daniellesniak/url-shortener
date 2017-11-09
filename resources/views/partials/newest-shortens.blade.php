@if(count($newestShortens) > 0)
    {{-- Most Recent Shortens --}}
    <div class="container is-hidden-mobile" id="newest-shortens">
        <h1 class="title">Most Recent Shortens</h1>
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
            @foreach($newestShortens as $newestShorten)
                <tr>
                    <td>
                        <a href="{{ $newestShorten->protocol.$newestShorten->url }}">{{ $newestShorten->url }}</a>
                    </td>
                    <td>
                        <a href="{{ route('home')}}/{{ $newestShorten->slug }}">{{ route('home') }}/{{ $newestShorten->slug }}</a>
                    </td>
                    <td></td>
                    <td>
                        {{ $newestShorten->created_at->diffForHumans() }}
                    </td>
                    {{-- Total Redirects --}}
                    <td>{{ $newestShorten->getRedirectsCount() }}</td>
                    {{-- [statistics_button] --}}
                    <td>
                        <a title="View statistics of this shorten!" href="{{ url('/', [ $newestShorten['slug'], 'stats']) }}" class="button is-small"><i class="fa fa-bar-chart"></i></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif
