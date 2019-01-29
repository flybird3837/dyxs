<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/30/030
 * Time: 15:22
 */

namespace App\Http\Transformers;


use App\Models\Site;
use League\Fractal\TransformerAbstract;

class SiteTransformer extends TransformerAbstract
{
    public function transform(Site $site) {
        return $site->toArray();
    }

}