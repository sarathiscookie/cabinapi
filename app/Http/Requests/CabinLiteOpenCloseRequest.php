<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CabinLiteOpenCloseRequest extends FormRequest
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
      //  $rules=[];
        if($this->request->get('storeSeason') == 'storeSeason') {
            $rules = [
                'summerSeasonYear'      => 'required|not_in:0',
                'summerSeasonStatus'    => 'required|not_in:0',
                'earliest_summer_open'  => 'required',
                'earliest_summer_close' => 'required',
                'latest_summer_open'    => 'required',
                'latest_summer_close'   => 'required',
                'summer_next_season'    => 'required',
                'winterSeasonYear'      => 'required|not_in:0',
                'winterSeasonStatus'    => 'required|not_in:0',
                'earliest_winter_open'  => 'required',
                'earliest_winter_close' => 'required',
                'latest_winter_open'    => 'required',
                'latest_winter_close'   => 'required',
                'winter_next_season'    => 'required',
            ];
        }

        if($this->request->get('updateSummerSeason') == 'updateSummerSeason') {
            $rules = [
                'summerSeasonYear'      => 'required|not_in:0',
                'summerSeasonStatus'    => 'required|not_in:0',
                'earliest_summer_open'  => 'required',
                'earliest_summer_close' => 'required',
                'latest_summer_open'    => 'required',
                'latest_summer_close'   => 'required',
                'summer_next_season'    => 'required',
            ];
        }

        if($this->request->get('updateWinterSeason') == 'updateWinterSeason') {
            $rules = [
                'winterSeasonYear'      => 'required|not_in:0',
                'winterSeasonStatus'    => 'required|not_in:0',
                'earliest_winter_open'  => 'required',
                'earliest_winter_close' => 'required',
                'latest_winter_open'    => 'required',
                'latest_winter_close'   => 'required',
                'winter_next_season'    => 'required',
            ];
        }

        return $rules;
    }

}
