<?php

namespace App\DataTables;

use App\Models\User;
// use App\Models\UserResendEmailVerify;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Http\Request;


class UserResendEmailVerifyDataTable extends DataTable
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
            
                    $query->where(function ($q) use ($nameParts) {
                        if (count($nameParts) === 2) {
                            // If there are two parts, assume it's first name and last name
                            $q->where('firstname', 'LIKE', "%{$nameParts[0]}%")
                              ->where('lastname', 'LIKE', "%{$nameParts[1]}%");
                        } else {
                            // If only one part, search both firstname and lastname individually
                            $q->where('firstname', 'LIKE', "%{$nameParts[0]}%")
                              ->orWhere('lastname', 'LIKE', "%{$nameParts[0]}%");
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
            ->addColumn('resend_mail', function ($row) {
                $cryptId = encrypt($row->id);
                $view_url = route('re_send_email', $cryptId);
                $actionBtn = '
                    <a class="" href="' . $view_url . '" title="View"><button class="btn btn-danger">Send Mail</button></a>';

                return $actionBtn;




            })
            

            ->rawColumns(['profile','username','email','resend_mail']);
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
        }
    
        $direction = 'desc';  // Default direction
    
        if (isset($request->order[0]['dir']) && $request->order[0]['dir'] == 'asc') {
            $direction = 'asc';
        }
        return  User::where(['resend_verification_mail' => '1'])->orderBy($column, $direction);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('userresendemailverify-table')
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
            Column::make('no')->title('#')->render('meta.row + meta.settings._iDisplayStart + 1;')->orderable(false),
            Column::make('profile')->title('Profile')->orderable(false),
            Column::make('username')->title('Username')->orderable(true),
            Column::make('email')->title('Email')->orderable(false),
            Column::make('resend_mail')->title('Resend Email')->orderable(false),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'UserResendEmailVerify_' . date('YmdHis');
    }
}
