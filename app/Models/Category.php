<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Category extends Model
{
    use HasFactory, HasUuids;

    // penamaan tabel di database
    protected $table = 'categories';

    // kolom yang dapat diisi
    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
        'parent_id',
        'level',
        'sort_order',
        'is_active',
    ];

    // cast tipe data
    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi ke kategori parent
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Relasi ke kategori child
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    // Relasi untuk semua descendants (recursive)
    public function allDescendants()
    {
        return $this->children()->with('allDescendants');
    }

    public function descendantsIdsRecursive()
    {
        $ids = collect([$this->id]);

        foreach ($this->children as $child) {
            $ids = $ids->merge($child->descendantsIdsRecursive());
        }

        return $ids->unique()->values();
    }

    // Relasi ke produk
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Scope untuk kategori tingkat pertama
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id')->where('is_active', true);
    }

    // Scope untuk kategori aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Mendapatkan breadcrumb path
    public function getBreadcrumbs()
    {
        $breadcrumbs = collect([$this]);
        $parent = $this->parent;

        while ($parent) {
            $breadcrumbs->prepend($parent);
            $parent = $parent->parent;
        }

        return $breadcrumbs;
    }

    // Mendapatkan full path name (e.g., "Electronics > Phones > Smartphones")
    public function getFullPath()
    {
        return $this->getBreadcrumbs()
            ->pluck('name')
            ->implode(' > ');
    }

    // Update level berdasarkan parent
    public static function boot()
    {
        parent::boot();

        static::saving(function ($category) {
            if ($category->parent_id) {
                $parent = self::find($category->parent_id);
                $category->level = ($parent->level ?? 1) + 1;
            } else {
                $category->level = 1;
            }
        });
    }
}
