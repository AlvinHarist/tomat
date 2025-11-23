# Struktur Kategori Bertingkat

Sistem kategori telah diupdate untuk mendukung struktur bertingkat (hierarchical categories).

## Fitur

### 1. Kolom Database

-   `name` - Nama kategori
-   `slug` - Slug untuk URL
-   `description` - Deskripsi kategori
-   `parent_id` - ID kategori parent (null untuk kategori tingkat pertama)
-   `level` - Tingkat kedalaman (1 untuk top-level, 2 untuk sub, dst)
-   `sort_order` - Urutan tampilan
-   `is_active` - Status aktif/nonaktif

### 2. Relasi Model

#### Parent Relationship

```php
$category->parent(); // Mendapatkan kategori parent
```

#### Children Relationship

```php
$category->children(); // Mendapatkan semua child categories
$category->allDescendants(); // Mendapatkan semua descendants (recursive)
```

#### Products Relationship

```php
$category->products(); // Produk dalam kategori ini
```

### 3. Helper Methods

#### Breadcrumbs

```php
$category = Category::find($id);
$breadcrumbs = $category->getBreadcrumbs(); // Collection dari semua ancestors + diri sendiri
```

#### Full Path

```php
$fullPath = $category->getFullPath(); // "Electronics > Phones > Smartphones"
```

### 4. Scopes

#### Top Level Categories

```php
$topCategories = Category::topLevel()->get(); // Hanya kategori tingkat pertama yang aktif
```

#### Active Categories

```php
$activeCategories = Category::active()->get(); // Semua kategori yang aktif
```

## Contoh Data Hierarchical

```
Elektronik (Level 1)
├── Smartphone (Level 2)
│   ├── Apple (Level 3)
│   ├── Samsung (Level 3)
│   └── Xiaomi (Level 3)
├── Laptop & Komputer (Level 2)
│   ├── Gaming (Level 3)
│   └── Office (Level 3)
└── Aksesori (Level 2)

Fashion (Level 1)
├── Pakaian Pria (Level 2)
│   ├── Kemeja (Level 3)
│   ├── Celana (Level 3)
│   └── Jaket (Level 3)
├── Pakaian Wanita (Level 2)
│   ├── Dress (Level 3)
│   ├── Blouse (Level 3)
│   └── Rok (Level 3)
└── Sepatu (Level 2)

Rumah Tangga (Level 1)
├── Dapur (Level 2)
│   ├── Panci & Wajan (Level 3)
│   └── Peralatan Masak (Level 3)
└── Kamar Tidur (Level 2)

Olahraga & Outdoor (Level 1)
```

## Query Examples

### Mendapatkan kategori dengan semua children

```php
$electronics = Category::with('children', 'children.children')->where('name', 'Elektronik')->first();
```

### Mendapatkan kategori aktif bersama parent-nya

```php
$category = Category::active()->with('parent')->find($id);
```

### Mendapatkan breadcrumb path

```php
$smartphone = Category::where('name', 'Smartphone')->first();
echo $smartphone->getFullPath(); // Output: "Elektronik > Smartphone"
```

### Mendapatkan produk dari kategori dan semua sub-kategorinya

```php
$parentId = 'elektronik-id';
$products = Product::whereIn('category_id', function($query) use ($parentId) {
    $query->select('id')
        ->from('categories')
        ->where('parent_id', $parentId)
        ->orWhere('id', $parentId);
})->get();
```

## Notes

-   `level` otomatis di-update saat kategori disimpan (menggunakan boot method)
-   Foreign key dari `parent_id` menggunakan cascade on delete
-   Kategori dapat di-nonaktifkan tanpa menghapus data dengan menggunakan kolom `is_active`
