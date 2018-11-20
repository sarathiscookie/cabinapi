<?php

namespace App\Http\Controllers\Mountainschool;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Settings;
use App\Http\Requests\TourRequest;
use Auth;

class SettingsController extends Controller
{
    /**
     * Display the form for editing the resource
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit()
    {
        $basic_settings = Settings::where('is_delete', 0)
            ->where('user_id', Auth::user()->_id)
            ->first();

        return view('mountainschool.settings.edit', ['basicSettings' => $basic_settings]);
    }

    /**
     * Update a given location informations
     *
     * @param {TourRequest} $request
     * @return Response
     */
    public function update(TourRequest $request)
    {
        if($request->has('updateBasicSettings')) {
            if ((($request->no_guides + $request->guests) == $request->sleeps) || ($request->no_guides + $request->guests) == ($request->beds + $request->dorms)) {
                Settings::updateOrCreate(
                    ['user_id'           => Auth::user()->_id, 'is_delete' => 0],
                    [
                        'contact_person' => $request->contact_person,
                        'no_guides'      => (int)$request->no_guides,
                        'half_board'     => $request->half_board,
                        'beds'           => (int)$request->beds,
                        'dorms'          => (int)$request->dorms,
                        'sleeps'         => (int)$request->sleeps,
                        'guests'         => (int)$request->guests
                    ]
                );

                return redirect()->back()->with('success', __('tours.successMsgbsUpt'));
            }

            return back()->with('error', __('tours.settings.match.sleeps_and_guests'));
        }
        else {
            abort(404);
        }
    }
}