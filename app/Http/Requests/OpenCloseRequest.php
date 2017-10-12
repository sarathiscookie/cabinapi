<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpenCloseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if($this->request->get('season') == 'summer') {
            $rules = [
                'summerSeason'          => 'required|not_in:0',
                'summerSeasonStatus'    => 'required|not_in:0',
                'earliest_summer_open'  => 'required',
                'earliest_summer_close' => 'required',
                'latest_summer_open'    => 'required',
                'latest_summer_close'   => 'required',
            ];
        }

        if($this->request->get('season') == 'winter') {
            $rules = [
                'winterSeason'          => 'required|not_in:0',
                'winterSeasonStatus'    => 'required|not_in:0',
                'earliest_winter_open'  => 'required',
                'earliest_winter_close' => 'required',
                'latest_winter_open'    => 'required',
                'latest_winter_close'   => 'required',
            ];
        }

        return $rules;
    }
}
