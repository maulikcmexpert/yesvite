<?php

namespace App\DataTables;

use App\Models\Coin_transactions;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionDataTable extends DataTable
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

            ->addColumn('user', function ($row) {
                return $row->users->firstname . ' ' . $row->users->lastname;
            })
            ->addColumn('event', function ($row) {
                return (isset($row->event->event_name)&&$row->event->event_name!="")?$row->event->event_name:"";
            })
            ->addColumn('type', function ($row) {
                return $row->type;
            })
            ->addColumn('coins', function ($row) {
                return $row->coins;
            })
            ->addColumn('current_balance', function ($row) {
                return $row->current_balance;
            })
            ->addColumn('used_coins', function ($row) {
                return $row->used_coins;
            })
            
            

            ->rawColumns(['user', 'event', 'type', 'coins','current_balance', 'used_coins']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Coin_transactions $model,Request $request): QueryBuilder

    {
        // dd($request);

        $userId =decrypt($request->user_id); // Retrieve the passed user ID
        // return $model->newQuery();
        return Coin_transactions::with([ 'users','event','user_subscriptions'])->where('user_id',$userId)->orderBy('id','desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('transaction-table')
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
            Column::make('no')->title('#')->render('meta.row + meta.settings._iDisplayStart + 1;')->orderable(false),
            Column::make('user')->orderable(false),
            Column::make('event')->orderable(true),
            Column::make('type')->orderable(false),
            Column::make('coins')->orderable(true),
            Column::make('current_balance')->title('Current Balance')->orderable(false),
            Column::make('used_coins')->title('Used Coins')->orderable(false),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Transaction_' . date('YmdHis');
    }
}
