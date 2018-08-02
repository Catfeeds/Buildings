<?php

namespace App\Http\Requests\API;

use App\Models\Area;
use App\Models\Block;
use App\Models\Building;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BuildingsRequest extends FormRequest
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
                    'name.not_in' => '该楼盘名已存在',
                    'area_guid.in' => '区域必须存在',
                    'block_guid.in' => '商圈必须存在',
                ];
            case 'update':
                return [
                    'name.unique' => '该楼盘名已存在',
                    'area_guid.in' => '区域必须存在',
                    'block_guid.in' => '商圈必须存在',
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
                            Building::all()->pluck('name')->toArray()
                        )
                    ],
                    'gps' => 'required',
                    'type' => 'required|numeric|between:1,3',
                    'area_guid' => [
                        'required',
                        'max:32',
                        Rule::in(
                            Area::all()->pluck('guid')->toArray()
                        )
                    ],
                    'block_guid' => [
                        'required',
                        'max:32',
                        Rule::in(
                            Block::all()->pluck('guid')->toArray()
                        )
                    ],
                    'address' => 'required|max:128',
                    'developer' => 'nullable|max:128',
                    'years' => 'nullable|numeric|max:99999999999',
                    'acreage' => 'nullable|numeric|max:99999999999',
                    'building_block_num' => 'nullable|numeric|max:10000',
                    'parking_num' => 'nullable|numeric|max:10000',
                    'parking_fee' => 'nullable|numeric|max:10000',
                    'greening_rate' => 'nullable|numeric|max:100',

                    'company' => 'array',
                    'album' => 'array',
                    'big_album' => 'array',
                    'describe' => 'max:65535',

                    'building_block' => 'required|array',    // 楼盘
                ];
            case 'update':
                return [
                    'name' => [
                        'required',
                        'max:32',
                        Rule::unique('buildings')->ignore($this->route('building')->guid,'guid'),
                    ],
                    'gps' => 'required',
                    'type' => 'required|numeric|between:1,3',
                    'area_guid' => [
                        'required',
                        'max:32',
                        Rule::in(
                            Area::all()->pluck('guid')->toArray()
                        )
                    ],
                    'block_guid' => [
                        'required',
                        'max:32',
                        Rule::in(
                            Block::all()->pluck('guid')->toArray()
                        )
                    ],
                    'address' => 'required|max:128',
                    'developer' => 'nullable|max:128',
                    'years' => 'nullable|numeric|max:99999999999',
                    'acreage' => 'nullable|numeric|max:99999999999',
                    'building_block_num' => 'nullable|numeric|max:10000',
                    'parking_num' => 'nullable|numeric|max:10000',
                    'parking_fee' => 'nullable|numeric|max:10000',
                    'greening_rate' => 'nullable|numeric|max:100',

                    'company' => 'array',
                    'album' => 'array',
                    'big_album' => 'array',
                    'describe' => 'max:65535',
                ];
            default:
                {
                    return [];
                }
        }
    }
}
