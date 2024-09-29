@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row mb-4">
        <div class="col-lg-12 margin-tb flex flex-col md:flex-row justify-between mb-4 gap-[20px] md:gap-0">
            <div class="pull-left flex gap-[20px]">
                <img class="rounded d-block w-[20%] h-auto" src="{{asset('img/Dn-prime17 logo ai.webp')}}" alt="logo">
                <h2 class="text-[55px] my-auto font-bold text-blue-700">{{__('DNreviews')}}</h2>
            </div>
            <div class="pull-right mb-2 md:w-[17%]">
                @auth
                    @if(Auth::user())
                        <a class="btn btn-success" href="{{ route('review.make') }}">{{__('Nieuw review')}}</a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        @if ($message = Session::get('error'))
            <div class="alert">
                <p>{{ $message }}</p>
            </div>
        @endif
        @forelse ($reviews as $review)
        <div class="row border mb-3 !border-blue-500">
            <div class="col-md-8 border-r py-4 flex flex-col md:flex-row gap-[10px] bg-green-200 !border-blue-500">
                <div class="md:w-[70%] p-2">
                    <a class="hover:no-underline" href="{{ route('review.single', $review->id) }}"><h1 class="text-[25px] mb-2 text-green-600 font-extrabold">{{$review->title}}</h1></a>
                    <h2 class="text-[20px] mb-2 font-bold text-blue-500">{{$review->movie}}</h2>
                    <p class="mb-4">{{ Str::limit($review->content, 500, '...') }}</p>
                    <a class="btn btn-success" href="{{ route('review.single', $review->id) }}">{{__('Lees verder')}}</a>  
                </div>
                <div class="md:w-[30%]">
                    <a href="{{ route('review.single', $review->id) }}"><img src="{{ asset($review->image) }}" alt="Gecentreerde Afbeelding" class="rounded mx-auto d-block w-full h-auto"></a>
                </div>
            </div>
            <div class="col-md-4 pt-4 px-[10px] bg-blue-200 border !border-blue-500 md:border-[0px]">
                <h3 class="text-[20px]">{{__('Meest recente comments')}}</h3>
                @forelse ($review->recent_comments as $comment)
                <div class="border bg-gray-200 flex gap-[10px] mt-3 !border-blue-500">
                    <div class="border-r p-[10px] border-blue-500">
                        <img class="h-[50px] w-[50px]" src="https://i.pravatar.cc/100?id={{$comment->id}}" alt="">
                    </div>
                    <div class="p-[10px]">
                        <h3 class="mb-2 border-b text-left">{{$comment->name}}</h3>
                        <p class="text-left">{{$comment->reaction}}</p>
                        <p class="text-left text-gray-600">
                            {{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}
                        </p>
                    </div>
                </div>
                @empty
                    <div class="mx-[10px] my-3">
                        <p>{{__('Nog geen comments, wees de eerste die commentaar geeft!')}}</p>
                    </div>
                @endforelse
                @guest
                    <div class="mx-[10px] my-3">
                        <a class="text-black hover:text-blue" href="{{ route('login') }}">{{__('Log in om commentaar te plaatsen')}}</a>
                    </div>
                @else
                    <form class="mt-[10px]" method="POST" action="{{ route('comment.create') }}">
                        @csrf
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>{{__('doe mee met het gesprek!')}}:</strong>
                                <textarea class="form-control" name="comment" placeholder="vul hier je comment in" maxlength="500" rows="1">{{ old('comment') }}</textarea>
                                @error('comment')
                                <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <input type="hidden" name="review" value="{{ $review->id }}">
                        <input type="hidden" name="creator" value="{{ Auth::user()->id }}">

                        <button type="submit" class="btn btn-primary mb-4">Submit</button>
                    </form>
                @endguest
                @if($review->recent_comments->count() > 0)
                    <a class="btn btn-success mb-10" href="{{ route('review.single', $review->id) }}">{{ __('Lees meer') }}</a>
                @endif
            </div>
        </div>
        @empty
        <div class="row border-t">
            <div class="col-md-8">
                <h3>{{__('geen reviews beschikbaar')}}</h3>
            </div>
            <div class="col-md-4">
            </div>
        </div>
        @endforelse
        {{ $reviews->links() }}
</div>
@endsection
