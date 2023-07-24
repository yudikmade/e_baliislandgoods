<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lang;
use Validator;
use Session;

use App\Helper\Common_helper;

class HomeController extends Controller{

    public function index() {
        $data = array(
            'share_page' => array(
                'description' => env('META_DESCRIPTION'),
                'keyword' => env('META_KEYWORD'),
                'title' => env('AUTHOR_SITE'),
                'image' => asset(env('URL_IMAGE').'logo.png')
            ),
            'title' => "Home | ".env('AUTHOR_SITE'),
            'description' => env('META_DESCRIPTION'),
            'alt_image' => 'Home | '.env('AUTHOR_SITE'),
            'current_currency' => \App\Helper\Common_helper::get_current_currency(),
            'is_page' => 'home'
        );
        return view('frontend.home', $data);
    }

    public function collections() {
        $data = array(
            'share_page' => array(
                'description' => env('META_DESCRIPTION'),
                'keyword' => env('META_KEYWORD'),
                'title' => env('AUTHOR_SITE'),
                'image' => asset(env('URL_IMAGE').'logo.png')
            ),
            'title' => "Collections | ".env('AUTHOR_SITE'),
            'description' => env('META_DESCRIPTION'),
            'alt_image' => 'Collections | '.env('AUTHOR_SITE'),
            'is_page' => 'collections'
        );
        return view('frontend.collections', $data);
    }
}
