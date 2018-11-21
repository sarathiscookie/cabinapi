<?php

namespace App\Http\Requests\Mountainschool;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookingRequest extends FormRequest
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

        $k     = $this->request->get('no_cabins');
        $m     = count($this->request->get('ind_tour_no'));
        $rules1 = [];

        for($j = 1; $j <= $k; $j++) {
            $rules1['guests'.$j.'.*']     = 'required|not_in:0';
            $rules1['sleeps'.$j.'.*']     = 'required|not_in:0';
            $rules1['beds'.$j.'.*']       = 'required_without:dormitory'.$j.'.*';
            $rules1['dormitory'.$j.'.*']  = 'required_without:beds'.$j.'.*';
        }

        for ($i=1; $i <= $m; $i++) {
            for($j = 1; $j <= $k; $j++) {
                $rules1['check_in' . $i . $j . '.*.*']   = 'required';
                $rules1['check_out' . $i . $j . '.*.*']  = 'required';
            }
        }

        $rules2 = [
            'ind_tour_no.*' => [
                'required',
                'max:100',
                Rule::unique('mschool', 'ind_tour_no')->where(function($query) {
                    $query->where('is_delete', 0);
                }),
            ],
            'tour_guide.*'  => 'required',
        ];

        $rules = array_merge($rules1, $rules2);

        return $rules;
    }

    /**
     * Get the validation messages
     *
     * @return array
     */
    public function messages()
    {
        $k       =  $this->request->get('no_cabins');
        $m     = count($this->request->get('ind_tour_no'));
        $msgApp  = [];

        for($j = 1 ; $j <= $k ; $j++) {
            $msgApp[ 'no_guides'.$j.'.*.required']         = __('tours.guidesRequired');
            $msgApp[ 'guests'.$j.'.*.required']            = __('tours.guestsRequired');
            $msgApp[ 'guests'.$j.'.*.not_in']              = __('tours.guestNotIn');
            $msgApp[ 'sleeps'.$j.'.*.required']            = __('tours.sleepsRequired');
            $msgApp[ 'sleeps'.$j.'.*.not_in']              = __('tours.sleepsNotIn');
            $msgApp[ 'beds'.$j.'.*.required_without']      = __('tours.bedsRequiredWithout');
            $msgApp[ 'dormitory'.$j.'.*.required_without'] = __('tours.dormsRequireWithout');
        }

        for ($i=1; $i <= $m; $i++) {
            for($j = 1; $j <= $k; $j++) {
                $msgApp[ 'check_in' . $i . $j . '.*.*.required']          = __('tours.checkInRequired');
                $msgApp[ 'check_out' . $i . $j . '.*.*.required']         = __('tours.checkOutRequired');
            }
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
