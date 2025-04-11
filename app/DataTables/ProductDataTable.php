<?php

namespace App\DataTables;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProductDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('image', function($product) {
                $imageUrl = $product->image_path ? asset('storage/'.$product->image_path) : asset('images/default-product.png');
                return '<img src="'.$imageUrl.'" alt="'.$product->name.'" class="img-thumbnail" width="50">';
            })
            ->addColumn('action', function($product) {
                return '<div class="btn-group">
                    <a href="'.route('admin.products.edit', $product->product_id).'" 
                       class="btn btn-sm btn-primary edit-product" 
                       data-id="'.$product->product_id.'">
                       <i class="fas fa-edit"></i> Edit
                    </a>
                    <button type="button" 
                            class="btn btn-sm btn-danger delete-product" 
                            data-id="'.$product->product_id.'" 
                            data-name="'.$product->name.'"
                            data-url="'.route('admin.products.destroy', $product->product_id).'">
                            <i class="fas fa-trash"></i> Delete
                    </button>
                </div>';
            })
            ->rawColumns(['image', 'action'])
            ->setRowId('product_id');
    }

    public function query(Product $model): QueryBuilder
    {
        return $model->newQuery()->with(['brand', 'priceRange'])->select([
            'product_id',
            'name',
            'description',
            'price',
            'stock_quantity',
            'image_path',
            'created_at',
            'brand_id',
            'price_range_id'
        ]);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('products-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Blfrtip') // Changed from 'Bfrtip' to 'Blfrtip' to add length menu
                    ->orderBy(1)
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload'),
                        Button::make('colvis')->text('Columns'),
                    ])
                    ->parameters([
                        'lengthMenu' => [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
                        'buttons' => [
                            'dom' => [
                                'button' => [
                                    'className' => 'btn btn-sm'
                                ]
                            ],
                            'buttons' => [
                                'className' => 'btn btn-secondary'
                            ],
                        ]
                    ]);
    }

    public function getColumns(): array
    {
        return [
            Column::computed('image')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
            Column::make('product_id')->title('ID'),
            Column::make('name'),
            Column::make('description'),
            Column::make('price'),
            Column::make('stock_quantity')->title('Stock'),
            Column::make('created_at'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(120)
                  ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Products_' . date('YmdHis');
    }
}
