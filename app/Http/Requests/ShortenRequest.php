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
            'url' => 'required|url',
            'custom_alias' => 'alpha_dash|unique:urls,string_id|min:3',
            'is_private' => 'required|boolean'
        ];
    }

    public function all()
    {
        $input = parent::all();
        $input['is_private'] = $input['is_private'] === 'true' ? 1 : 0;
        $input['url'] = $input['protocol_select'] . $input['url'];

        return $input;
    }
}
