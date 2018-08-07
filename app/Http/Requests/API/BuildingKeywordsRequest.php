<?php

namespace App\Http\Requests\API;

use App\Models\Building;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BuildingKeywordsRequest extends FormRequest
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

    // 提示信息
    public function messages()
    {
        switch ($this->route()->getActionMethod()) {
            case 'store':
                return [
                    'building_guid.in' => '楼盘必须存在',
                ];
            case 'update':
                return [

                ];
            default:
                {
                    return [];
                }
        }
    }

    // 字段验证
    public function rules()
    {
        switch ($this->route()->getActionMethod()) {
            case 'store':
                return [
                    'building_guid' => [
                        'required',
                        'max:32',
                        Rule::in(
                            Building::all()->pluck('guid')->toArray()
                        )
                    ],
                    'keywords' => 'required'
                ];
            case 'update':
                return [

                ];
            default:
                {
                    return [];
                }
        }
    }
}
