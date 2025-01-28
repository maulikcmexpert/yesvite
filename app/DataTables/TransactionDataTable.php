<?php

namespace App\DataTables;

use App\Models\Coin_transactions;
use App\Models\Event;
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
            ->filter(function ($query) {
                if ($this->request->has('search')) {
                    $keyword = $this->request->get('search');
                    $keyword = $keyword['value'];
            
                    // Split the keyword by spaces
                    $nameParts = explode(' ', $keyword);
            
                    $query->where(function ($q) use ($nameParts, $keyword) {
                        if (count($nameParts) === 2) {
                            // If two parts (assumed first and last name)
                            $q->whereHas('users', function ($q) use ($nameParts) {
                                $q->where('firstname', 'LIKE', "%{$nameParts[0]}%")
                                  ->where('lastname', 'LIKE', "%{$nameParts[1]}%");
                            });
                        } else {
                            // If one part, search for either firstname or lastname
                            $q->whereHas('users', function ($q) use ($keyword) {
                                $q->where('firstname', 'LIKE', "%{$keyword}%")
                                  ->orWhere('lastname', 'LIKE', "%{$keyword}%");
                            });
                        }
            
                        // Search in 'events' as well
                        $q->orWhereHas('event', function ($q) use ($keyword) {
                            $q->where('event_name', 'LIKE', "%{$keyword}%");
                        });

                        
                        $q->orWhere('type', 'LIKE', "%{$keyword}%")
                        ->orWhere('coins', 'LIKE', "%{$keyword}%")
                        ->orWhere('coins', 'LIKE', "%{$keyword}%")
                        ->orWhere('current_balance', 'LIKE', "%{$keyword}%")
                        // ->orWhere('used_coins', 'LIKE', "%{$keyword}%")
                        ->orWhere('description', 'LIKE', "%{$keyword}%")
                        ->orWhere('created_at', 'LIKE', "%{$keyword}%")
                        ->orWhere('endDate', 'LIKE', "%{$keyword}%");

                    });
                }
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
            ->addColumn('description', function ($row) {
                return $row->description;
            })
            ->addColumn('coins', function ($row) {
                return $row->coins;
            })
            ->addColumn('current_balance', function ($row) {
                return $row->current_balance;
            })
            // ->addColumn('used_coins', function ($row) {
            //     return $row->used_coins;
            // })
            ->addColumn('transcation_date', function ($row) {
                return $row->created_at;
            })
            ->addColumn('endDate', function ($row) {
                return (isset($row->endDate)&&$row->endDate!="")?$row->endDate:"";
            })
            
            

            ->rawColumns(['user', 'event', 'type','description', 'coins','current_balance','transcation_date','endDate']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Coin_transactions $model,Request $request): QueryBuilder

    {
        $column = 'id';

        if (isset($request->order[0]['column'])) {
            if ($request->order[0]['column'] == '0') {
                $column = 'id';
            }
            if ($request->order[0]['column'] == '2') {
                // $column = 'firstname';
                $column = Event::select('event_name')
                ->whereColumn('events.id', 'coin_transactions.event_id');

            }else if ($request->order[0]['column'] == '3'){
                $column = 'type';
            }
            else if ($request->order[0]['column'] == '4'){
                $column = 'description';
            }else if ($request->order[0]['column'] == '5'){
                $column = 'coins';
            }else if ($request->order[0]['column'] == '6'){
                $column = 'current_balance';
            }
            // else if ($request->order[0]['column'] == '7'){
            //     $column = 'used_coins';
            // }
            else if ($request->order[0]['column'] == '7'){
                $column = 'created_at';
            }else if ($request->order[0]['column'] == '8'){
                $column = 'endDate';
            }

        }

        $direction = 'desc';

        if (isset($request->order[0]['dir']) && $request->order[0]['dir'] == 'asc') {
            $direction = 'asc';
        }

        $userId =decrypt($request->user_id); // Retrieve the passed user ID
        // return $model->newQuery();
        return Coin_transactions::with([ 'users','event','user_subscriptions'])->where('user_id',$userId)
                                ->orderBy($column, $direction);

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
            Column::make('type')->orderable(true),
            Column::make('description')->orderable(true),
            Column::make('coins')->orderable(true),
            Column::make('current_balance')->title('Current Balance')->orderable(true),
            // Column::make('used_coins')->title('Used Coins')->orderable(true),
            Column::make('transcation_date')->title('Transcation Date')->orderable(true),
            Column::make('endDate')->title('Expiry Date')->orderable(true),
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
