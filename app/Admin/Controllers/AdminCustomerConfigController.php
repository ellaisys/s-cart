<?php
#app/Http/Admin/Controllers/AdminCustomerConfigController.php
namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AdminConfig;
use Illuminate\Http\Request;
use App\Admin\AdminConfigTrait;
class AdminCustomerConfigController extends Controller
{
    use AdminConfigTrait;
    public function index()
    {

        $data = [
            'title' => trans('customer.config_manager.title'),
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

        $obj = (new AdminConfig)->where('code', 'customer')->orderBy('sort', 'desc')->get();
        $data['configs'] = $obj;


        return view('admin.screen.customer_config')
            ->with($data);
    }

}
