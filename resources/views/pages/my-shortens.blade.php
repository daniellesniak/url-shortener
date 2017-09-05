@extends('layouts.base')

@section('title', 'Your shorten URL is ready!')

@section('content-fluid')
    <div class="container" style="margin-top: 50px;">
        <div class="content has-text-centered">
            <h1 class="title is-1">My Shortens</h1>
        </div>

        <table class="table">
            <thead>
            <th>#</th>
            <th>Url Destination</th>
            <th>Shorten Url</th>
            <th>Created</th>
            <th>Total Redirects</th>
            </thead>

            <tbody>
            @forelse($myShortens as $myShorten)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>
                        <a href="{{ $myShorten['protocol'] . $myShorten['url'] }}">
                            {{ $myShorten['protocol'] . $myShorten['url'] }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ url('/') . '/' . $myShorten['string_id'] }}">{{ url('/') . '/' . $myShorten['string_id'] }}</a>
                    </td>
                    <td>todo</td>
                    <td>{{ $myShorten['redirects_count'] }}</td>
                </tr>
            @empty
                <p>No shortens found!</p>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection