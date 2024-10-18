<?php

return [
    'resource' => [
        'navigation_icon' => 'heroicon-o-rectangle-stack',
        'navigation_group' => 'Nội dung',
        'navigation_label' => 'Danh mục',
        'model_label' => 'Danh mục',
        'plural_model_label' => 'Danh mục',
    ],
    'form' => [
        'label' => [
            'name' => 'Tên danh mục',
            'slug' => 'Đường dẫn',
            'description' => 'Mô tả',
            'parent_id' => 'Danh mục cha',
            'category_type' => 'Loại danh mục',
            'status' => 'Trạng thái'
        ],
        'placeholder' => [
            'name' => 'Nhập tên danh mục...',
            'slug' => 'Tự động tạo từ tên danh mục...',
            'description' => 'Nhập mô tả...',
            'parent_id' => 'Danh mục gốc...',
            'category_type' => 'Chọn loại danh mục...',
        ],
        'options' => [
            'product' => 'Sản phẩm',
            'post' => 'Bài viết',
            'active' => 'Hiển thị',
            'inactive' => 'Ẩn'
        ],
        'icons' => [
            'active' => 'heroicon-o-eye',
            'inactive' => 'heroicon-o-eye-slash'
        ],
        'colors' => [
            'active' => 'success',
            'inactive' => 'danger'
        ]
    ],
    'table' => [
        'label' => [
            'name' => 'Tên danh mục',
            'slug' => 'Đường dẫn',
            'parent_id' => 'Danh mục cha',
            'category_type' => 'Loại danh mục',
            'status' => 'Trạng thái',
            'created_at' => 'Ngày đăng',
        ],
        'placeholder' => [
            'parent_id' => 'Danh mục gốc',
        ],
        'options' => [
            'product' => 'Sản phẩm',
            'post' => 'Bài viết',
            'active' => 'Hiển thị',
            'inactive' => 'Ẩn'
        ],
        'icons' => [
            'active' => 'heroicon-o-eye',
            'inactive' => 'heroicon-o-eye-slash'
        ],
    ],
    'filter' => [
        'label' => [
            'created_at' => 'Ngày tạo',
            'created_from' => 'Từ ngày',
            'created_until' => 'Đến ngày',
            'status' => 'Trạng thái',
        ],
        'options' => [
            'active' => 'Hiển thị',
            'inactive' => 'Ẩn',
        ],
    ],
];
