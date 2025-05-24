<?php

namespace Modules\Catalog\Repositories\Dashboard;

use Illuminate\Support\Facades\File;
use Modules\Catalog\Entities\Category;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Modules\Core\Traits\CoreTrait;

class CategoryRepository
{
    use CoreTrait;

    protected $category;
    protected $imgPath;

    function __construct(Category $category)
    {
        $this->category = $category;
        $this->imgPath = public_path('uploads/categories');
    }

    public function getAll($order = 'id', $sort = 'desc')
    {
        $categories = $this->category->orderBy($order, $sort)->get();
        return $categories;
    }

    public function mainCategories($order = 'sort', $sort = 'asc')
    {
        $categories = $this->category->mainCategories()->orderBy($order, $sort)->get();
        return $categories;
    }

    public function getAllActive($order = 'id', $sort = 'desc')
    {
        return $this->category->active()->orderBy($order, $sort)->get();
    }

    public function findById($id)
    {
        $category = $this->category->withDeleted()->find($id);
        return $category;
    }

    public function create($request)
    {
        DB::beginTransaction();

        try {

            $data = [
                //                'image' => $request->image ? path_without_domain($request->image) : url(config('setting.logo')),
                'status' => $request->status ? 1 : 0,
                'show_in_home' => $request->show_in_home ? 1 : 0,
                'color' => $request->color ?? null,
                'sort' => $request->sort ?? 0,
            ];

            if (config('setting.other.toggle_sub_category') == 1)
                $data['category_id'] = ($request->category_id != "null" && $request->category_id != 1) ? $request->category_id : null;
            else
                $data['category_id'] = null;

            if (!is_null($request->image)) {
                $imgName = $this->uploadImage($this->imgPath, $request->image);
                $data['image'] = 'uploads/categories/' . $imgName;
            } else {
                $data['image'] = url(config('setting.logo'));
            }

            $category = $this->category->create($data);
            $this->translateTable($category, $request);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update($request, $id)
    {
        DB::beginTransaction();

        $category = $this->findById($id);
        $restore = $request->restore ? $this->restoreSoftDelte($category) : null;

        try {

            $data = [
                //                'image' => $request->image ? path_without_domain($request->image) : $category->image,
                'status' => $request->status ? 1 : 0,
                'show_in_home' => $request->show_in_home ? 1 : 0,
                'color' => $request->color ?? null,
                'sort' => $request->sort ?? 0,
            ];

            if (config('setting.other.toggle_sub_category') == 1)
                $data['category_id'] = ($request->category_id != "null" && $request->category_id != 1) ? $request->category_id : null;
            else
                $data['category_id'] = null;

            if ($request->image) {
                File::delete($category->image); ### Delete old image
                $imgName = $this->uploadImage($this->imgPath, $request->image);
                $data['image'] = 'uploads/categories/' . $imgName;
            } else {
                $data['image'] = $category->image;
            }

            $category->update($data);
            $this->translateTable($category, $request);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function restoreSoftDelte($model)
    {
        $model->restore();
    }

    public function translateTable($model, $request)
    {
        foreach ($request['title'] as $locale => $value) {
            $model->translateOrNew($locale)->title = $value;
            $model->translateOrNew($locale)->seo_description = $request['seo_description'][$locale];
            $model->translateOrNew($locale)->seo_keywords = $request['seo_keywords'][$locale];
        }

        $model->save();
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {

            $model = $this->findById($id);
            if ($model)
                File::delete($model->image); ### Delete old image

            if ($model->trashed()) :
                $model->forceDelete();
            else :
                $model->delete();
            endif;

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function deleteSelected($request)
    {
        DB::beginTransaction();

        try {

            foreach ($request['ids'] as $id) {
                $model = $this->delete($id);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function QueryTable($request)
    {
        $query = $this->category->with(['translations'])->where(function ($query) use ($request) {
            $query->where('id', 'like', '%' . $request->input('search.value') . '%');
            $query->orWhere(function ($query) use ($request) {
                $query->whereHas('translations', function ($query) use ($request) {
                    $query->where('title', 'like', '%' . $request->input('search.value') . '%');
                    $query->orWhere('slug', 'like', '%' . $request->input('search.value') . '%');
                });
            });
        });

        $query = $this->filterDataTable($query, $request);

        return $query;
    }

    public function filterDataTable($query, $request)
    {
        // Search Categories by Created Dates
        if (isset($request['req']['from']) && $request['req']['from'] != '')
            $query->whereDate('created_at', '>=', $request['req']['from']);

        if (isset($request['req']['to']) && $request['req']['to'] != '')
            $query->whereDate('created_at', '<=', $request['req']['to']);

        if (isset($request['req']['deleted']) && $request['req']['deleted'] == 'only')
            $query->onlyDeleted();

        if (isset($request['req']['deleted']) && $request['req']['deleted'] == 'with')
            $query->withDeleted();

        if (isset($request['req']['status']) && $request['req']['status'] == '1')
            $query->active();

        if (isset($request['req']['status']) && $request['req']['status'] == '0')
            $query->unactive();

        return $query;
    }
}
