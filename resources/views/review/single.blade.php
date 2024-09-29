@extends('layouts.app')

@section('content')
    <div class="w-[50%] md:w-[17%] mb-4 ml-[7.5%] md:ml-[2.8%]">
        <a class="btn btn-primary flex gap-[5px]" href="{{ route('review.index') }}">
            {{__('Terug naar review-lijst')}}
        </a>
    </div>
    @auth
        @if (Auth::user()->id == $review->user_id)
            <section class="insight mb-4">
                <div class="container">
                    <table>
                        <tbody>
                        <td>
                            <div class="col align-right">
                                <a class="btn btn-primary flex gap-[5px]" href="{{ route('review.edit', $review) }}">
                                    <i class="flex flex-col justify-center fa-solid fa-pen-to-square"></i>{{__('Review bewerken')}}
                                </a>
                            </div>
                        </td>
                        <td>
                            <div class="col align-right">
                                <button class="btn btn-warning flex gap-[5px]" onclick="toggleModal(true)">
                                    <i class="flex flex-col justify-center fa-solid fa-trash mt-1"></i>{{__('Review verwijderen')}}
                                </button>
                            </div>
                        </td>
                        </tbody>
                    </table>
                </div>
            </section>
        @endif
    @endauth
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
    <div class="d-flex justify-content-center text-center align-items-center flex-column">
        @isset($review)
            <div class="row flex-col md:flex-row text-center w-100 my-4 flex border p-4 bg-green-200 !border-blue-500">
                <div class="col-4 d-flex align-items-center flex-column h-100 order-2 md:order-1">
                    <h2 class="my-5 text-[35px] font-bold text-green-500">{{$review->movie}}</h2>
                    <img src="{{ asset($review->image) }}" alt="Gecentreerde Afbeelding" class="rounded-image mx-auto d-block">
                </div>
                <div class="col-8 md:border-l p-4 bg-green-200 !border-blue-500 order-1 md:order-2">
                    <h2 class="mt-5 text-[35px] font-bold text-blue-500">{{$review->title}}</h2>
                    <p class="my-4">{{$review->content}}</p>
                </div>
            </div>
        @endisset
        <div class="row w-100 flex justify-center lg:justify-end">
        <div class="flex w-full md:w-[50%] flex-col">
            <h3 class="text-[20px]">Comments</h3>
            @forelse ($review->comments as $comment)
            <div class="border bg-gray-200 flex gap-[10px] mt-3 !border-blue-500">
                <div class="border-r p-[10px] border-blue-500">
                    <img class="h-[50px] w-[50px]" src="https://i.pravatar.cc/100?id={{$comment->id}}" alt="">
                </div>
                <div class="p-[10px] w-full">
                    <h3 class="mb-2 border-b text-left">{{$comment->name}}</h3>
                    <div class="flex justify-between">
                        <p class="text-left comment-txt">{{$comment->reaction}}</p>
                    
                        @if(Auth::check() && Auth::id() === $comment->user_id)

                            <div id="edit-form-{{ $comment->id }}" class="hidden mt-3">
                                <form action="{{ route('comment.update', $comment->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <textarea class="form-control" name="comment" rows="1" maxlength="500">{{ $comment->reaction }}</textarea>
                                    @error('comment')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                    @enderror
                                    <button type="submit" class="btn btn-primary mt-2">{{ __('Update Comment') }}</button>
                                    <button type="button" class="btn btn-secondary mt-2" onclick="hideEditForm({{ $comment->id }})">{{ __('Annuleren') }}</button>
                                </form>
                            </div>
                            <div class="text-right w-[29%]">
                                <button class="btn btn-warning" onclick="showEditForm({{ $comment->id }})"><i class="flex flex-col justify-center fa-solid fa-pen-to-square"></i></button>
                                <button class="btn btn-danger" onclick="toggleDeleteModal(true, {{ $comment->id }})"><i class="flex flex-col justify-center fa-solid fa-trash"></i></button>
                            </div>

                            <div id="deleteModal-{{ $comment->id }}" class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center hidden">
                                <div class="bg-white rounded-lg p-6 w-96">
                                    <h2 class="text-xl mb-4">{{__('Weet je het zeker?')}}</h2>
                                    <p>{{__('Deze actie kan niet ongedaan worden gemaakt.')}}</p>
                                    <div class="flex justify-end mt-6">
                                        <button onclick="toggleDeleteModal(false, {{ $comment->id }})" class="mr-4 px-4 py-2 bg-gray-300 hover:bg-gray-500 rounded mb-[16px]">{{__('Annuleren')}}</button>
                                        <form action="{{ route('comment.destroy', $comment->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-800 text-white rounded">
                                                {{ __('Verwijder') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <p class="text-left text-gray-600">
                        {{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}
                    </p>
                </div>
            </div>
            @empty
                <div class="mx-[10px] my-3">
                    <p>{{ __('Nog geen comments, wees de eerste die commentaar geeft!') }}</p>
                </div>
            @endforelse
            <div class="mt-3">
                {{ $review->comments->links() }}
            </div>
            @guest
            <div class="mt-[10px] my-3">
                <a class="text-black hover:text-blue" href="{{ route('login') }}">{{ __('Log in om commentaar te plaatsen') }}</a>
            </div>
            @else
            <form class="mt-[10px]" method="POST" action="{{ route('comment.create') }}">
                @csrf
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>{{ __('doe mee met het gesprek!') }}:</strong>
                        <textarea class="form-control" name="comment" placeholder="vul hier je comment in" maxlength="500" rows="1">{{ old('comment') }}</textarea>
                        @error('comment')
                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <input type="hidden" name="review" value="{{ $review->id }}">
                <input type="hidden" name="creator" value="{{ Auth::user()->id }}">

                <button type="submit" class="btn btn-primary ml-3">Submit</button>
            </form>
            @endguest
        </div>
    </div>

    </div>

    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg p-6 w-96">
            <h2 class="text-xl mb-4">{{__('Weet je het zeker?')}}</h2>
            <p>{{__('Deze actie kan niet ongedaan worden gemaakt.')}}</p>
            <div class="flex justify-end mt-6">
                <button onclick="toggleModal(false)" class="mr-4 px-4 py-2 bg-gray-300 hover:bg-gray-500 rounded mb-[16px]">{{__('Annuleren')}}</button>
                <form action="{{ route('review.destroy', $review) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-800 text-white rounded">
                        {{ __('Verwijder') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

@endsection

<script>
    function toggleDeleteModal(show, commentId) {
        const modal = document.getElementById('deleteModal-' + commentId);
        if (show) {
            modal.classList.remove('hidden');
        } else {
            modal.classList.add('hidden');
        }
    }
    function toggleModal(show) {
        const modal = document.getElementById('deleteModal');
        if (show) {
            modal.classList.remove('hidden');
        } else {
            modal.classList.add('hidden');
        }
    }

    function showEditForm(commentId) {
        const editForm = document.getElementById('edit-form-' + commentId);
        const commentText = editForm.closest('.border').querySelector('.comment-txt');

        if (commentText) {
            commentText.classList.add('hidden');
        }
        
        editForm.classList.remove('hidden');
    }

    function hideEditForm(commentId) {
        const editForm = document.getElementById('edit-form-' + commentId);
        const commentText = editForm.closest('.border').querySelector('.comment-txt');

        if (commentText) {
            commentText.classList.remove('hidden');
        }
        
        editForm.classList.add('hidden');
    }
</script>

