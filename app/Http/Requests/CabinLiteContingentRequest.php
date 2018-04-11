<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CabinLiteContingentRequest extends FormRequest
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
        $rules =  [
            'reservation_type'        => 'required|not_in:2',
            'normal_beds'             => 'nullable|numeric',
            'normal_dorms'            => 'nullable|numeric',
            'normal_emergency_rooms'  => 'numeric|nullable',
            'normal_inquiry_guest'    => 'numeric|nullable',
            'normal_ms_inquiry_guest' => 'numeric|nullable',

        ];

        if($this->request->get('notRegularCheckbox') === '1') {
            $rules_app =  [
                'not_regular_date'             => 'required_if:notRegularCheckbox,1',
                'not_regular_beds'             => 'required_if:notRegularCheckbox,1|numeric',
                'not_regular_dorms'            => 'required_if:notRegularCheckbox,1|numeric',
                'not_regular_emergency_rooms'  => 'numeric|nullable',
                'not_regular_inquiry_guest'    => 'numeric|nullable',
                'not_regular_ms_inquiry_guest' => 'numeric|nullable',
            ];

            $rules =    array_merge($rules,$rules_app);
        }

        if($this->request->get('regularCheckbox') === '1') {

            if($this->request->get('monday') === '1') {
                $rules_app =  [
                    'mon_beds'                     => 'required|numeric',
                    'mon_dorms'                    => 'required|numeric',
                    'mon_emergency_rooms'          => 'numeric|nullable',
                    'mon_inquiry_guest'            => 'numeric|nullable',
                    'mon_ms_inquiry_guest'         => 'numeric|nullable',

                ];
                $rules =  array_merge($rules,$rules_app);
            }
            if($this->request->get('tuesday') === '1') {
                $rules_app =  [
                    'tue_beds'                     => 'required|numeric',
                    'tue_dorms'                    => 'required|numeric',
                    'tue_emergency_rooms'          => 'numeric|nullable',
                    'tue_inquiry_guest'            => 'numeric|nullable',
                    'tue_ms_inquiry_guest'         => 'numeric|nullable',
                ];
                $rules =   array_merge($rules,$rules_app);
            }
            if($this->request->get('wednesday') === '1') {
                $rules_app =  [
                    'wed_beds'                     => 'required|numeric',
                    'wed_dorms'                    => 'required|numeric',
                    'wed_emergency_rooms'          => 'numeric|nullable',
                    'wed_inquiry_guest'            => 'numeric|nullable',
                    'wed_ms_inquiry_guest'         => 'numeric|nullable',
                ];
                $rules =  array_merge($rules,$rules_app);
            }
            if($this->request->get('thursday') === '1') {
                $rules_app =  [
                    'thu_beds'                     => 'required|numeric',
                    'thu_dorms'                    => 'required|numeric',
                    'thu_emergency_rooms'          => 'numeric|nullable',
                    'thu_inquiry_guest'            => 'numeric|nullable',
                    'thu_ms_inquiry_guest'         => 'numeric|nullable',
                ];
                $rules =   array_merge($rules,$rules_app);
            }
            if($this->request->get('friday') === '1') {
                $rules_app =  [
                    'fri_beds'                     => 'required|numeric',
                    'fri_dorms'                    => 'required|numeric',
                    'fri_emergency_rooms'          => 'numeric|nullable',
                    'fri_inquiry_guest'            => 'numeric|nullable',
                    'fri_ms_inquiry_guest'         => 'numeric|nullable',
                ];
                $rules =   array_merge($rules,$rules_app);
            }
            if($this->request->get('saturday') === '1') {
                $rules_app =  [
                    'sat_beds'                     => 'required|numeric',
                    'sat_dorms'                    => 'required|numeric',
                    'sat_emergency_rooms'          => 'numeric|nullable',
                    'sat_inquiry_guest'            => 'numeric|nullable',
                    'sat_ms_inquiry_guest'         => 'numeric|nullable',
                ];
                $rules =   array_merge($rules,$rules_app);
            }
            if($this->request->get('sunday') === '1') {
                $rules_app =  [
                    'sun_beds'                     => 'required|numeric',
                    'sun_dorms'                    => 'required|numeric',
                    'sun_emergency_rooms'          => 'numeric|nullable',
                    'sun_inquiry_guest'            => 'numeric|nullable',
                    'sun_ms_inquiry_guest'         => 'numeric|nullable',
                ];
                $rules =   array_merge($rules,$rules_app);
            }
        }

        return $rules;
    }
    /**
     * Get the validation attribute that apply to the request.
     *
     * @return array
     */
    public function   attributes(){
        if (\Lang::locale() == 'de') {
            $number_of_beds = 'Anzahl der Betten' ;
            $number_of_norms= 'Anzahl der Matratzenlager' ;
        }
        else{
            $number_of_beds = 'No of beds' ;
            $number_of_norms= 'No of dorms' ;
        }

            return ['normal_beds' =>  $number_of_beds, 'normal_dorms' => $number_of_norms,
                'mon_beds' =>$number_of_beds, 'mon_dorms' => $number_of_norms,
                'tue_beds' => $number_of_beds, 'tue_dorms' => $number_of_norms,
                'wed_beds' =>$number_of_beds, 'wed_dorms' =>$number_of_norms,
                'thu_beds' => $number_of_beds, 'thu_dorms' => $number_of_norms,
                'fri_beds' => $number_of_beds, 'fri_dorms' => $number_of_norms,
                'sat_beds' => $number_of_beds, 'sat_dorms' => $number_of_norms,
                'sun_beds' => $number_of_beds, 'sun_dorms' => $number_of_norms,

            ];


    }
    /**
     * Get the validation message.
     *
     * @return array
     */
    public function messages()
    {
        if (\Lang::locale() == 'de') {
            return [
                'numeric' => 'Bitte Zahl angeben',
                'not_regular_date.required_if'=>'Dieses Feld Datumsbereich ist erforderlich, wenn das Kontrollkästchen "Nicht regelmäßig" aktiviert ist.',
                'reservation_type.not_in'        => 'Die gewählte Reservierungsart ist ungültig.',
                'not_regular_beds.required' => 'Die Anzahl der Betten wird benötigt, wenn das Kontrollkästchen "Nicht regulär" aktiviert ist.',
                 'not_regular_dorms.required' => 'Die Anzahl der Matratzenlager wird benötigt, wenn das Kontrollkästchen "Nicht regulär" aktiviert ist.'
            ];
        }
        else{
            return [
                'not_regular_date.required_if'=>'The Date range field is required when "Not Regular" checkbox is checked.',
                 'not_regular_beds.required_if' => 'The  No of beds field is required when "Not Regular" checkbox is checked.',
                 'not_regular_dorms.required_if' => 'The  No of dorms field is required when "Not Regular" checkbox is checked.'
            ];

        }

    }
}
