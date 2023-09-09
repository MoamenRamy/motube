@extends('theme.default')

@section('head')
    <link href="{{ asset('theme/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('heading')
    Blocked Channels
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
                    <th>Created at</th>
                    <th>Unblock</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($channels as $channel)
                    <tr>
                        <td>{{ $channel->name }}</td>
                        <td>{{ $channel->email }}</td>
                        <td>{{ $channel->created_at->diffForHumans()}}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.channels.unblock', $channel)}}" style="display:inline-block">
                                @method('patch')
                                @csrf

                                @if ($channel->block)
                                    <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Are you sure you want to unblock this channel?')"><i class="fa fa-lock-open"></i>Unblock</button>
                                @endif
                            </form>
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
