<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShortenRequest extends FormRequest
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
            'protocol_select' => 'required|in:http://,https://',
            'url_with_protocol' => 'required|url',
            'is_private' => 'required|boolean'
        ];
    }

    public function all()
    {
        $input = parent::all();
        $input['is_private'] = $input['is_private'] === 'true' ? 1 : 0;
        return $input;
    }
}
