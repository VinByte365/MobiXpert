<?php

namespace App\DataTables;

use App\Models\Review;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ReviewDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('product_name', function($review) {
                return $review->product->name;
            })
            ->addColumn('user_name', function($review) {
                return $review->user->name;
            })
            ->addColumn('action', function($review) {
                return '<button type="button" 
                        class="btn btn-sm btn-danger delete-review" 
                        data-id="'.$review->review_id.'" 
                        data-url="'.route('admin.reviews.destroy', $review->review_id).'">
                        <i class="fas fa-trash"></i> Delete
                </button>';
            })
            ->rawColumns(['action'])
            ->setRowId('review_id');
    }

    public function query(Review $model): QueryBuilder
    {
        return $model->newQuery()->with(['product', 'user']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('reviews-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Blfrtip')
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
            Column::make('review_id')->title('ID'),
            Column::computed('product_name')->title('Product'),
            Column::computed('user_name')->title('User'),
            Column::make('rating'),
            Column::make('comment'),
            Column::make('created_at')->title('Date'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Reviews_' . date('YmdHis');
    }
}