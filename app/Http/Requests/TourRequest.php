<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class TourRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
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
        $rules     = [];

        if ($this->request->get('formPart') == 'createCabin') {
            $rules = [
                'cabin_name'     => [
                    'required',
                    Rule::unique('cabins', 'name')->where(function($query) {
                        $query->where('is_delete', 0);
                    }),
                ],
                'contact_person' => 'required',
                'email'          => 'required',
            ];
        }

        if ($this->request->get('formPart') == 'createTour') {
            $rules = [
                'tour_name' => [
                    'required',
                    Rule::unique('tour', 'tour_name')->where(function ($query) {
                        $query->where('is_delete', 0);
                    }),
                ],
                'tour_no'   => [
                    'required',
                    Rule::unique('tour', 'tour_no')->where(function ($query) {
                        $query->where('is_delete', 0);
                    }),
                ]
            ];

            if($this->request->get('no_cabins') == 0 ){
                $rules['no_cabins'] = 'required|not_in:0';
            }
        }

        /* Update Tour */
        if ($this->request->get('formPart') == 'updateTour') {
            $rules = [
                'tour_name' => [
                    'required',
                    Rule::unique('tour', 'tour_name')->where(function ($query) {
                       $query->where('is_delete', 0);
                        $query->where( '_id',  '<>', new \MongoDB\BSON\ObjectID($this->request->get('udtId')) );


                    })
                ],
                'tour_no' => [
                    'required',
                    Rule::unique('tour', 'tour_no' , $this->request->get('tour_no'))->where(function ($query) {
                        $query->where('is_delete', 0);
                        $query->where( '_id',  '<>', new \MongoDB\BSON\ObjectID($this->request->get('udtId')) );
                    }),
                ],

            ];
            if($this->request->get('no_cabins') == 0 ){
                $rules['no_cabins'] = 'required|not_in:0';
            }
        }

        /* Update contact */
        if ($this->request->get('updatePwd') == 'updateContact') {
            $rules = [
                'firstname'     => 'required',
                'lastname'      => 'required',
                'telephone'     => 'required',
                'zip'           => 'required',
                'city'          => 'required',
                'street'        => 'required',
                'country'       => 'required',
                'email'         => 'required',
                'birthDay'      => 'required',
                'userName'      => 'required',
                'usrPassword'   => 'required',
                'usrLogo'       => 'image|mimes:jpeg,bmp,png|size:2000'
            ];
        }

        /* Update password */
        if ($this->request->get('updatePwd') == 'updatePwd') {
            $rules = [
                'current_pwd'     => 'required',
                'new_pwd'         => 'required',
                'con_pwd'         => 'required|same:new_pwd',
            ];
        }

        /* Update basic settings */
        if ($this->request->get('updateBasicSettings') == 'updateBasicSettings') {
            $rules = [
                'no_guides'      => 'required',
                'contact_person' => 'required',
                'notice'         => 'required',
            ];
        }

        if ($this->request->get('formPart') == 'newBooking') {
            $k     =  $this->request->get('no_cabins') ;

            $rlApp = [];
            for($j=1 ; $j<=$k ; $j++) {
                $rlApp['no_guides' . $j .'.*']  = 'required';
                $rlApp['guests' . $j .'.*']     = 'required|not_in:0';
                $rlApp['check_in' . $j.'.*' ]   = 'required';
                $rlApp['days' . $j .'.*']       = 'required';
            }

            $rules1 = [
                'ind_tour_no.*' => [
                    'required',
                    Rule::unique('mschool', 'ind_tour_no')->where(function($query) {
                        $query->where('is_delete', 0);
                    }),
                ],
                'tour_guide.*'  => 'required',
            ];

            $rules = array_merge($rlApp,$rules1);
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
        if (\App::isLocale(env('APP_LOCALE')) === 'de') {
            return [ 'current_pwd'  => 'Aktuelles Passwort',
                     'new_pwd'      => 'Neues Passwort',
                     'con_pwd'      => 'Passwort bestätigen',
                     'ind_tour_no'  => 'Individuelle Tour Nr.',
                     'ind_notice'   => 'Kommentar'
            ];
        }
        else {
            return [
                'current_pwd' => 'Current Password',
                'new_pwd'     => 'New Password',
                'con_pwd'     => 'Confirm Password',
                'ind_tour_no' => 'Individual Tour No',
                'ind_notice'  => 'Comments'
            ];
        }
    }

    /**
     * Get the validation message
     *
     * @return array
     */
    public function messages()
    {
        $k       =  $this->request->get('no_cabins') ;
        $msgApp  = [];

        for($j = 1 ; $j <= $k ; $j++) {
            $rlApp['no_guides' . $j .'.*'] = 'required';
            $rlApp['guests' . $j .'.*']    = 'required';
            $rlApp['check_in' . $j.'.*' ]  = 'required';
            $rlApp['days' . $j .'.*']      = 'required';
        }

        if (\App::isLocale(env('APP_LOCALE')) === 'de') {
            for($j=1 ; $j<=$k ; $j++) {
                $msgApp[ 'no_guides'.$j.'.*.required'] = 'No of Guide field is required.';
                $msgApp[ 'guests'.$j.'.*.required']    = 'Anzahl der Gäste muss eingetragen sein';
                $msgApp[ 'guests'.$j.'.*.not_in']      = 'Anzahl der Gäste muss eingetragen sein';
                $msgApp[ 'check_in'.$j.'.*.required']  = 'Anreisetag muss eingetragen sein';
                $msgApp[ 'days'.$j.'.*.required']      = 'Tage müssen eingetragen werden';

            }

            $messages =  ['no_cabins.required' => 'Mindestens eine Hütte muss ausgewählt werden',
                'no_cabins.not_in'             => 'Mindestens eine Hütte muss ausgewählt werden',
                'ind_tour_no.*.required'       => 'Individuelle Tour Nr. muss eingetragen sein',
                'ind_notice.*.required'        => 'Individuelle Bemerkung muss eingetragen sein',
                'tour_guide.*.required'        => 'Bergführer muss eingetragen sein',
                'ind_tour_no.*.unique'         =>  'Diese Tournummer gibt es bereits'];

            $msg = array_merge($msgApp,$messages);

            return $msg;
        }
        else
            {
            for($j=1 ; $j<=$k ; $j++) {
                $msgApp[ 'no_guides'.$j.'.*.required']  = 'No of Guide field is required.';
                $msgApp[ 'guests'.$j.'.*.required']     = 'No of Guest field is required.';
                $msgApp[ 'guests'.$j.'.*.not_in']       = 'No of Guest field is required.';
                $msgApp[ 'check_in'.$j.'.*.required']   = 'Check In field is required.';
                $msgApp[ 'days'.$j.'.*.required']       = 'Days field is required.';
            }

            $messages =  ['no_cabins.required'  => 'Please select at least one Cabin for this Tour.',
                 'no_cabins.not_in'             => 'Please select at least one Cabin for this Tour.',
                'ind_tour_no.*.required'        => 'Individual Tour No field is required.',
                'ind_notice.*.required'         => 'Individual Notice field is required.',
                'tour_guide.*.required'         => 'Tour Guide field is required.',
                'ind_tour_no.*.unique'          =>  'The Individual Tour No has already been taken.',
            ];

            $msg = array_merge($msgApp,$messages);

            return $msg;
        }
    }
}
