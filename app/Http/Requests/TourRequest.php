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

        if ($this->request->get('formPart') === 'createCabin') {
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

        if ($this->request->get('formPart') === 'createTour') {
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
        if ($this->request->get('formPart') === 'updateTour') {
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
        if ($this->request->get('updateBasicSettings') === 'updateBasicSettings') {
            $rules = [
                'contact_person' => 'required|max:100',
                'beds'           => 'required|not_in:0',
                'dorms'          => 'required|not_in:0',
                'sleeps'         => 'required|not_in:0',
            ];
        }

        if ($this->request->get('formPart') === 'newBooking') {
            $k     = $this->request->get('no_cabins');
            $rlApp = [];
            for($j = 1; $j <= $k; $j++) {
                $rlApp['guests'.$j.'.*']     = 'required|not_in:0';
                $rlApp['check_in'.$j.'.*']   = 'required';
                $rlApp['check_out'.$j.'.*']  = 'required';
                $rlApp['sleeps'.$j.'.*']     = 'required|not_in:0';
                $rlApp['beds'.$j.'.*']       = 'required_without:dormitory'.$j.'.*';
                $rlApp['dormitory'.$j.'.*']  = 'required_without:beds'.$j.'.*';
            }

            $rules1 = [
                'ind_tour_no.*' => [
                    'required',
                    'max:100',
                    Rule::unique('mschool', 'ind_tour_no')->where(function($query) {
                        $query->where('is_delete', 0);
                    }),
                ],
                'tour_guide.*'  => 'required',
            ];

            $rules = array_merge($rlApp, $rules1);
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
        return [
            'current_pwd'  => __('tours.currentPwd'),
            'new_pwd'      => __('tours.newPwd'),
            'con_pwd'      => __('tours.conPwd'),
            'ind_tour_no'  => __('tours.indTourNo'),
        ];
    }

    /**
     * Get the validation message
     *
     * @return array
     */
    public function messages()
    {
        $k       =  $this->request->get('no_cabins');
        $msgApp  = [];

        for($j = 1 ; $j <= $k ; $j++) {
            $msgApp[ 'no_guides'.$j.'.*.required']         = __('tours.guidesRequired');
            $msgApp[ 'guests'.$j.'.*.required']            = __('tours.guestsRequired');
            $msgApp[ 'guests'.$j.'.*.not_in']              = __('tours.guestNotIn');
            $msgApp[ 'check_in'.$j.'.*.required']          = __('tours.checkInRequired');
            $msgApp[ 'check_out'.$j.'.*.required']         = __('tours.checkOutRequired');
            $msgApp[ 'sleeps'.$j.'.*.required']            = __('tours.sleepsRequired');
            $msgApp[ 'sleeps'.$j.'.*.not_in']              = __('tours.sleepsNotIn');
            $msgApp[ 'beds'.$j.'.*.required_without']      = __('tours.bedsRequiredWithout');
            $msgApp[ 'dormitory'.$j.'.*.required_without'] = __('tours.dormsRequireWithout');
        }

        $messages =  [
            'no_cabins.required'           => __('tours.noOfCabinsRequired'),
            'no_cabins.not_in'             => __('tours.noOfCabinsNotIn'),
            'ind_tour_no.*.required'       => __('tours.individualTourNoRequired'),
            'tour_guide.*.required'        => __('tours.tourGuideRequired'),
            'ind_tour_no.*.unique'         => __('tours.individualTourNoUnique'),
            'ind_tour_no.*.max'            => __('tours.individualTourNoMax')
        ];

        $msg = array_merge($msgApp,$messages);

        return $msg;
    }
}
