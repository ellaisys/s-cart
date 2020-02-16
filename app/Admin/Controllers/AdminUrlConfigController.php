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
            'subTitle' => '',
            'icon' => 'fa fa-indent',
            'menuRight' => [],
            'menuLeft' => [],
            'topMenuRight' => [],
            'topMenuLeft' => [],
            'urlDeleteItem' => '',
            'removeList' => 0, // 1 - Enable function delete list item
            'buttonRefresh' => 0, // 1 - Enable button refresh
            'buttonSort' => 0, // 1 - Enable button sort
        ];

        $obj = (new AdminConfig)->where('code', 'env')
            ->orderBy('sort', 'desc')
            ->get();
        $data['configs'] = $obj;

        return view('admin.screen.url_config')
            ->with($data);
    }

}
