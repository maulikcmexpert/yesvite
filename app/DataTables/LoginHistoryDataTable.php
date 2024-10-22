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
use Illuminate\Http\Request;


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
            
                    // Split the search term by space to separate firstname and lastname
                    $nameParts = explode(' ', $keyword);
            
                    $query->where(function ($q) use ($nameParts, $keyword) {
                        if (count($nameParts) > 1) {
                            // Search for firstname and lastname separately
                            $q->whereHas('user', function ($q) use ($nameParts) {
                                $q->where('firstname', 'LIKE', "%{$nameParts[0]}%")
                                  ->where('lastname', 'LIKE', "%{$nameParts[1]}%");
                            });
                        } else {
                            // Search for firstname or lastname if only one term is provided
                            $q->whereHas('user', function ($q) use ($keyword) {
                                $q->where('firstname', 'LIKE', "%{$keyword}%")
                                  ->orWhere('lastname', 'LIKE', "%{$keyword}%");
                            });
                        }
            
                        // Search other fields (e.g., login_count and ip_address)
                        $q->orWhere('login_count', 'LIKE', "%{$keyword}%")
                          ->orWhere('ip_address', 'LIKE', "%{$keyword}%");
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
    public function query(LoginHistory $model,Request $request): QueryBuilder
    {

        $column = 'id';  // Default column
    
        if (isset($request->order[0]['column'])) {
            if ($request->order[0]['column'] == '1') {
                // Sorting by the reporter user's firstname from the users table
                $column = User::select('firstname')
                ->whereColumn('users.id', 'login_histories.user_id');            } 

                if ($request->order[0]['column'] == '0') {
                    // Sorting by the reporter user's firstname from the users table
                    $column = User::select('firstname')
                    ->whereColumn('users.id', 'login_histories.user_id'); 
                           } 
        }
       
    

        $direction = 'desc';  // Default direction
    
        if (isset($request->order[0]['dir']) && $request->order[0]['dir'] == 'asc') {
            $direction = 'asc';
        }
        return LoginHistory::with(['user'])->orderBy($column, $direction);
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
                    ->orderBy(0)
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
            Column::make('no')->title('No')->render('meta.row + meta.settings._iDisplayStart + 1;')->orderable(true),
            Column::make('username')->title('Username')->orderable(true),
            Column::make('ip_address')->title("Ip Address")->orderable(false),
            Column::make('login_count')->title("Login Count")->orderable(false),
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
