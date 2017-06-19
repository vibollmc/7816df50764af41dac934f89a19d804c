<?php
Route::group(['prefix' => env('ADMIN_URL'), 'namespace' => 'Admin', 'middleware' => 'web'], function(){
    //Auth
    Route::get('/login',      ['as' => 'admin_login','uses' => 'AuthController@getLogin']);
    Route::post('/postlogin', ['as' => 'admin_postlogin','uses' => 'AuthController@postLogin']);
    Route::get('/logout',     ['as' => 'admin_logout','uses' => 'AuthController@getLogout']);

    Route::post('/image/upload',                        ['as' => 'upload_image','uses' => 'HomeController@upload_image']);
    Route::group(['middleware' => 'admin'], function(){
        // Route::get('/sync',                             ['as' => 'admin_sync','uses' => 'FilmController@sync']);
        Route::get('/',                                     ['as' => 'admin_home','uses' => 'HomeController@index']);
        Route::get('/cache-clear',                          ['as' => 'cache_clear','uses' => 'HomeController@clear_cache']);
        Route::get('/film/drive',                           ['as' => 'backend_drive','uses' => 'ToolController@drive']);
        Route::post('/film/drive',                          ['as' => 'update_drive','uses' => 'ToolController@update_drive']);
        Route::group(['middleware' => 'admin_access'], function(){
            // Payment
            Route::get('/payment',                          ['as' => 'admin_payment','uses' => 'PaymentController@index']);
            Route::get('/edit-payment/{id}',                ['as' => 'edit_payment','uses' => 'PaymentController@edit']);
            Route::post('/edit-payment/{id}',               ['as' => 'update_payment','uses' => 'PaymentController@update_payment']);
            // Tags
            Route::get('/tag',                              ['as' => 'admin_tag','uses' => 'TagController@index']);
            Route::get('/tag/edit/{id}',                    ['as' => 'edit_tag','uses' => 'TagController@edit']);
            Route::post('/tag/update/{id}',                 ['as' => 'update_tag','uses' => 'TagController@update']);
            Route::get('/tag/delete/{id}',                  ['as' => 'delete_tag','uses' => 'TagController@delete']);
            Route::post('/tag/status',                      ['as' => 'update_tag_status','uses' => 'TagController@status']);
            // Article
            Route::get('/article',                          ['as' => 'admin_article','uses' => 'ArticleController@index']);
            Route::get('/article/create',                   ['as' => 'create_article','uses' => 'ArticleController@create']);
            Route::post('/article/store',                   ['as' => 'store_article','uses' => 'ArticleController@store']);
            Route::get('article/edit/{id}',                 ['as' => 'edit_article', 'uses' => 'ArticleController@edit']);
            Route::post('article/update/{id}',              ['as' => 'update_article', 'uses' => 'ArticleController@update']);
            Route::post('/article/change',                  ['as' => 'article_change_status', 'uses' => 'ArticleController@change_status']);
            Route::post('/article/upload',                  ['as' => 'article_upload', 'uses' => 'ArticleController@upload']);
            Route::get('/article/delete/{id}',              ['as' => 'delete_article', 'uses' => 'ArticleController@delete']);
            Route::post('/delete/article_image',            ['as' => 'delete_article_img', 'uses' => 'ArticleController@delete_image']);
            // Settings
            Route::group(['prefix' => 'setting'], function(){
                Route::get('/',                                ['as' => 'admin_setting','uses' => 'SettingController@index']);
                Route::get('/menu',                            ['as' => 'menu_setting','uses' => 'SettingController@menu']);
                Route::post('/menu',                           ['as' => 'update_menu','uses' => 'SettingController@update_menu']);
                Route::get('/block',                           ['as' => 'block_setting','uses' => 'SettingController@block']);
                Route::post('/block',                          ['as' => 'update_block','uses' => 'SettingController@update_block']);
                Route::get('/block/create',                    ['as' => 'create_block','uses' => 'SettingController@create_block']);
                Route::post('/block/create',                   ['as' => 'store_block','uses' => 'SettingController@store_block']);
                Route::get('/block/ads',                       ['as' => 'ads_block','uses' => 'SettingController@ads_block']);
                Route::get('/block/edit/{id}',                 ['as' => 'edit_ads','uses' => 'SettingController@edit_block']);
                Route::post('/block/edit/{id}',                ['as' => 'update_ads','uses' => 'SettingController@update_ads']);
                Route::get('/slide',                           ['as' => 'edit_slide','uses' => 'SettingController@slide']);
                Route::post('/slide',                          ['as' => 'update_slide','uses' => 'SettingController@update_slide']);
                Route::post('/upload',                         ['as' => 'setting_upload', 'uses' => 'SettingController@upload']);
                Route::get('/seo',                             ['as' => 'edit_seo','uses' => 'SettingController@seo']);
                Route::post('/seo',                            ['as' => 'update_seo','uses' => 'SettingController@seo_update']);
                Route::get('/interface',                       ['as' => 'edit_interface','uses' => 'SettingController@face']);
                Route::post('/interface',                      ['as' => 'update_interface','uses' => 'SettingController@update_face']);
                Route::get('/constant',                        ['as' => 'edit_constant','uses' => 'SettingController@constant']);
                Route::post('/constant',                       ['as' => 'update_constant','uses' => 'SettingController@update_constant']);
                Route::get('/customer',                        ['as' => 'customer_access','uses' => 'SettingController@access']);
                Route::post('/customer',                       ['as' => 'update_access','uses' => 'SettingController@update_access']);
                Route::get('/social',                          ['as' => 'social_setting','uses' => 'SettingController@social']);
                Route::post('/social',                         ['as' => 'update_social','uses' => 'SettingController@update_social']);
                Route::get('/footer',                          ['as' => 'footer_setting','uses' => 'SettingController@footer']);
                Route::post('/footer',                         ['as' => 'update_footer','uses' => 'SettingController@update_footer']);
                Route::get('/server',                          ['as' => 'server_setting','uses' => 'SettingController@server']);
                Route::post('/server',                         ['as' => 'update_server','uses' => 'SettingController@update_server']);
                Route::get('/price',                           ['as' => 'price_setting','uses' => 'SettingController@price']);
                Route::post('/price',                          ['as' => 'update_price','uses' => 'SettingController@update_price']);
                Route::get('/analytic',                        ['as' => 'backend_analytic','uses' => 'SettingController@analytic']);
            Route::post('/analytic',                           ['as' => 'update_analytic','uses' => 'SettingController@update_analytic']);
            });
            // Users
            Route::group(['prefix' => 'user'], function(){
                Route::get('',                             ['as' => 'admin_user','uses' => 'UserController@index']);
                Route::get('/create',                      ['as' => 'create_user','uses' => 'UserController@create']);
                Route::post('/store',                      ['as' => 'store_user','uses' => 'UserController@store']);
                Route::get('/edit/{id}',                   ['as' => 'edit_user','uses' => 'UserController@edit']);
                Route::post('/update/{id}',                ['as' => 'update_user','uses' => 'UserController@update']);
                Route::get('/show/{id}',                   ['as' => 'show_user','uses' => 'UserController@show']);
                Route::get('/delete/{id}',                 ['as' => 'delete_user','uses' => 'UserController@destroy']);
                Route::get('/type/{id}',                   ['as' => 'user_type','uses' => 'UserController@type']);
                // Customer
                Route::get('/customer',                    ['as' => 'admin_customer','uses' => 'CustomerController@index']);
                Route::get('/customer/create',             ['as' => 'create_customer','uses' => 'CustomerController@create']);
                Route::post('/customer/store',             ['as' => 'store_customer','uses' => 'CustomerController@store']);
                Route::get('/customer/edit/{id}',          ['as' => 'edit_customer','uses' => 'CustomerController@edit']);
                Route::post('/customer/update/{id}',       ['as' => 'update_customer','uses' => 'CustomerController@update']);
                Route::get('/customer/show/{id}',          ['as' => 'show_customer','uses' => 'CustomerController@show']);
                Route::get('/customer/delete/{id}',        ['as' => 'delete_customer','uses' => 'CustomerController@destroy']);
                Route::get('/customer/type/{id}',          ['as' => 'user_customer','uses' => 'CustomerController@type']);
            });
            // Server
            Route::get('/server',                           ['as' => 'admin_server','uses' => 'ServerController@index']);
            Route::get('/server/create',                    ['as' => 'create_server','uses' => 'ServerController@create']);
            Route::post('/server/store',                    ['as' => 'store_server','uses' => 'ServerController@store']);
            Route::get('/server/edit/{id}',                 ['as' => 'edit_server','uses' => 'ServerController@edit']);
            Route::post('/server/edit/{id}',                ['as' => 'post_edit_server','uses' => 'ServerController@update']);
            Route::get('/server/delete/{id}',               ['as' => 'delete_server','uses' => 'ServerController@delete']);

            // Films
            Route::group(['prefix' => 'film'], function(){
                Route::get('/old/sync/{id}/{old_id}',       ['as' => 'old_film_sync','uses' => 'SyncController@sync']);
                Route::get('/auto-add',                     ['as' => 'auto_add','uses' => 'FilmController@auto_add']);

                // Genres
                Route::get('/genres',                       ['as' => 'admin_film_genres','uses' => 'FilmController@genres']);
                Route::get('/create_genres',                ['as' => 'create_genres','uses' => 'FilmController@create_genres']);
                Route::post('/create_genres/post',          ['as' => 'store_genres','uses' => 'FilmController@post_create_genres']);
                Route::get('/edit_genres/{id}',             ['as' => 'edit_genres','uses' => 'FilmController@edit_genres']);
                Route::post('/update_genres/{id}',          ['as' => 'update_genres','uses' => 'FilmController@update_genres']);
                Route::get('/change_genres/{id}',           ['as' => 'change_genres','uses' => 'FilmController@change_genres']);
                Route::get('/delete_genres/{id}',           ['as' => 'delete_genres','uses' => 'FilmController@delete_genres']);
                // Category
                Route::get('/category',                     ['as' => 'admin_category','uses' => 'CategoryController@index']);
                Route::get('/category/create',              ['as' => 'create_category','uses' => 'CategoryController@create']);
                Route::post('/category/store',              ['as' => 'store_category','uses' => 'CategoryController@store']);
                Route::get('/category/edit/{id}',           ['as' => 'edit_category','uses' => 'CategoryController@edit']);
                Route::post('/category/update/{id}',        ['as' => 'update_category','uses' => 'CategoryController@update']);
                Route::get('/category/delete/{id}',         ['as' => 'delete_category','uses' => 'CategoryController@delete']);
                // Quality
                Route::get('/quality',                      ['as' => 'admin_quality','uses' => 'QualityController@index']);
                Route::get('/quality/create',               ['as' => 'create_quality','uses' => 'QualityController@create']);
                Route::post('/quality/store',               ['as' => 'store_quality','uses' => 'QualityController@store']);
                Route::get('/quality/edit/{id}',            ['as' => 'edit_quality','uses' => 'QualityController@edit']);
                Route::post('/quality/update/{id}',         ['as' => 'update_quality','uses' => 'QualityController@update']);
                Route::get('/quality/delete/{id}',          ['as' => 'delete_quality','uses' => 'QualityController@delete']);
                // Country
                Route::get('/country',                      ['as' => 'admin_country','uses' => 'CountryController@index']);
                Route::get('/country/create',               ['as' => 'create_country','uses' => 'CountryController@create']);
                Route::post('/country/store',               ['as' => 'store_country','uses' => 'CountryController@store']);
                Route::get('/country/edit/{id}',            ['as' => 'edit_country','uses' => 'CountryController@edit']);
                Route::post('/country/edit/{id}',           ['as' => 'update_country','uses' => 'CountryController@update']);
                Route::get('/country/change/{id}',          ['as' => 'change_country','uses' => 'CountryController@change_menu']);
                Route::get('/country/delete/{id}',          ['as' => 'delete_country','uses' => 'CountryController@delete']);
            });
            // Actor
            Route::get('/actor',                            ['as' => 'admin_actor','uses' => 'ActorController@index']);
            Route::get('/actor/edit/{id}',                  ['as' => 'edit_actor','uses' => 'ActorController@edit']);
            Route::post('/actor/update/{id}',               ['as' => 'update_actor','uses' => 'ActorController@update']);
            Route::get('/actor/delete/{id}',                ['as' => 'delete_actor','uses' => 'ActorController@delete']);
            Route::post('/actor/status',                    ['as' => 'update_actor_status','uses' => 'ActorController@status']);
            // Block seo
            Route::get('/seo/orther',                       ['as' => 'admin_seo_block', 'uses' => 'SeoController@index']);
            Route::get('/seo/create',                       ['as' => 'create_orther_seo', 'uses' => 'SeoController@create']);
            Route::post('/seo/create',                      ['as' => 'store_orther_seo', 'uses' => 'SeoController@store']);
            Route::get('/seo/edit/{id}',                    ['as' => 'edit_orther_seo', 'uses' => 'SeoController@edit']);
            Route::post('/seo/edit/{id}',                   ['as' => 'update_orther_seo', 'uses' => 'SeoController@update']);
            Route::get('/seo/delete/{id}',                  ['as' => 'delete_orther_seo', 'uses' => 'SeoController@delete']);
            // Error
            Route::get('/feedback/error',                   ['as' => 'admin_error', 'uses' => 'FeedbackController@error']);
            Route::get('/feedback/error/del/{id}',          ['as' => 'delete_error', 'uses' => 'FeedbackController@delete_error']);
            Route::post('/feedback/error',                  ['as' => 'update_error', 'uses' => 'FeedbackController@update_error']);
        });
        /*End access*/
        // Film
        Route::group(['prefix' => 'film'], function(){

            Route::get('/',                             ['as' => 'admin_film','uses' => 'FilmController@index']);
            Route::get('/member',                       ['as' => 'admin_member_film','uses' => 'FilmController@member']);
            Route::get('/create',                       ['as' => 'create_film','uses' => 'FilmController@create']);
            Route::get('/edit/{id}',                    ['as' => 'edit_film','uses' => 'FilmController@edit']);
            Route::get('/film-ep/{id}',                 ['as' => 'episode','uses' => 'FilmController@film_ep']);
            Route::get('/edit-film-ep/{id}',            ['as' => 'edit_film_ep','uses' => 'FilmController@edit_ep']);
            Route::post('/edit-film-ep/{id}',           ['as' => 'update_film_ep','uses' => 'FilmController@update_ep']);
            Route::get('/add-film-ep/{id}',             ['as' => 'add_film_ep','uses' => 'FilmController@add_ep']);
            Route::post('/store',                       ['as' => 'store_film','uses' => 'FilmController@store']);
            Route::post('/add-film-ep/{id}',            ['as' => 'store_ep','uses' => 'FilmController@store_ep']);
            Route::post('/update/{id}',                 ['as' => 'update_film','uses' => 'FilmController@update']);
            Route::get('/delete/{id}',                  ['as' => 'delete_film','uses' => 'FilmController@delete']);
            Route::get('/delete_video',                 ['as' => 'delete_video','uses' => 'FilmController@delete_video']);
            Route::get('/delete_ep/{id}',               ['as' => 'delete_ep','uses' => 'FilmController@delete_ep']);
            // Old data
            Route::get('/old',                          ['as' => 'admin_old_film','uses' => 'SyncController@index']);
            Route::get('/old/change/{id}',              ['as' => 'admin_old_change','uses' => 'SyncController@change']);
            Route::get('/old/episode/{id}',             ['as' => 'old_film_ep','uses' => 'SyncController@episode']);
            // Route::get('/old/sync',                     ['as' => 'old_film_sync','uses' => 'SyncController@sync']);
            Route::post('/multi-delete',                ['as' => 'multi_delete_film','uses' => 'FilmController@multi_delete']);
            Route::post('/multi-delete-ep/{id}',        ['as' => 'multi_delete_ep','uses' => 'FilmController@multi_delete_ep']);
            Route::post('/get-info-film',               ['as' => 'get_info_auto','uses' => 'FilmController@get_info_auto']);
            Route::get('/get-info-film',                ['as' => 'get_creat_film','uses' => 'FilmController@create']);

        });
        // Calendar
        Route::get('/calendar',                         ['as' => 'admin_calendar','uses' => 'SettingController@calendar']);
        Route::post('/calendar',                        ['as' => 'update_calendar','uses' => 'SettingController@update_calendar']);
        // Feedback
        Route::get('/feedback/reporter',                ['as' => 'admin_reporter', 'uses' => 'FeedbackController@reporter']);
        Route::get('/feedback/fixing',                  ['as' => 'admin_fixing', 'uses' => 'FeedbackController@pending']);
        Route::get('/feedback/change-report',           ['as' => 'change_report', 'uses' => 'FeedbackController@change_report']);
        Route::get('/feedback/show/{id}',               ['as' => 'show_report', 'uses' => 'FeedbackController@show_report']);
        Route::get('/feedback/spam/{able}/{error}',     ['as' => 'report_spam', 'uses' => 'FeedbackController@report_spam']);
        // Tool check
        Route::get('/tool/die',                         ['as' => 'die_episode', 'uses' => 'ToolController@die_episode']);
        Route::get('/tool/null-episode',                ['as' => 'null_episode', 'uses' => 'ToolController@null_episode']);
        Route::get('/tool/google-drive/edit/{id}',      ['as' => 'edit_google_folder', 'uses' => 'ToolController@edit_google_folder']);
        Route::post('/tool/google-drive/edit/{id}',     ['as' => 'post_edit_folder', 'uses' => 'ToolController@post_edit_folder']);
        Route::get('/tool/google-drive/del-folder/{id}',['as' => 'delete_google_folder', 'uses' => 'ToolController@delete_google_folder']);
        Route::get('/tool/google-drive/update',         ['as' => 'update_google_all', 'uses' => 'ToolController@update_google_all']);
        Route::get('/tool/google-drive/update/{id}',    ['as' => 'update_google_folder', 'uses' => 'ToolController@update_google_folder']);
        Route::get('/tool/google-file',                 ['as' => 'google_drive_file', 'uses' => 'ToolController@google_file']);
        Route::get('/tool/google-drive/del-file/{id}',  ['as' => 'delete_google_file', 'uses' => 'ToolController@delete_google_file']);
        Route::post('/tool/google-drive/add-folder',    ['as' => 'add_google_drive_folder', 'uses' => 'ToolController@add_folder']);
    });

});
Route::group(['middleware' => 'web'], function(){
    Route::auth();

    // Route::get('/home', 'HomeController@index');
});
Route::group(['prefix' => '', 'namespace' => 'Client', 'middleware' => ['agent', 'web']], function(){
    Route::get('/get_captcha/{config?}', function (\Mews\Captcha\Captcha $captcha, $config = 'default') {
        return $captcha->src($config);
    });
    // Sitemap
    Route::get('/sitemap.xml',                              ['as' => 'sitemap', 'uses' => 'SitemapController@index']);
    Route::get('/tin-tuc.xml',                              ['as' => 'sitemap_article', 'uses' => 'SitemapController@article']);
    Route::get('list/{slug}.xml',                       ['as' => 'sitemap_category', 'uses' => 'SitemapController@category']);
    Route::get('category/{slug}.xml',                       ['as' => 'sitemap_genre', 'uses' => 'SitemapController@genre']);
    Route::get('country/{slug}.xml',                       ['as' => 'sitemap_country', 'uses' => 'SitemapController@country']);
    Route::get('year/{slug}.xml',                  ['as' => 'sitemap_year', 'uses' => 'SitemapController@year']);
    Route::get('pornstar.xml',                             ['as' => 'sitemap_stars','uses' => 'SitemapController@stars']);
    Route::get('keyword/{slug}.xml',                        ['as' => 'sitemap_tag','uses' => 'SitemapController@tag']);
    // Auth
    Route::get('login',                                     ['as' => 'login_get', 'uses' => 'AuthController@getLogin']);
    Route::post('login',                                    ['as' => 'login_post', 'uses' => 'AuthController@postLogin']);
    Route::get('register',                                  ['as' => 'register_get', 'uses' => 'AuthController@getRegister']);
    Route::post('register',                                 ['as' => 'register_post', 'uses' => 'AuthController@postRegister']);
    Route::get('social/{slug}',                             ['as' => 'social', 'uses' => 'AuthController@redirectToProvider']);
    Route::get('logout',                                    ['as' => 'logout', 'uses' => 'AuthController@getLogout']);

    Route::get('',                                          ['as' => 'home','uses' => 'HomeController@index']);
    Route::get('404',                      ['as' => '404','uses' => 'HomeController@page_notfound']);
    Route::get('search',                                  ['as' => 'search','uses' => 'HomeController@search']);
    // Route::get('tim-kiem',                                  ['as' => 'advance_search','uses' => 'HomeController@advance_search']);
    Route::get('filter',                                  ['as' => 'advance_fill','uses' => 'HomeController@advance_fill']);
    Route::post('getlink',                                  ['as' => 'get_link','uses' => 'HomeController@get_link']);
    Route::post('video-list',                               ['as' => 'get_video_list','uses' => 'HomeController@get_video_list']);
    Route::get('pornstar',                                 ['as' => 'stars','uses' => 'CategoryController@stars']);
    Route::get('news',                                   ['as' => 'client_article','uses' => 'ArticleController@index']);
    Route::get('wishlist',                                ['as' => 'user_bookmark','uses' => 'CategoryController@bookmark']);
    Route::get('member-page',                             ['as' => 'user','uses' => 'AuthController@home']);
    Route::get('update-personal-info',                     ['as' => 'user_edit','uses' => 'AuthController@edit']);
    Route::post('update-personal-info',                    ['as' => 'user_update','uses' => 'AuthController@update']);
    Route::get('notify',                                 ['as' => 'user_notifi','uses' => 'AuthController@notifi']);
    // Member
    Route::get('statics',                                  ['as' => 'user_static','uses' => 'FilmMemberController@overview']);
    Route::get('/my-porn',                             ['as' => 'member_films','uses' => 'FilmMemberController@index']);
    Route::get('/add-film',                            ['as' => 'member_create_film','uses' => 'FilmMemberController@create']);
    Route::get('/get-list-file-drive',                      ['as' => 'member_drive_tool','uses' => 'FilmMemberController@drive_tool']);
    Route::post('/get-list-file-drive',                     ['as' => 'post_drive_tool','uses' => 'FilmMemberController@post_drive_tool']);
    Route::get('/film-error',                              ['as' => 'member_error_film','uses' => 'FilmMemberController@ep_error']);
    Route::get('/update-film/{id}',                            ['as' => 'member_edit_film','uses' => 'FilmMemberController@edit']);
    Route::get('/episodes/{id}',                       ['as' => 'member_episode','uses' => 'FilmMemberController@film_ep']);
    Route::get('/update-episode/{id}',                        ['as' => 'member_edit_film_ep','uses' => 'FilmMemberController@edit_ep']);
    Route::post('/update-episode/{id}',                       ['as' => 'member_update_film_ep','uses' => 'FilmMemberController@update_ep']);
    Route::get('/add-episode/{id}',                       ['as' => 'member_add_film_ep','uses' => 'FilmMemberController@add_ep']);
    Route::post('/add-film',                           ['as' => 'member_store_film','uses' => 'FilmMemberController@store']);
    Route::post('/add-episode/{id}',                      ['as' => 'member_store_ep','uses' => 'FilmMemberController@store_ep']);
    Route::post('/update-film/{id}',                           ['as' => 'member_update_film','uses' => 'FilmMemberController@update']);
    Route::get('/delete-film/{id}',                            ['as' => 'member_delete_film','uses' => 'FilmMemberController@delete']);
    Route::get('/delete-episodes',                       ['as' => 'member_delete_video','uses' => 'FilmMemberController@delete_video']);
    Route::get('/delete-episode/{id}',                        ['as' => 'member_delete_ep','uses' => 'FilmMemberController@delete_ep']);
    Route::get('/payments',                       ['as' => 'checkout','uses' => 'FilmMemberController@checkout']);

    Route::get('most-view',                                 ['as' => 'popular','uses' => 'CategoryController@popular']);
    Route::get('film-hot',                                  ['as' => 'hot','uses' => 'CategoryController@hot']);
    Route::post('bookmark',                                 ['as' => 'bookmark','uses' => 'HomeController@bookmark']);
    Route::post('report',                                   ['as' => 'report','uses' => 'HomeController@report']);
    Route::get('category',                                  ['as' => 'genres','uses' => 'CategoryController@genres']);
    Route::get('country',                                  ['as' => 'countries','uses' => 'CategoryController@countries']);
    Route::get('year',                             ['as' => 'years','uses' => 'CategoryController@years']);
    Route::get('keyword',                                   ['as' => 'tags','uses' => 'TagController@index']);
    Route::get('pornstar/{slug}',                          ['as' => 'star_show','uses' => 'CategoryController@star_show']);
    Route::get('keyword/{slug}',                            ['as' => 'tag','uses' => 'TagController@detail']);
    Route::get('news/{slug}',                            ['as' => 'show_article','uses' => 'ArticleController@show']);
    Route::get('category/{slug}',                           ['as' => 'genre','uses' => 'CategoryController@genre_detail']);
    Route::get('country/{slug}',                           ['as' => 'country','uses' => 'CategoryController@country']);
    Route::get('year/{slug}',                      ['as' => 'year','uses' => 'CategoryController@year']);
    Route::get('download/{slug}/{type}',                    ['as' => 'download','uses' => 'FilmController@download']);
    Route::get('download-{id}/{slug}/{type}',               ['as' => 'download_ep','uses' => 'FilmController@download_ep']);
    Route::get('{category}/{slug}',                         ['as' => 'film_detail','uses' => 'FilmController@detail']);
    Route::get('{category}/{slug}/{ep}',                    ['as' => 'play','uses' => 'FilmController@play']);
    Route::get('{slug}',                                    ['as' => 'category','uses' => 'CategoryController@index']);
});

