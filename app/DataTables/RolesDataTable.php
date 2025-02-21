<?php

namespace App\DataTables;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class RolesDataTable extends DataTable
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
            ->addColumn('number', function ($row) {
                static $count = 1;
                return $count++;
            })
            ->addColumn('name', function ($row) {
                return (isset($row->name) && $row->name != "") ? $row->name : "";
            })
            ->addColumn('email', function ($row) {
                return (isset($row->email) && $row->email != "") ? $row->email : "";
            })

            ->addColumn('role', function ($row) {
                return (isset($row->role) && $row->role != "") ? $row->role : "";
            })
            ->addColumn('phone', function ($row) {
                return (isset($row->phone_number) && $row->phone_number != "") ? $row->phone_number : "";
            })
            ->addColumn('action', function ($row) {
                $cryptId = encrypt($row->id);
                $edit_url = route('users.edit', $cryptId);

                $actionBtn = '<div class="action-icon">
                    <a class="" href="' . $edit_url . '" title="Edit"><i class="fa fa-edit"></i></a>

                    </div>';
                return $actionBtn;
            })
            ->rawColumns(['name', 'email','role','phone','action']);

    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Admin $model): QueryBuilder
    {
        // return $model->newQuery();
        return Admin::where('is_admin','0')->orderBy('id', 'desc');

    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('roles-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(1)
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
            Column::make('no')->title('No')->render('meta.row + meta.settings._iDisplayStart + 1;')->orderable(false),
            Column::make('name')->title('Name')->orderable(true),
            Column::make('email')->title("Email")->orderable(true),
            Column::make('role')->title("Role")->orderable(true),
            Column::make('phone')->title("Phone Number")->orderable(true),
            Column::make('action')->title("Action")->orderable(false),



           // Column::make('action')->title("Action"),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Roles_' . date('YmdHis');
    }
}
