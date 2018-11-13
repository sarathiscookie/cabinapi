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
        $rules = [];
        if ($this->request->get('storeSeason') === 'storeSeason') {
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

        if ($this->request->get('updateSummerSeason') === 'updateSummerSeason') {
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

        if ($this->request->get('updateWinterSeason') === 'updateWinterSeason') {
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

    /**
     * Get the validation attribute that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        if (\Lang::locale() == 'de') {
            return [
                'summerSeasonYear'      => 'Sommer Saison',
                'summerSeasonStatus'    => 'Sommer Status',
                'earliest_summer_open'  => 'Früheste Öffnungsdatum',
                'earliest_summer_close' => 'Früheste Schließungsdatum',
                'latest_summer_open'    => 'Späteste Öffnungsdatum',
                'latest_summer_close'   => 'Späteste Schließungsdatum',
                'summer_next_season'    => 'Nächste Sommersaison buchbar ab',

                'earliest_winter_open'  => 'Früheste Öffnungsdatum',
                'earliest_winter_close' => 'Früheste Schließungsdatum',
                'latest_winter_open'    => 'Späteste Öffnungsdatum',
                'latest_winter_close'   => 'Späteste Schließungsdatum',
                'winter_next_season'    => 'Nächste Wintersaison buchbar ab',

            ];
        }
        else {
            return [];
        }
    }

    /**
     * Get the validation messages.
     *
     * @return array
     */
    public function messages()
    {
        if (\Lang::locale() == 'de') {
            return [
                'numeric'                   => 'Bitte Zahl angeben',
                'summerSeasonYear.not_in'   => 'Sommer Saison muss angegeben werden.',
                'summerSeasonStatus.not_in' => 'Sommer Status muss angegeben werden.',
                'winterSeasonYear.not_in'   => 'Winter Saison muss angegeben werden.',
                'winterSeasonStatus.not_in' => 'Winter Status muss angegeben werden.'
            ];
        }
        else {
            return [];
        }
    }
}
