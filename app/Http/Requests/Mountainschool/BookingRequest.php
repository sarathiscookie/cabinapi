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
        $m = count($this->request->get('ind_tour_no'));

        $rules1 = [];

        for ($j = 0; $j < $m; $j++) {
            $k = $this->request->get('no_cabins')[$j];

            for ($i = 1; $i <= $k; $i++) {
                $rules1['guests.*.*']     = 'required|not_in:0';
                $rules1['sleeps.*.*']     = 'required|not_in:0';
                $rules1['beds.*.*']       = 'required_without:dormitory';
                $rules1['dormitory.*.*']  = 'required_without:beds';
                $rules1['check_in.*.*']   = 'required';
                $rules1['check_out.*.*']  = 'required';
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
        $m = count($this->request->get('ind_tour_no'));

        $msgApp  = [];

        for ($j = 0; $j < $m; $j++) {
            $k = $this->request->get('no_cabins')[$j];

            for ($i=1; $i <= $k; $i++) {
                $msgApp[ 'no_guides.*.*.required']         = __('tours.guidesRequired');
                $msgApp[ 'guests.*.*.required']            = __('tours.guestsRequired');
                $msgApp[ 'guests.*.*.not_in']              = __('tours.guestNotIn');
                $msgApp[ 'sleeps.*.*.required']            = __('tours.sleepsRequired');
                $msgApp[ 'sleeps.*.*.not_in']              = __('tours.sleepsNotIn');
                $msgApp[ 'beds.*.*.required_without']      = __('tours.bedsRequiredWithout');
                $msgApp[ 'dormitory' .'.*.required_without']   = __('tours.dormsRequireWithout');
                $msgApp[ 'check_in.*.*.required']          = __('tours.checkInRequired');
                $msgApp[ 'check_out.*.*.required']         = __('tours.checkOutRequired');
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
