<?php namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Support\MessageBag;
use App\Http\Requests;
use App\Models\Customer;
use App\Models\Image;
use App\Models\Server;
use App\Models\Message;
use Validator, Session, Redirect, MetaTag;
use Intervention\Image\ImageManagerStatic as Img;
use Socialite;


/**
 * AuthController provide all function for user: register, login, logout, reset password ...
 *
 * @author Carl Pham <vanca.vnn@gmail.com>
 */
class AuthController extends Controller {

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * The registrar implementation.
     *
     * @var Registrar
     */
    // protected $registrar;

    public function __construct(Guard $auth) {
        $this->auth = $auth;
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Show from register user.
     *
     * @return view
     */
    public function getRegister() {
        return view('client.auth.register');
    }

    /**
     * Process user register.
     *
     * @param  Request $request User infomations.
     * @return void    Redirect to info page.
     */
    public function postRegister(Request $request) {
        $rules     = [
        // 'captcha'    => 'required|captcha',
        'username'       => 'required|unique:customers',
        'email'      => 'required|unique:customers',
        'password'   => 'required|max:255|min:6',
        'password_confirmation' => 'required|same:password',
        ];
        $data      = $request->all();
        // $data['captcha'] = strtolower($data['captcha']);
        $validator = \Validator::make($data, $rules);
        if ($validator->fails())
        {
            return Redirect::route('register_get')->withInput()->withErrors($validator);
        }
        $user = Customer::create([
            'username' => str_replace('-', '_',str_slug($data['username'])),
            'email' => trim($data['email']),
            'password' => md5($data['password']),
            'type_id' => 1,
            ]);
        Session::set('user', $user);
        if (isset($data['current_url'])) {
            return Redirect::to($data['current_url']);
        }
        return \Redirect::route('home');
    }

    /**
     * Show form login user.
     *
     * @return view
     */
    public function getLogin() {
        if(Session::has('user')){
            $user = Session::get('user');
            if(Session::has('flash_url')){
                return Redirect::to(Session::get('flash_url'));
            }
            return Redirect::route('home');
        }
        $breadcrumb[] = ['link' => 'javascript:void(0)', 'title' => 'Login'];
        return view('client.auth.login');
    }

    /**
     * Process login user. Use ajax receive response.
     *
     * @param  Request $request User login infomartion.
     * @return array Array success.
     */
    public function postLogin(Request $request) {
        $data = $request->all();
        if(Session::has('current_url')){
            $data['current_url'] = Session::get('current_url');
        }
        $user = Customer::where(['email' => $data['email'], 'password' => md5($data['password'])])->with('avatar', 'type')->first();
        if (!is_null($user)) {
            Session::set('user', $user);
            if (isset($data['current_url'])) {
                if(Session::has('current_url')){
                    Session::forget('current_url');
                }
                return Redirect::to($data['current_url']);
            }
            return Redirect::route('home');
        }else{
            Session::flash('container_mes', '<p class="alert alert-danger text-center">Email or password not incorect!</p>');
            return \Redirect::route('login_get')->withInput();
        }
    }

    /**
     * User logout.
     *
     * @return array
     */
    public function getLogout() {
        \Session::forget('user');
        return \Redirect::back();
    }

    public function redirectToProvider($app, Request $request) {
        if(!Session::has('current_url')){
            Session::set('current_url', $request->server('HTTP_REFERER'));
        }
        if ($request->has('code')) {
            $this->handleProviderCallback($app);
            if(Session::has('current_url')){
                $url = Session::get('current_url');
                Session::forget('current_url');
            }else{
                $url = route('home');
            }
            return redirect($url);
        }
        $url = Session::get('current_url');
        Session::forget('current_url');
        return Socialite::with($app)->redirect($url);
    }

    public function handleProviderCallback($app) {
        $Customerocial = Socialite::with($app)->user();
        $user = Customer::where('email', '=', $Customerocial->email)->with(['avatar'])->first();
        if (is_null($user)) {
            // Create new user
            $userInfo = array(
                'username'         => str_replace('-', '_',str_slug($Customerocial->name.'-'.str_random(5))),
                'email'            => $Customerocial->email,
                'password'         => $Customerocial->email,
                'status'           => 1,
                'data' => json_encode([
                                    'social' => $app,
                                    'social_id' => $Customerocial->id,
                                    'gender' => isset($Customerocial->user['gender'])? $Customerocial->user['gender']: NULL,
                                    ]),
                'third_party'      => $app,
                'third_party_date' => serialize($Customerocial),
                'type_id' => 1
            );
            $user = Customer::create($userInfo);
        }
        Session::set('user', $user);
        if(Session::has('current_url')){
            $url = Session::get('current_url');
            Session::forget('current_url');
        }else{
            $url = route('home');
        }
        return redirect($url);
    }

    public function getUserInfo()
    {
        $user = Session::get('user');
        $breadcrumb[] = ['link' => 'javascript:void(0);', 'title' => 'My page'];
        return view('client.user.info', compact('user', 'breadcrumb'));
    }

    public function setUserInfo(Request $request)
    {
        $user = Customer::find(Session::get('user')->id);
        $old_data = json_decode($user->data, true);
        if(!is_array($old_data)){
            $old_data = [];
        }
        $data = $request->all();
        $validator = Validator::make($data, [
            'avatar' => 'image',
            'birth' => 'date_format:d/m/Y|before:today'
            ]);
        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        if (null != $request->file('avatar')) {
            $uploadDir = 'data/uploads/';
            $fileName  = $data['avatar']->getClientOriginalName();
            $extension = $data['avatar']->getClientOriginalExtension();
            $fileRename = strtolower($user->name).'-'.'avatar-'.time().'.'.$extension;
            if ($data['avatar']->getClientSize() > 5*1048576) { //1MB = 1048576
                $size = $data['avatar']->getClientSize();
                return Redirect::back()->withInput()->withFlashMessage('Kích thước ảnh '.round($size/1048576, 2).'M.<br>');
            }
            $data['avatar']->move($uploadDir, $fileName);
            $thumb = $uploadDir.$fileName;
            // Image crop
            $width = img::make($thumb)->width();
            $height = img::make($thumb)->height();
            $max_width = 120;
            $max_height = 120;
            $server = Server::where(['type' => 'ftp', 'default' => 1])->first();
            if (file_exists($thumb)){
                if ($width > $max_width or $height > $max_height) {
                    if ($width/$height < $max_width/$max_height) {
                        $image = img::make($thumb)->resize($max_width, NULL, function ($constraint) {
                                                                        $constraint->aspectRatio();
                                                                    })->save($thumb);
                        $image->crop($max_width, $max_height)->save($thumb);
                        $image_thumb = Server::uploadFtp($thumb, null, $server->id);
                    }else{
                        $image = img::make($thumb)->resize(NULL, $max_height, function ($constraint) {
                                                                        $constraint->aspectRatio();
                                                                    })->save($thumb);
                        $image->crop($max_width, $max_height)->save($thumb);
                        $image_thumb = Server::uploadFtp($thumb, null, $server->id);
                    }
                }else{
                    if ($width < $height) {
                        $image = img::make($thumb)->resize($max_width, NULL, function ($constraint) {
                                                                        $constraint->aspectRatio();
                                                                    })->save($thumb);
                        $image->crop($width, $width)->save($thumb);
                        $image_thumb = Server::uploadFtp($thumb, null, $server->id);
                    }else{
                        $image = img::make($thumb)->resize(NULL, $max_height, function ($constraint) {
                                                                        $constraint->aspectRatio();
                                                                    })->save($thumb);
                        $image->crop($height, $height)->save($thumb);
                        $image_thumb = Server::uploadFtp($thumb, null, $server->id);
                    }
                }
                @unlink($thumb);
            }
            if (false === $image_thumb) {
                return Redirect::back()->withInput()->withFlashMessage('Lỗi upload avatar, vui lòng thử lại.');
            }

            $image = Image::create([
                 'type'      => 'avatar',
                 'server_id' => $server->id,
                 'filename'  => $fileRename,
                 'link'      => $image_thumb
            ]);
            unset($data['avatar']);
            $user->avatar_id = $image->id;
        }
        unset($data['_token']);
        foreach ($data as $key => $value) {
            if($value == '' or is_null($value)){
                unset($data[$key]);
            }
        }
        $new_data = array_merge($old_data,$data);
        $user->data = json_encode($new_data);
        $user->save();
        Session::set('user', $user);
        Session::flash('message', '<p class="alert alert-success">Thay đổi thông tin thành công</p>');
        return Redirect::route('user_info');
    }

    public function changePassword(Request $request)
    {
        $user = Customer::find(Session::get('user')->id);
        $data = $request->all();
        if(md5($data['oldpassword']) != $user->password){
            Session::flash('oldpassword_not_match', 'Sai mật khẩu');
        }
        $validator = Validator::make($data, [
            'password' => 'required|min:6',
            'repassword' => 'required|same:password'
            ], [
            'required' => 'Không để trống.',
            'min' => 'Phải từ 6 ký tự trở lên',
            'same' => 'Nhập lại không chính xác.'
            ]);
        if ($validator->fails() or md5($data['oldpassword']) != $user->password) {
            Session::flash('password_tab', true);
            return Redirect::back()->withInput()->withErrors($validator);
        }
        $user->password = md5($data['password']);
        $user->save();
        Session::set('user', $user);
        Session::flash('message', '<p class="alert alert-success">Thay đổi mật khẩu thành công</p>');
        return Redirect::route('user_info');
    }

    function home(){
        $breadcrumb[] = ['link' => 'javascript:void(0)', 'title' => 'Trang cá nhân'];
        MetaTag::set('title', 'Trang cá nhân');
        $result = Customer::where('id', Session::get('user')->id)->with('type')->first();
        return view('client.auth.home', compact('breadcrumb', 'result'));
    }

    function notifi(Request $request){
        if (isset($_GET['id'])) {
            $result = Message::where('Customer_id', Session::get('user')->id)->where('id', $_GET['id'])->first();
            $result->status = 1;
            $result->save();
            MetaTag::set('title', date('d/m/Y H:i:s'));
            $breadcrumb[] = ['link' => route('user_notifi'), 'title' => 'Thông báo'];
            $breadcrumb[] = ['link' => 'javascript:void(0)', 'title' => date('d/m/Y H:i:s')];
        }else{
            $result = Message::where('Customer_id', Session::get('user')->id)->orderBy('created_at', 'desc');
            if (isset($_GET['active'])) {
                if ($_GET['active'] == 1) {
                    $result->where('status', 1);
                }else{
                    $result->whereNull('status');
                }
            }
            $result = $result->paginate(20);
            $uri = $request->all();
            $result->appends($uri);
            MetaTag::set('title', 'Thông báo');
            $breadcrumb[] = ['link' => 'javascript:void(0)', 'title' => 'Thông báo'];
        }
        return view('client.auth.message', compact('breadcrumb', 'result'));
    }

    function edit(){
        $breadcrumb[] = ['link' => 'javascript:void(0)', 'title' => 'Sửa thông tin cá nhân'];
        MetaTag::set('title', 'Sửa thông tin cá nhân');
        $result = Customer::where('id', Session::get('user')->id)->first();
        return view('client.auth.edit', compact('breadcrumb', 'result'));
    }

    function update(Request $request){
        $user = Customer::find(Session::get('user')->id);
        $data = $request->all();
        if (strlen($data['password']) > 0) {
            $validator = Validator::make($data, [
                'password' => 'min:6',
                'password_confirmation' => 'required|same:password'
                ], [
                'required' => 'Không để trống.',
                'min' => 'Phải từ 6 ký tự trở lên',
                'same' => 'Nhập lại không chính xác.'
                ]);
            if ($validator->fails()) {
                return Redirect::back()->withInput()->withErrors($validator);
            }
            $user->password = md5($data['password']);
        }
        $user->full_name = $data['full_name'];
        $user->phone     = $data['phone'];
        $user->data      = json_encode($data['data']);
        $user->save();
        Session::set('user', $user);
        Session::flash('message', '<p class="alert alert-success">Thay đổi thành công</p>');
        return Redirect::route('user');
    }

    function bookmarks(){
        $user = Customer::where('id', Session::get('user')->id)->with(['bookmark_posts'])->first();
        $result = $user->bookmark_posts;
        $title = 'Tin đã lưu';
        return view('client.user.posts', compact('result', 'title'));
    }

    function posts(){
        $result = Posts::where('user_id', Session::get('user')->id)->with(['type'])->orderBy('id', 'desc')->paginate(5);
        $title = 'Tin đã đăng';
        $breadcrumb[] = ['link' => 'javascript:void(0);', 'title' => 'Tin đã đăng'];
        return view('client.user.posts', compact('result', 'title', 'breadcrumb'));
    }

    function post_edit($slug){
        $result = Posts::where('slug', $slug)->with(['ward', 'district', 'province', 'type', 'images', 'user', 'street', 'cover'])->first();
    }

    function unbookmark($slug){
        $posts_id = Posts::where('slug', $slug)->first()->id;
        $Customer_id = Session::get('user')->id;
        $bookmark = Customer_posts::where(['posts_id' => $posts_id, 'Customer_id' => $Customer_id])->orderBy('id', 'desc')->first();
        $bookmark->delete();
        Session::flash('message', '<p class="alert alert-success">Đã Hủy lưu tin.</p>');
        return Redirect::route('user_bookmarks');
    }

    function feedback($id, Request $request){
        $data = $request->all();
        $post = Posts::find($id);
        unset($data['_token']);
        $data['title_ascii'] = Str::ascii($data['title']);
        $data['slug'] = $post->slug;
        $data['item_id'] = $id;
        $data['user_id'] = Session::get('user')->id;
        $feedback = Feedback::create($data);
        Session::flash('message', '<div class="alert alert-success alert-room-success"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Đã phản hồi.</div>');
        return Redirect::back();
    }
}
