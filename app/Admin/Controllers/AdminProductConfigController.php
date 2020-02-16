<?php
#app/Http/Admin/Controllers/AdminProductConfigController.php
namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AdminConfig;
use Illuminate\Http\Request;
use App\Admin\AdminConfigTrait;
class AdminProductConfigController extends Controller
{
    use AdminConfigTrait;
    public function index()
    {

        $data = [
            'title' => trans('product.config_manager.title'),
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

        $obj = (new AdminConfig)->where('code', 'product')->orderBy('sort', 'desc')->get();
        $data['configs'] = $obj;

        return view('admin.screen.product_config')
            ->with($data);
    }

}
