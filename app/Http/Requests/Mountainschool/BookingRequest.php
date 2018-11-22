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
            $tour = $this->request->get('tours_ids')[$j];
            $k = $this->request->get('no_cabins')[$tour];

            for ($i = 1; $i <= $k; $i++) {
                $rules1['guests' . $tour . $i . '.*']     = 'required|not_in:0';
                $rules1['sleeps' . $tour . $i. '.*']     = 'required|not_in:0';
                $rules1['beds' . $tour . $i. '.*']       = 'required_without:dormitory';
                $rules1['dormitory' . $tour . $i. '.*']  = 'required_without:beds';
                $rules1['check_in' . $tour . $i. '.*']   = 'required';
                $rules1['check_out' . $tour . $i. '.*']  = 'required';
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
            $tour = $this->request->get('tours_ids')[$j];
            $k = $this->request->get('no_cabins')[$tour];

            for ($i=1; $i <= $k; $i++) {
                $msgApp[ 'no_guides' . $tour . $i . '.*.required']         = __('tours.guidesRequired');
                $msgApp[ 'guests' . $tour . $i . '.*.required']            = __('tours.guestsRequired');
                $msgApp[ 'guests' . $tour . $i . '.*.not_in']              = __('tours.guestNotIn');
                $msgApp[ 'sleeps' . $tour . $i . '.*.required']            = __('tours.sleepsRequired');
                $msgApp[ 'sleeps' . $tour . $i . '.*.not_in']              = __('tours.sleepsNotIn');
                $msgApp[ 'beds' . $tour . $i . '.*.required_without']      = __('tours.bedsRequiredWithout');
                $msgApp[ 'dormitory'. $tour . $i .'.*.required_without']   = __('tours.dormsRequireWithout');
                $msgApp[ 'check_in' . $tour . $i . '.*.required']          = __('tours.checkInRequired');
                $msgApp[ 'check_out' . $tour . $i . '.*.required']         = __('tours.checkOutRequired');
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
