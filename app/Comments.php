<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    //comments table in database
    protected $guarded = [];
    //user who has commented
    public function author(){
    	return $this->belongsTo('App\User','from_user');
    }
    //returns the instance of the user who is author of that post
    public function post(){
    	return $this->belongsTo('App\Posts','on_post');
    }
}
