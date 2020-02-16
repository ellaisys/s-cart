<?php
#app/Http/Admin/Controllers/AdminUrlConfigController.php
namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AdminConfig;
use App\Admin\AdminConfigTrait;
class AdminUrlConfigController extends Controller
{
    use AdminConfigTrait;
    public function index()
    {
        $data = [
            'title' => trans('link.config_manager.title'),
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

        $obj = (new AdminConfig)->where('code', 'env')
            ->orderBy('sort', 'desc')
            ->get();
        $data['configs'] = $obj;

        return view('admin.screen.url_config')
            ->with($data);
    }

}
