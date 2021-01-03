<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthorCommentsResource;
use App\Http\Resources\AuthorPostsResource;
use App\Http\Resources\TokenResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\UsersResource;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return UsersResource
     */
    public function index()
    {
        $users=User::paginate();
        return new UsersResource($users);
    }

    /**
     * @param Request $request
     * @return UserResource
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'email' => 'required',
            'password'  => 'required'
        ]);
        $user = new User();
        $user->name = $request->get( 'name' );
        $user->email = $request->get( 'email' );
        $user->password = Hash::make( $request->get( 'password' ) );
        $user->save();
        return new UserResource( $user );
    }

    /**
     * @param $id
     * @return UserResource
     */
    public function show($id)
    {
        $user=User::with(['posts','comments'])->where('id',$id)->get();
        return new UserResource($user);
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {   $user=User::find($id);
        if($request->has('name')){
            $user->name=$request->get('name');
        }
        if( $request->hasFile('avatar') ){
            $featuredImage = $request->file( 'avatar' );
            $filename = time().$featuredImage->getClientOriginalName();
            Storage::disk('images')->putFileAs(
                $filename,
                $featuredImage,
                $filename
            );
            $user->avatar = url('/') . '/images/' .$filename;
        }
        $user->save();

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param $id
     * @return AuthorPostsResource
     */
    public function posts($id){
               $posts=User::find($id)->posts;
                return new AuthorPostsResource($posts);

    }

    /**
     * @param $id
     * @return AuthorCommentsResource
     */
    public function comments( $id ){
        $user = User::find( $id );
        $comments = $user->comments;
        return new AuthorCommentsResource( $comments );
    }
    public function getToken(Request $request){
        $request->validate( [
            'email' => 'required',
            'password'  => 'required'
        ] );
        $credentials = $request->only( 'email' , 'password' );
        if( Auth::attempt( $credentials ) ){
            $user = User::where( 'email' , $request->get( 'email' ) )->first();
            return new TokenResource( [ 'token' => $user->api_token] );
        }
        return 'not found';
    }
}