// Admin
// View::composer(['admin.partials.sidebar'], 'App\Http\ViewComposers\admin\SidebarComposer');
// View::composer(['admin.partials.dashboard_header'], 'App\Http\ViewComposers\admin\DashboardComposer');
// Client
View::composer(['client.layouts.header', 'client.layouts.sidebar'], 'App\Http\ViewComposers\Client\MenuComposer');
View::composer(['client.modal.advance-fill', 'client.block.filter-block'], 'App\Http\ViewComposers\Client\ModalFillComposer');
View::composer(['client.block.article'], 'App\Http\ViewComposers\Client\ArticleComposer');
View::composer(['client.block.calendar'], 'App\Http\ViewComposers\Client\CalendarComposer');
View::composer(['client.block.actor'], 'App\Http\ViewComposers\Client\ActorComposer');
View::composer(['client.block.trailer'], 'App\Http\ViewComposers\Client\TrailerComposer');
View::composer(['client.block.popular'], 'App\Http\ViewComposers\Client\PopularComposer');
View::composer(['client.block.action'], 'App\Http\ViewComposers\Client\ActionComposer');
View::composer(['client.block.thiller'], 'App\Http\ViewComposers\Client\ThillerComposer');
View::composer(['client.layouts.js'], 'App\Http\ViewComposers\Client\JsComposer');