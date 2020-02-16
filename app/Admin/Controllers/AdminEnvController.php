<?php
#app/Http/Admin/Controllers/AdminEnvController.php
namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AdminConfig;
use App\Models\ShopCurrency;
use App\Models\ShopLanguage;
use App\Admin\AdminConfigTrait;
use Illuminate\Http\Request;

class AdminEnvController extends Controller
{
    use AdminConfigTrait;
    
    public function index()
    {
        $languages = ShopLanguage::getCodeActive();
        $currencies = ShopCurrency::getCodeActive();
        $data = [
            'title' => trans('env.title'),
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

        foreach (timezone_identifiers_list() as $key => $value) {
            $timezones[$value] = $value;
        }
        $data['timezones'] = $timezones;
        $data['languages'] = $languages;
        $data['currencies'] = $currencies;
        $obj = (new AdminConfig)->where('code', 'env')
            ->orderBy('sort', 'desc')->get();
        $data['configs'] = $obj;

        return view('admin.screen.env')
            ->with($data);
    }
}
