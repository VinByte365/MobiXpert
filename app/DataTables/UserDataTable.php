<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('status_badge', function($user) {
                $badgeClass = $user->status == 'active' ? 'bg-success' : 'bg-danger';
                return '<span class="badge ' . $badgeClass . '">' . ucfirst($user->status) . '</span>';
            })
            ->addColumn('action', function($user) {
                return '<div class="btn-group">
                    <a href="' . route('admin.users.edit', $user->id) . '" 
                       class="btn btn-sm btn-primary edit-user" 
                       data-id="' . $user->id . '">
                       <i class="fas fa-edit"></i> Edit
                    </a>
                    <button type="button" 
                            class="btn btn-sm btn-danger delete-user" 
                            data-id="' . $user->id . '" 
                            data-name="' . $user->name . '"
                            data-url="' . route('admin.users.destroy', $user->id) . '">
                            <i class="fas fa-trash"></i> Delete
                    </button>
                </div>';
            })
            ->rawColumns(['status_badge', 'action'])
            ->setRowId('id');
    }

    public function query(User $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('users-table')
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
            Column::make('id'),
            Column::make('name'),
            Column::make('email'),
            Column::make('role'),
            Column::computed('status_badge')
                  ->title('Status')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
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
        return 'Users_' . date('YmdHis');
    }
}
