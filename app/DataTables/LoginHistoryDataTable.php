<?php

namespace App\DataTables;
use App\Models\User;
use App\Models\LoginHistory;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class LoginHistoryDataTable extends DataTable
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
            ->filter(function ($query) {
                if ($this->request->has('search')) {
                    $keyword = $this->request->get('search');
                    $keyword = $keyword['value'];
                    $query->where(function ($q) use ($keyword) {
                        $q->where('firstname', 'LIKE', "%{$keyword}%")
                            ->orWhere('lastname', 'LIKE', "%{$keyword}%");
                    });
                }
            })
           
            ->addColumn('username', function ($row) {
                return $row->user->firstname . ' ' . $row->user->lastname;
            })

            ->addColumn('ip_address', function ($row) {
                return $row->ip_address;
            })

            ->addColumn('login_count', function ($row) {
                return $row->login_count;
            })
         
            //     $cryptId = encrypt($row->id);
            //     $edit_url = route('account_verification.edit', $cryptId);
            //     $verify_url=route('account_verify',$cryptId);
            //     $actionBtn = '<div class="action-icon">
            //     <a class="" href="' . $verify_url . '" title="Delete"><button type="submit" class="btn btn-danger">Verify</button></form>
            //     </div>';

            //     return $actionBtn;




            // })
            

            // <a class="" href="' . $edit_url . '" title="Edit"><i class="fa fa-edit"></i></a>
            ->rawColumns(['username','ip_address','login_count']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(LoginHistory $model): QueryBuilder
    {
        return LoginHistory::with(relations: ['user'])->orderBy('id', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('loginhistory-table')
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
            Column::make('no')->title('No')->render('meta.row + meta.settings._iDisplayStart + 1;'),
            Column::make('username')->title('Username'),
            Column::make('ip_address')->title("Ip Address"),
            Column::make('login_count')->title("Login Count"),
            // Column::make('action')->title("Action"),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'LoginHistory_' . date('YmdHis');
    }
}
