@extends('theme.default')

@section('head')
    <link href="{{ asset('theme/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('heading')
Channels Permissions
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
                    <th>Channel type</th>
                    <th>Edit</th>
                    <th>Dekete</th>
                    <th>Block</th>

                </tr>
            </thead>

            <tbody>
                @foreach ($channels as $channel)
                    <tr>
                        <td>{{ $channel->name }}</td>
                        <td>{{ $channel->email }}</td>
                        <td>{{ $channel->isSuperAdmin() ? 'Super Admin' : ($channel->isAdmin() ? 'Admin' : 'User') }}</td>
                        <td>
                            <form class="ml-4 form-inline" method="POST" action="{{route('admin.channels.update', $channel)}}" style="display:inline-block">
                                @method('patch')
                                @csrf
                                <select class="form-control form-control-sm" name="administration_level">
                                    <option selected disabled>Choose type</option>
                                    <option value="0">User</option>
                                    <option value="1">Admin</option>
                                    <option value="2">Super Admin</option>
                                </select>
                                <button type="submit" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> Edit</button>
                            </form>
                        </td>
                        <td>
                            <form method="POST" action="{{route('admin.channels.delete', $channel)}}" style="display:inline-block">
                                @method('delete')
                                @csrf
                                @if (auth()->user() != $channel && !$channel->isSuperAdmin())
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this channel?')"><i class="fa fa-trash"></i> Delete</button>
                                @else
                                    <div class="btn btn-danger btn-sm disabled"><i class="fa fa-trash"></i> Delete </div>
                                @endif
                            </form>
                        </td>
                        <td>
                            <form method="POST" action="{{route('admin.channels.block', $channel)}}" style="display:inline-block">
                                @method('patch')
                                @csrf
                                @if (auth()->user() != $channel && !$channel->isSuperAdmin())
                                    @if ($channel->block)
                                    <div class="btn btn-warning btn-sm disabled"><i class="fas fa-lock"></i> Blocked </div>
                                    @else
                                    <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('are you sure you want to block this channel?')"><i class="fa fa-lock"></i> Block</button>
                                    @endif
                                @else
                                    <div class="btn btn-warning btn-sm disabled"><i class="fas fa-lock"></i> Block </div>
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
