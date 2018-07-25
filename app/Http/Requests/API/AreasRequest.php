<?php

namespace App\Http\Requests\API;

use App\Models\Area;
use App\Models\City;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AreasRequest extends FormRequest
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
                    'city_guid.in' => '城市必须存在',
                    'name.not_in' => '该区域名已存在'
                ];
            case 'update':
                return [
                    'city_guid.in' => '城市必须存在',
                    'name.unique' => '该区域名已存在'
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
                    'city_guid' => [
                        'required',
                        'max:32',
                        Rule::in(
                            City::all()->pluck('guid')->toArray()
                        )
                    ],
                    'name' => [
                        'required',
                        'max:32',
                        Rule::notIn(
                            Area::all()->pluck('name')->toArray()
                        )
                    ]
                ];
            case 'update':
                return [
                    'city_guid' => [
                        'required',
                        'max:32',
                        Rule::in(
                            City::all()->pluck('guid')->toArray()
                        )
                    ],
                    'name' => [
                        'required',
                        'max:32',
                        Rule::unique('areas')->ignore($this->route('area')->guid,'guid'),
                    ]
                ];
            default:
                {
                    return [];
                }
        }
    }
}
