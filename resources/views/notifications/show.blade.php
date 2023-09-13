@extends('layouts.main')

@section('content')
    <div class="container">
        <p class="my-4 font-whight-bold">{{$title}}</p>
        <div class="row">
            @forelse($notifications as $notifi)
                <div class="notification_body">
                    <div class="card mb-2" style="width: 56rem;">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-1">
                                    <div class="icon-circle bg-secondary">
                                        <i class="fas fa-bell text-white"></i>
                                    </div>
                                </div>
                                <div class="col-10">
                                    <i class="far fa-clock"></i> <span class="comment-date text-secondary">{{$notifi->created_at->diffForHumans()}}</span>
                                    @if ($notifi->sucess)
                                        <p class="mt-3" style="width: 40rem;">Congratulations, your video has been processed {{$notifi->notification}} successfuly</p>
                                    @else
                                        <p class="mt-3" style="width: 40rem;">Unfortunately, an unexpected error occurred while processing the video {{$notifi->notification}} Please upload it again</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="mx-auto col-8">
                    <div class="alert alert-primary text-center" role="alert">
                        there is no notifications
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
