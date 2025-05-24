<?php

namespace Modules\Catalog\ViewComposers\Dashboard;

use Modules\Catalog\Repositories\Dashboard\CategoryRepository as Category;
use Illuminate\View\View;
use Cache;

class CategoryComposer
{
    public $categories;
    public $sharedActiveCategories;

    public function __construct(Category $category)
    {
        $this->categories = $category->mainCategories();
        $this->sharedActiveCategories = $category->getAllActive();
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with(['mainCategories' => $this->categories, 'sharedActiveCategories' => $this->sharedActiveCategories]);
    }
}
