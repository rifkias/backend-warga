<?php

namespace App\Http\Requests\Config;

use Illuminate\Foundation\Http\FormRequest;

class RolePermissionRequest extends FormRequest
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
        $rules = [
            'role_id'=> ['required',"exists:rules,id"],
            'module_id'=> ['required',"exists:modules,id"],
            'pcreate'=>["numeric","digits:1"],
            'pread'=>["numeric","digits:1"],
            'pupdate'=>["numeric","digits:1"],
            'pdelete'=>["numeric","digits:1"],
            'pupload'=>["numeric","digits:1"],
            'pcustom1'=>["numeric","digits:1"],
            'pcustom2'=>["numeric","digits:1"],
            'pcustom3'=>["numeric","digits:1"],
            'pcustom4'=>["numeric","digits:1"],
            'pcustom5'=>["numeric","digits:1"],
        ];
        return $rules;
    }
}
