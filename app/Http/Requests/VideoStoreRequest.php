<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class VideoStoreRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'data' => 'required|file|mimes:mp4,mpeg'
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $errors = $validator->errors()->toArray();
        if (isset($errors['data']) && in_array('The data must be a file of type: mp4, mpeg.', $errors['data'])) {
            throw new HttpResponseException(response()->json([
                'error' => [
                    'message' => 'The data must be a file of type: mp4, mpeg.',
                ],
            ], Response::HTTP_UNSUPPORTED_MEDIA_TYPE));
        }

        throw new HttpResponseException(response()->json($validator->errors(), Response::HTTP_BAD_REQUEST));
    }
}
