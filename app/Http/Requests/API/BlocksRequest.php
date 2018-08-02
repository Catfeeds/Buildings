<?php

namespace App\Http\Requests\API;

use App\Models\Area;
use App\Models\Block;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BlocksRequest extends FormRequest
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
                    'area_guid.in' => '区域必须存在',
                    'name.not_in' => '该商圈名已存在'
                ];
            case 'update':
                return [
                    'area_guid.in' => '区域必须存在',
                    'name.unique' => '该商圈名已存在'
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
                    'area_guid' => [
                        'required',
                        'max:32',
                        Rule::in(
                            Area::all()->pluck('guid')->toArray()
                        )
                    ],
                    'name' => [
                        'required',
                        'max:32',
                        Rule::notIn(
                            Block::all()->pluck('name')->toArray()
                        )
                    ],
                    'x' => 'required|max:32',
                    'y' => 'required|max:32',
                    'baidu_coord' => 'max:65535',
                ];
            case 'update':
                return [
                    'area_guid' => [
                        'required',
                        'max:32',
                        Rule::in(
                            Area::all()->pluck('guid')->toArray()
                        )
                    ],
                    'name' => [
                        'required',
                        'max:32',
                        Rule::unique('blocks')->ignore($this->route('block')->guid,'guid'),
                    ],
                    'x' => 'required|max:32',
                    'y' => 'required|max:32',
                    'baidu_coord' => 'max:65535',
                ];
            default:
                {
                    return [];
                }
        }
    }
}
