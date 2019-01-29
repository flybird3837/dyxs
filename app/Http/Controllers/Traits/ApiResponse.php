<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/15/015
 * Time: 16:52
 */

namespace App\Http\Controllers\Traits;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

trait ApiResponse
{
    /**
     * @param $model
     * @param TransformerAbstract $transformer
     * @return array
     */
    public function item($model, TransformerAbstract $transformer)
    {
        $manager = app(Manager::class);
        $resource = new Item($model, $transformer);

        return $manager->createData($resource)->toArray();
    }

    /**
     * @param $model
     * @param TransformerAbstract $transformer
     * @return array
     */
    public function collection($model, TransformerAbstract $transformer)
    {
        $manager = app(Manager::class);
        $resource = new Collection($model, $transformer);
        return $manager->createData($resource)->toArray();
    }

    /**
     * @param LengthAwarePaginator $paginator
     * @param TransformerAbstract $transformer
     * @return array
     */
    public function paginator(LengthAwarePaginator $paginator, TransformerAbstract $transformer)
    {
        $manager = app(Manager::class);
        $data = $paginator->getCollection();
        $resource = new Collection($data, $transformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        return $manager->createData($resource)->toArray();
    }
}