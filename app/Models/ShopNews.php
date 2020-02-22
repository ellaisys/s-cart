<?php
#app/Models/ShopNews.php
namespace App\Models;

use App\Models\ShopNewsDescription;
use Illuminate\Database\Eloquent\Model;
use Cache;
class ShopNews extends Model
{
    public $table = SC_DB_PREFIX.'shop_news';
    protected $guarded = [];
    protected $connection = SC_CONNECTION;
    protected $appends = [
        'title',
        'keyword',
        'description',
        'content',
    ];

    public function descriptions()
    {
        return $this->hasMany(ShopNewsDescription::class, 'shop_news_id', 'id');
    }
    public function getItemsNews($limit = null, $opt = null)
    {
        $query = (new ShopNews)->where('status', 1)->sort();
        if (!(int) $limit) {
            return $query->get();
        } else
        if ($opt == 'paginate') {
            return $query->paginate((int) $limit);
        } else {
            return $query->limit($limit)->get();
        }

    }
/*
Get thumb
 */
    public function getThumb()
    {
        return sc_image_get_path_thumb($this->image);
    }

/*
Get image
 */
    public function getImage()
    {
        return sc_image_get_path($this->image);

    }
/**
 * [getUrl description]
 * @return [type] [description]
 */
    public function getUrl()
    {
        return route('news.detail', ['alias' => $this->alias]);
    }

    //Fields language
    public function getTitle()
    {
        return $this->processDescriptions()['title'] ?? '';
    }
    public function getKeyword()
    {
        return $this->processDescriptions()['keyword'] ?? '';
    }
    public function getDescription()
    {
        return $this->processDescriptions()['description'] ?? '';
    }
    public function getContent()
    {
        return $this->processDescriptions()['content'] ?? '';
    }
//Attributes
    public function getTitleAttribute()
    {
        return $this->getTitle();
    }
    public function getKeywordAttribute()
    {
        return $this->getKeyword();

    }
    public function getDescriptionAttribute()
    {
        return $this->getDescription();

    }

    public function getContentAttribute()
    {
        return $this->getContent();

    }
//Scort
    public function scopeSort($query, $column = null)
    {
        $column = $column ?? 'sort';
        return $query->orderBy($column, 'asc')->orderBy('id', 'desc');
    }

    public function processDescriptions()
    {
        return $this->descriptions->keyBy('lang')[sc_get_locale()] ?? [];
    }


/**
     * Get list news
     *
     * @param   array  $arrOpt
     * Example: ['status' => 1, 'top' => 1]
     * @param   array  $arrSort
     * Example: ['sortBy' => 'id', 'sortOrder' => 'asc']
     * @param   array  $arrLimit  [$arrLimit description]
     * Example: ['step' => 0, 'limit' => 20]
     * @return  [type]             [return description]
     */
    public function getList($arrOpt = [], $arrSort = [], $arrLimit = [])
    {
        if(empty($arrOpt) && empty($arrSort) && empty($arrLimit)) {
            return $this->processListFull();
        } else {
            return $this->processList($arrOpt = [], $arrSort = [], $arrLimit = []);
        }
    }

    /**
     * Process get list news
     *
     * @param   array  $arrSort   [$arrSort description]
     * @param   array  $arrLimit  [$arrLimit description]
     * @param   array  $arrOpt    [$arrOpt description]
     *
     * @return  collect
     */
    private function processList($arrOpt = [], $arrSort = [], $arrLimit = [])
    {
        $sortBy = $arrSort['sortBy'] ?? null;
        $sortOrder = $arrSort['sortOrder'] ?? 'asc';
        $step = $arrLimit['step'] ?? 0;
        $limit = $arrLimit['limit'] ?? 0;

        $data = $this->sort($sortBy, $sortOrder);
        if(count($arrOpt = [])) {
            foreach ($arrOpt as $key => $value) {
                $data = $data->where($key, $value);
            }
        }
        if((int)$limit) {
            $start = $step * $limit;
            $data = $data->offset((int)$start)->limit((int)$limit);
        }
        $data = $data->get()->groupBy('id');

        return $data;
    }

    /**
     * Process list full news
     *
     * @return  [type]  [return description]
     */
    private function processListFull()
    {
        if(sc_config('cache_status') && sc_config('cache_news')) {
            if (!Cache::has('cache_news')) {
                $listFullNews = $this->processList();
                Cache::put('cache_news', $listFullNews, $seconds = sc_config('cache_time', 0)?:600);
            }
            return Cache::get('cache_news');
        } else {
            return $this->processList();
        }
    }

}
