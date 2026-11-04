<?php
namespace App\Services;

use App\Models\CaseCategory;
use Illuminate\Database\Eloquent\Builder;

class CaseCategoryService {


    public function getCategories(array $filters = []) {
        return CaseCategory::query()
            ->when(!empty($filters['search']), function (Builder $q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%');
            })
            ->when(isset($filters['status']), function (Builder $q) use ($filters) {
                $q->where('status', $filters['status']);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);
    }

    public function createCategory(array $data): CaseCategory {
        return CaseCategory::create($data);
    }


    public function updateCategory(CaseCategory $category, array $data): CaseCategory {
        $category->update($data);
        return $category->fresh();
    }


    public function deleteCategory(CaseCategory $category): bool {
        return $category->delete();
    }
    
}