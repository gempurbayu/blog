@extends('layouts.app')


@section('header')
	<div class="post-heading">
		<h1>{{ $post->title }}</h1>
		<h2 class="subheading">{{ $post->description }}</h2>
		<span class="meta">
			{{ $post->created_at->format('M d,Y \a\t h:i a') }} By
			<a href="{{ url('/user/'.$post->author_id)}}">{{ $post->author->name }}</a>

		</span>
	</div>

@endsection

@section('content')
	@if ($post)
		<div class="">
			{!! $post->body !!} 
		</div>
	@if (Auth::guest())
	<p>
		Please Login to Comment
	</p>
	@else
	<div class="">
		<h3>Leave a comment</h3>
	</div>
	<div class="panel-body">
		<form class="" action="/comment/add" method="post">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="on_post" value="{{ $post->id }}">
			<input type="hidden" name="slug" value="{{ $post->slug }}">
			<div class="form-group">
				<textarea required="required" placeholder="Enter comment here" name="body" class="form-control"></textarea>
			</div>
			<input type="submit" name="post_comment" class="btn btn-success" value="Post">
		</form>
	</div>
	@endif
	<div class="">
		@if ($comments)
		<ul style="list-style: none; padding: 0">
			@foreach($comments as $comment)
			<li class="panel-body">
				<div class="list-group">
					<div class="list-group-item">
						<h3>{{ $comment->author->name }}</h3>
						<p>{{ $comment->created_at->format('M d,Y \a\t h:i a')}}</p>
					</div>
					<div class="list-group-item">
						<p>{{ $comment->body }}</p>
					</div>
				</div>
			</li>
			@endforeach
		</ul>
		@endif
	</div>
	@else

	@endif
@endsection