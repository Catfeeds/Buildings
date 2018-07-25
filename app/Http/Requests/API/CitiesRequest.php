<?php

namespace App\Http\Requests\API;

use App\Models\City;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CitiesRequest extends FormRequest
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
     * 说明: 验证错误信息
     *
     * @return array
     * @author 罗振
     */
    public function messages()
    {
        switch ($this->route()->getActionMethod()) {
            case 'store':
                return [
                    'name.not_in' => '该城市名称已存在'
                ];
            case 'update':
                return [
                    'name.unique' => '该城市名称已存在'
                ];
            default:
                {
                    return [];
                }
        }
    }

    /**
     * 说明: 字段验证
     *
     * @return array
     * @author 罗振
     */
    public function rules()
    {
        switch ($this->route()->getActionMethod()) {
            case 'store':
                return [
                    'name' => [
                        'required',
                        'max:32',
                        Rule::notIn(
                            City::all()->pluck('name')->toArray()
                        )

                    ]
                ];
            case 'update':
                return [
                    'name' => [
                        'required',
                        'max:32',
                        Rule::unique('cities')->ignore($this->route('city')->guid,'guid'),
                    ],
                ];
            default:
                {
                    return [];
                }
        }
    }
}
