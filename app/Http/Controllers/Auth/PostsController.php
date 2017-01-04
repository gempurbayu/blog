<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Posts;
use App\User;
use Redirect;

use Illuminate\Support\Facades\Auth;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //stored all posts and search function
        $id = Auth::id();
        $search = $request->get('search');
        $posts = Posts::where('title','like','%'.$search.'%')->where('active',1)->where('author_id',$id)->orderBy('created_at')->paginate(3);
        return view('admin/posts/allposts')->withPosts($posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //if user can post i.e user is admin or author
        if ($request->user()->can_post()) {
            return view('admin/posts/create');
        } else {
            return redirect('/')->withErrors('You have not sufficient permissions for writting post');
        }
    }

    //save posts data info into db
    public function store(Request $request)
    {
        $post = new Posts();
        $post->title = $request->get('title');
        $post->description = $request->get('description');
        $post->body = $request->get('body');
        $post->slug = str_slug($post->title);
        $post->author_id = $request->user()->id;

        //thumbnail upload
        if ($request->file('images')) {
            $fileName = str_random(30);
            $request->file('images')->move("img/",$fileName);
        } else {
            $fileName = $post->images;
        }
        $post->images = $fileName;

        if ($request->has('save')){
            //for draft
            $post->active = 0;
            $message = 'Post saved succesfully';
        } else {
            // for posts
            $post->active = 1;
            $message = 'post published succesfully';
        }
        $post->save();
        return redirect('admin/posts/editpost/'.$post->slug)->withMessage($message);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $slug)
    {
        $post = Posts::where('slug',$slug)->first();
        if($post && ($request->user()->id == $post->author_id || $request->user()->is_admin()))
        return view('admin/posts/edit')->with('post',$post);
        return redirect('/')->withErrors('you have not sufficient permission');
    }
    //update  
    public function update(Request $request)
    {
        $post_id = $request->input('post_id');
        $post = Posts::find($post_id);
        if ($post && ($post->author_id == $request->user()->id || $request->user()->is_admin())) {
            $title = $request->input('title');
            $slug = str_slug($title);
            $duplicate = Posts::where('slug',$slug)->first();
            if($duplicate){
                if($duplicate->id != $post_id){
                    return redirect('admin/posts/editpost/'.$post->slug)->withErrors('Title already exist.')->withInput();
                } else {
                    $post->slug = $slug;
                }
            }
            $post->title = $title;
            //thumbnail upload
            if ($request->file('images')) {
                $fileName = str_random(30);
                $request->file('images')->move("img/",$fileName);
            } else {
                $fileName = $post->images;
            }
            $post->images = $fileName;
            $post->body = $request->input('body');

            if($request->has('save')){
                $post->active = 0;
                $message = 'Post saved succesfully';
                $goto = 'admin/posts/editposts'.$post->slug;
            } else {
                $post->active = 1;
                $message = 'Post updated succesfully';
                $goto = 'admin/posts/allposts';
            }
            $post->save();
            return redirect($goto)->withMessage($message);
        } else {
            return redirect('/')->withErrors('you have not sufficient permissions');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $post = Posts::find($id);
        if($post && ($post->author_id == $request->user()->id || $request->user()->is_admin())){
            $post->delete();
            $data['message'] = 'Post deleted succesfully';
        } else {
            $data['errors'] = 'Invalid Operation. You have not sufficient permissions';
        }
        return redirect('admin/posts/allposts')->with($data);
    }
}
