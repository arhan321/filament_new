<?php

namespace App\Filament\Admin\Resources\Products;

use App\Models\Product;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use App\Filament\Admin\Resources\Products\Pages\EditProduct;
use App\Filament\Admin\Resources\Products\Pages\ListProducts;
use App\Filament\Admin\Resources\Products\Pages\CreateProduct;
use App\Filament\Admin\Resources\Products\Schemas\ProductForm;
use App\Filament\Admin\Resources\Products\Tables\ProductsTable;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    // -----------------------------
    //  NAVIGATION FIX (NO MORE TYPE ERRORS)
    // -----------------------------

    // Icon (return string, aman)
    public static function getNavigationIcon(): string|null
    {
        return 'heroicon-o-adjustments-horizontal';
    }

    // Navigation group
    public static function getNavigationGroup(): string|null
    {
        return 'Products';
    }

    // Sort order
    public static function getNavigationSort(): ?int
    {
        return 1;
    }
    public static function getModelLabel(): string
    {
        return 'Barang';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Barang';
    }
    // Record title
    protected static ?string $recordTitleAttribute = 'Product';

    // -----------------------------
    //  FILAMENT SCHEMAS
    // -----------------------------

    public static function form(Schema $schema): Schema
    {
        return ProductForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }
}
