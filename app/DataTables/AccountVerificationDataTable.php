<?php

namespace App\DataTables;
use App\Models\User;
use App\Models\AccountVerification;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Http\Request;


class AccountVerificationDataTable extends DataTable
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
            
                    // Split the search term by spaces to handle first and last name separately
                    $nameParts = explode(' ', $keyword);
            
                    $query->where(function ($q) use ($nameParts, $keyword) {
                        if (count($nameParts) > 1) {
                            // If search contains both first and last names
                            $q->where('firstname', 'LIKE', "%{$nameParts[0]}%")
                              ->where('lastname', 'LIKE', "%{$nameParts[1]}%");
                        } else {
                            // If only one search term, search both firstname and lastname
                            $q->where('firstname', 'LIKE', "%{$keyword}%")
                              ->orWhere('lastname', 'LIKE', "%{$keyword}%");
                        }
                    });
                }
            })
            
            ->addColumn('profile', function ($row) {

                if (trim($row->profile) != "" || trim($row->profile) != NULL) {
                    // if (Storage::disk('public')->exists('profile/' . $row->profile)) {
                    $imageUrl = asset('storage/profile/' . $row->profile);
                    // } else {
                    //     $imageUrl = asset('storage/no_profile.png');
                    // }
                } else {
                    $imageUrl = asset('storage/no_profile.png');
                }
                return '<div class="symbol-label">
                <img src="' . $imageUrl . '" alt="No Image" class="w-50">
            </div>';
            })
            ->addColumn('username', function ($row) {
                return $row->firstname . ' ' . $row->lastname;
            })
            ->addColumn('email', function ($row) {
                return $row->email;
            })
            ->addColumn('action', function ($row) {
                $cryptId = encrypt($row->id);
                $edit_url = route('account_verification.edit', $cryptId);
                $verify_url=route('account_verify',$cryptId);
                $actionBtn = '<div class="action-icon">
                <a class="" href="' . $verify_url . '" title="Delete"><button type="submit" class="btn btn-danger">Verify</button></form>
                </div>';

                return $actionBtn;




            })
            

            // <a class="" href="' . $edit_url . '" title="Edit"><i class="fa fa-edit"></i></a>
            ->rawColumns(['profile','username','email','action']);
    }


    /**
     * Get the query source of dataTable.
     */
    public function query(User $model,Request $request): QueryBuilder
    {
        $column = 'id';  // Default column
    
        if (isset($request->order[0]['column'])) {
            if ($request->order[0]['column'] == '2') {
                // Sorting by the reporter user's firstname from the users table
                $column = "firstname";
            } elseif ($request->order[0]['column'] == '3') {
                // Sorting by the 'to' reporter user's firstname (assuming another user field)
                $column = "email";
            }
            elseif ($request->order[0]['column'] == '1') {
                // Sorting by the 'to' reporter user's firstname (assuming another user field)
                $column = "firstname";
            }
        }
    
        $direction = 'desc';  // Default direction
    
        if (isset($request->order[0]['dir']) && $request->order[0]['dir'] == 'asc') {
            $direction = 'asc';
        }
        // dd($direction);
        return  User::where(['email_verified_at' => Null])->orderBy($column, $direction);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('accountverification-table')
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
            Column::make('profile')->title('Profile')->orderable(false),
            Column::make('username')->title('Username')->orderable(true),
            Column::make('email')->title('Email')->orderable(true),
            Column::make('action')->title('Action')->orderable(false),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'AccountVerification_' . date('YmdHis');
    }
}
