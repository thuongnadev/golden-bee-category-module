<?php

declare(strict_types=1);

namespace Modules\Category\App\Filament\Resources\CategoryResource\Forms;

use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Illuminate\Support\Str;
use Filament\Forms\Set;
use Illuminate\Validation\Rule;

class CategoryForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(4)
                    ->schema([
                        self::basicInfoSection()->columnSpan(3),
                        self::categoryDetailsSection()->columnSpan(1),
                    ]),
            ]);
    }

    private static function basicInfoSection(): Section
    {
        return Section::make()
            ->schema([
                self::nameInput(),
                self::slugInput(),
                self::descriptionInput(),
            ])
            ->columns(2);
    }

    private static function nameInput(): TextInput
    {
        return TextInput::make('name')
            ->label(__('category::category.form.label.name'))
            ->placeholder(__('category::category.form.placeholder.name'))
            ->required()
            ->rules(['max:255'])
            ->live(onBlur: true)
            ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                if (($get('slug') ?? '') !== Str::slug($old)) {
                    return;
                }
                $set('slug', Str::slug($state));
            })
            ->columnSpan(1);
    }

    private static function slugInput(): TextInput
    {
        return TextInput::make('slug')
            ->label(__('category::category.form.label.slug'))
            ->placeholder(__('category::category.form.placeholder.slug'))
            ->required()
            ->rules([function (Get $get) {
                $categoryId = $get('id');
                return $categoryId
                    ? Rule::unique('categories', 'slug')->ignore($categoryId)
                    : Rule::unique('categories', 'slug');
            }])
            ->columnSpan(1);
    }

    private static function descriptionInput(): Textarea
    {
        return Textarea::make('description')
            ->label(__('category::category.form.label.description'))
            ->placeholder(__('category::category.form.placeholder.description'))
            ->rows(3)
            ->columnSpan(2);
    }

    private static function categoryDetailsSection(): Section
    {
        return Section::make()
            ->schema([
                self::parentCategoryInput(),
                self::categoryTypeInput(),
                self::statusToggle(),
            ])
            ->columnSpan(1);
    }

    private static function parentCategoryInput(): SelectTree
    {
        return SelectTree::make('parent_id')
            ->label(__('category::category.form.label.parent_id'))
            ->placeholder(__('category::category.form.placeholder.parent_id'))
            ->relationship('parent', 'name', 'parent_id')
            ->enableBranchNode()
            ->nullable();
    }

    private static function categoryTypeInput(): Select
    {
        return Select::make('category_type')
            ->label(__('category::category.form.label.category_type'))
            ->placeholder(__('category::category.form.placeholder.category_type'))
            ->rules(['required'])
            ->options([
                'product' => __('category::category.form.options.product'),
                'post' => __('category::category.form.options.post'),
            ]);
    }

    private static function statusToggle(): ToggleButtons
    {
        return ToggleButtons::make('status')
            ->label(__('category::category.form.label.status'))
            ->inline()
            ->default('1')
            ->options([
                '1' => __('category::category.form.options.active'),
                '0' => __('category::category.form.options.inactive'),
            ])
            ->icons([
                '1' => __('category::category.form.icons.active'),
                '0' => __('category::category.form.icons.inactive'),
            ])
            ->colors([
                '1' => __('category::category.form.colors.active'),
                '0' => __('category::category.form.colors.inactive'),
            ]);
    }
}
