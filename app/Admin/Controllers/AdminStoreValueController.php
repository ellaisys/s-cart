<?php
#app/Http/Admin/Controllers/AdminStoreValueController.php
namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AdminConfig;
use Illuminate\Http\Request;
use App\Admin\AdminConfigTrait;
class AdminStoreValueController extends Controller
{
    use AdminConfigTrait;
    public function index()
    {

        $data = [
            'title' => trans('store_value.admin.list'),
            'sub_title' => '',
            'icon' => 'fa fa-indent',
            'menuRight' => [],
            'menuLeft' => [],
            'topMenuRight' => [],
            'topMenuLeft' => [],
            'menuSort' => '',
            'scriptSort' => '',
            'listTh' => '',
            'dataTr' => '',
            'pagination' => '',
            'resultItems' => '',
            'url_delete_item' => '',
        ];

        $obj = (new AdminConfig)->whereIn('code', ['config', 'display'])
                ->orderBy('sort', 'desc')
                ->get()
                ->groupBy('code');
        $data['configs'] = $obj;

        return view('admin.screen.store_value')
            ->with($data);
    }

}
