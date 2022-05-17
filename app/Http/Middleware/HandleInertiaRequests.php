<?php

namespace App\Http\Middleware;

use Eastwest\Json\Json;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Laracasts\Flash\Message;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request) : array
    {
        return array_merge(parent::share($request), [
            'app' => [
                'name' => config('app.name'),
                'current_route' => request()->route()->getName(),
                'back_url' => url()->previous(),
                'asset_url' => asset('')
            ],
            'user' =>function() use($request) {
                return $request->user()
                    ? $request->user()->append(['notifications','unread_notifications','permissions_all','role_text'])
                    : null;
                },
            'swal' => fn () => (object) Json::decode($request->session()->get('alert.config','[]')),
            'flash' => fn () => collect($request->session()->get('flash_notification'))
                ->transform(function(Message $arr){
                    return [
                        'type' => $arr->level,
//                        'className' => $arr->level,

//                        'icon' => $arr->level,
//                        'ripple' => $arr->level,
                        'dismissible' => $arr->important,
                        'message' => $arr->message,

                    ];
                }),

        ]);
    }
}
