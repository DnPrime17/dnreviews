@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row">
        <div class="col-lg-12 margin-tb flex justify-between">
            <div class="pull-left">
                <h2 class="text-[20px] mb-2 font-bold">{{__('Review aanpassen')}}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('review.single', $review->id) }}" enctype="multipart/form-data">{{__('Terug')}}</a>
            </div>
        </div>
    </div>

    <form action="{{ route('review.update', $review->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{__('Review titel')}}:</strong>
                    <input type="text" name="title" class="form-control" placeholder="Review titel" value="{{ $review->title }}">
                    @error('title')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{__('Film')}}:</strong>
                    <input type="text" name="movie" class="form-control" placeholder="Film" value="{{ $review->movie }}">
                    @error('movie')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{__('Review text')}}:</strong>
                    <textarea class="form-control" name="content" placeholder="Content" maxlength="1000" rows="10">{{ $review->content }}</textarea>
                    @error('content')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                    <strong>{{__('Nieuwe film afbeelding')}}:</strong>
                    <input type="file"  accept=".jpg,.jpeg,.png,.pdf" class="form-control" name="image">
                    @error('image')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <input type="hidden" name="creator" value="{{ Auth::user()->id }}">
            <input type="hidden" name="id" value="{{ $review->id }}">

            <button type="submit" class="btn btn-primary ml-3">{{__('Updaten')}}</button>

        </div>
    </form>
</div>
@endsection