<?php

declare(strict_types=1);

namespace Modules\Category\App\Filament\Resources\CategoryResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Modules\Category\App\Filament\Resources\CategoryResource\Tables\Actions\CategoryAction;
use Modules\Category\App\Filament\Resources\CategoryResource\Tables\BulkActions\CategoryBulkAction;
use Modules\Category\App\Filament\Resources\CategoryResource\Tables\Filters\CategoryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class CategoryTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('category::category.table.label.name'))
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function ($state, $record) {
                        $depth = $record->depth ?? 0;

                        $prefix = '';
                        
                        if ($depth > 0) {
                            $prefix .= str_repeat('— ', $depth);
                        }

                        return new HtmlString($prefix . "<span>$state</span>");
                    }),
                TextColumn::make('parent.name')
                    ->label(__('category::category.table.label.parent_id'))
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        return $record->parent ? $record->parent->name : __('category::category.table.placeholder.parent_id');
                    }),
                TextColumn::make('category_type')
                    ->label(__('category::category.table.label.category_type'))
                    ->searchable()
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        return __('category::category.table.options.' . $record->category_type);
                    }),
                ToggleColumn::make('status')
                    ->label(__('category::category.table.label.status'))
                    ->tooltip(function ($record) {
                        return $record->status
                            ? __('category::category.table.options.active')
                            : __('category::category.table.options.inactive');
                    })
                    ->onIcon(__('category::category.table.icons.active'))
                    ->offIcon(__('category::category.table.icons.inactive'))
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('category::category.table.label.created_at'))
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters(CategoryFilter::filter())
            ->actions(CategoryAction::action())
            ->bulkActions(CategoryBulkAction::bulkActions())
            ->modifyQueryUsing(function (Builder $query) {
                return $query->select('categories.*')
                    ->selectRaw('
                        (
                            WITH RECURSIVE category_tree(id, path, depth) AS (
                                SELECT id, CAST(id AS CHAR(200)), 0
                                FROM categories
                                WHERE parent_id IS NULL
                                UNION ALL
                                SELECT c.id, CONCAT(ct.path, ",", c.id), ct.depth + 1
                                FROM categories c
                                JOIN category_tree ct ON c.parent_id = ct.id
                            )
                            SELECT path FROM category_tree WHERE id = categories.id
                        ) as tree_path,
                        (
                            WITH RECURSIVE category_tree(id, depth) AS (
                                SELECT id, 0
                                FROM categories
                                WHERE parent_id IS NULL
                                UNION ALL
                                SELECT c.id, ct.depth + 1
                                FROM categories c
                                JOIN category_tree ct ON c.parent_id = ct.id
                            )
                            SELECT depth FROM category_tree WHERE id = categories.id
                        ) as depth,
                        (
                            SELECT COUNT(*) = 0
                            FROM categories as c
                            WHERE c.parent_id = categories.parent_id AND c.id > categories.id
                        ) as is_last_child,
                        (
                            SELECT COUNT(*) > 0
                            FROM categories as c
                            WHERE c.parent_id = categories.id
                        ) as has_children
                    ')
                    ->orderByRaw('tree_path');
            });
    }
}
