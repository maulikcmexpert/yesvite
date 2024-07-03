<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Storage;

class UserDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $counter = 1;
        return datatables()
            ->eloquent($query)
            ->addColumn('no', function () use (&$counter) {
                return $counter++;
            })
            ->addColumn('profile', function ($row) {

                if (trim($row->profile) != "" || trim($row->profile) != NULL) {
                    if (Storage::disk('public')->exists('profile/' . $row->profile)) {
                        $imageUrl = asset('storage/profile/' . $row->profile);
                    } else {
                        $imageUrl = asset('storage/no_profile.png');
                    }
                } else {
                    $imageUrl = asset('storage/no_profile.png');
                }
                return '<div class="symbol-label">
                <img src="' . $imageUrl . '" alt="No Image" class="w-50">
            </div>';
            })
            ->addColumn('username', function ($row) {
                return $row->firstname;
            })
            ->addColumn('app_user', function ($row) {
                if ($row->app_user == '1') {
                    return '<i class="fa-solid fa-mobile"></i>';
                } else {
                    return '<span class="text-danger">Not App User</span>';
                }
            })
            ->rawColumns(['profile', 'app_user']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        return  User::where(['account_type' => '0', 'app_user' => '1'])->orderBy('id', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('user-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            // ->orderBy(1)
            ->setTableAttributes(['class' => 'table table-bordered data-table users-data-table dataTable no-footer'])
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('no')->title('#'),
            Column::make('profile'),
            Column::make('username'),
            Column::make('app_user')->title('App User'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'User_' . date('YmdHis');
    }
}
