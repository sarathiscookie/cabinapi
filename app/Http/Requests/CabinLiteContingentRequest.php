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
        return [
            'reservation_type'        => 'required|not_in:2',
            'normal_beds'             => 'required|numeric',
            'normal_dorms'            => 'required|numeric',
            'normal_emergency_rooms'  => 'numeric|nullable',
            'normal_inquiry_guest'    => 'numeric|nullable',
            'normal_ms_inquiry_guest' => 'numeric|nullable',
        ];
    }

    /**
     * Get the validation attribute that apply to the request.
     *
     * @return array
     */
    public function   attributes(){
        if (\Lang::locale() == 'de') {
            $number_of_beds     = 'Anzahl der Betten' ;
            $number_of_norms    = 'Anzahl der Matratzenlager' ;
        }
        else{
            $number_of_beds     = 'No of beds' ;
            $number_of_norms    = 'No of dorms' ;
        }

        return [
            'normal_beds'   => $number_of_beds, 'normal_dorms'  => $number_of_norms,
            'mon_beds'      => $number_of_beds, 'mon_dorms'     => $number_of_norms,
            'tue_beds'      => $number_of_beds, 'tue_dorms'     => $number_of_norms,
            'wed_beds'      => $number_of_beds, 'wed_dorms'     => $number_of_norms,
            'thu_beds'      => $number_of_beds, 'thu_dorms'     => $number_of_norms,
            'fri_beds'      => $number_of_beds, 'fri_dorms'     => $number_of_norms,
            'sat_beds'      => $number_of_beds, 'sat_dorms'     => $number_of_norms,
            'sun_beds'      => $number_of_beds, 'sun_dorms'     => $number_of_norms,

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
                'numeric'                       => 'Bitte Zahl angeben',
                'not_regular_date.required_if'  =>'Dieses Feld Datumsbereich ist erforderlich, wenn das Kontrollkästchen "Nicht regelmäßig" aktiviert ist.',
                'reservation_type.not_in'       => 'Die gewählte Reservierungsart ist ungültig.',
                'not_regular_beds.required'     => 'Die Anzahl der Betten wird benötigt, wenn das Kontrollkästchen "Nicht regulär" aktiviert ist.',
                'not_regular_dorms.required'    => 'Die Anzahl der Matratzenlager wird benötigt, wenn das Kontrollkästchen "Nicht regulär" aktiviert ist.'
            ];
        }
        else {
            return [
                'not_regular_date.required_if'   =>'The Date range field is required when "Not Regular" checkbox is checked.',
                 'not_regular_beds.required_if'  => 'The  No of beds field is required when "Not Regular" checkbox is checked.',
                 'not_regular_dorms.required_if' => 'The  No of dorms field is required when "Not Regular" checkbox is checked.'
            ];
        }
    }
}
