@extends('theme.default')

@section('head')
    <link href="{{ asset('theme/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('heading')
All channels
@endsection

@section('content')
<hr>
<div class="row">
    <div class="col-md-12">
        <table id="videos-table" class="table table-stribed" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Channel name</th>
                    <th>Email</th>
                    <th>videos count</th>
                    <th>total views</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($channels as $channel)
                    <tr>
                        <td><a href="{{ route('main.channels.videos', $channel) }}">{{ $channel->name }}</a></td>
                        <td>{{ $channel->email }}</td>
                        <td>{{ $channel->videos->count() }}</td>
                        <td>
                            <p>{{ $channel->views->sum('views_number') }}</p>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('script')
<!-- Page level plugins -->
<script src="{{ asset('theme/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('theme/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#videos-table').DataTable({
            // "language": {
            //     "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Arabic.json"
            // }
        });
    });
</script>
@endsection
