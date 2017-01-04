@extends('layouts.dashboard')
@section('content')
<h2>Update Post</h2>
  <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
  <script type="text/javascript">
tinymce.init({
    selector: "textarea",
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
});
</script>

  <form action="{{ url('/admin/posts/updatepost')}} " method="post" enctype="multipart/form-data">
  	<input type="hidden" name="_token" value="{{ csrf_token() }}">
  	<input type="hidden" name="post_id" value="{{ $post->id }}{{ old('post_id') }}">
  	<input type="hidden" name="post_descriptions" value="{{ $post->id }}{{ old('post_id') }}">
  	<div class="form-group">
  		<p>Title</p>
  		<input type="text" name="title" value="@if(!old('title')){{$post->title}}@endif{{ old('title') }}" required="required" placeholder="Enter title here" class="form-control">
  		<br>
  		<p>Description</p>
  		<input type="text" name="description" value="@if(!old('description')){{$post->description}}@endif{{ old('description') }}" required="required" placeholder="Enter description here" class="form-control">
  		<br>
  		<!-- thumbnail upload -->
  		<p>Thumbnail</p>
  		<img src="{{ url('img/'.$post->images) }}" id="showimages" style="max-width:200px;max-height:200px;float:left;"/>
  		<div class="row">
  			<div class="col-md-12">
  				<input type="file" name="images" id="inputimages">
  			</div>
  		</div>
  	</div>

  	<div class="form-group">
  		<textarea name="body" class="form-control" rows="20">
  			@if(!old('body'))
  				{!! $post->body !!}
  			@endif
  				{!! old('body') !!}
  		</textarea>
  	</div>
  	@if($post->active == '1')
  	<input type="submit" name="publish" class="btn btn-success" value="Update"/>
  	@else
  	<input type="submit" name="publish" class="btn btn-success" value="Publish"/>
  	@endif
  	<input type="submit" name="save" class="btn btn-default" value="Save As Draft"/>

  	<a href="{{ url('admin/posts/deletepost/'.$post->id.'?_token='.csrf_token()) }}" onclick="return confirm('Are you sure to delete this post?');" class="btn btn-danger">Delete</a>

  </form>


@endsection