<?php

namespace Ml\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Ml\Response\Result;

class BaseRequest extends FormRequest
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
            //
        ];
    }

    /**
     * 自定义检验失败返回结果
     *
     * 验证失败 200
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {

        if ($this->ajax()) {
            $errors = $validator->errors()->messages();
            $result = new Result();
            $result->failed('参数校验失败');
            $result->setModel($errors);

            throw new HttpResponseException(response($result->toArray(), 200));
        } else {

            throw (new ValidationException($validator))
                ->errorBag($this->errorBag)
                ->redirectTo($this->getRedirectUrl());
        }
    }
}
