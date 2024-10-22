<?php

namespace App\DataTables;

use App\Models\UserChatReport;
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


use App\Models\{
    UserReportChat,
    User
};

class UserChatReportDataTable extends DataTable
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
                $search = $this->request->get('search');
                $keyword = $search['value'] ?? null;
    
                if (!empty($keyword)) {
                    $query->where(function ($q) use ($keyword) {
                        $q->whereHas('reporter_user', function ($q) use ($keyword) {
                            $q->where('firstname', 'LIKE', "%{$keyword}%")
                              ->orWhere('lastname', 'LIKE', "%{$keyword}%");
                        })
                        ->orWhereHas('to_reporter_user', function ($q) use ($keyword) {
                            $q->where('firstname', 'LIKE', "%{$keyword}%")
                              ->orWhere('lastname', 'LIKE', "%{$keyword}%");
                        })
                        ->orWhere('report_type', 'LIKE', "%{$keyword}%")
                        ->orWhere('report_description', 'LIKE', "%{$keyword}%");
                    });
                }
            })
            
            ->addColumn('number', function ($row) {
                static $count = 1;
                return $count++;
            })
            ->addColumn('reporter_username', function ($row) {
                return (isset($row->reporter_user->firstname) && $row->reporter_user->firstname != "") ? $row->reporter_user->firstname : "";
            })
            ->addColumn('reported_username', function ($row) {
                return (isset($row->to_reporter_user->firstname) && $row->to_reporter_user->firstname != "") ? $row->to_reporter_user->firstname : "";
            })
            ->addColumn('report_type', function ($row) {
                return $row->report_type;
            })
            ->addColumn('report_description', function ($row) {
                return $row->report_description;
            })

            ->addColumn('report_time', function ($row) {
                return Carbon::parse($row->created_at)->format('Y-m-d h:i A');
            })
            // ->addColumn('action', function ($row) {
            //     $cryptId = encrypt($row->id);
            //     $view_url = route('user_chat_report.destroy', $cryptId);
            //     $actionBtn = '<div class="action-icon">
            //         <a class="" href="' . $view_url . '" title="View"><i class="fa fa-eye"></i></a>';
            //     return $actionBtn;
            // })
            ->rawColumns(['number', 'reporter_username', 'reported_username', 'report_type', 'report_description', 'report_time']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(UserReportChat $model, Request $request): QueryBuilder
    {
        $column = 'id';  // Default column
    
        if (isset($request->order[0]['column'])) {
            if ($request->order[0]['column'] == '1') {
                // Sorting by the reporter user's firstname from the users table
                $column = User::select('firstname')
                    ->whereColumn('users.id', 'user_report_chats.reporter_user_id');
            } elseif ($request->order[0]['column'] == '2') {
                // Sorting by the 'to' reporter user's firstname (assuming another user field)
                $column = User::select('firstname')
                    ->whereColumn('users.id', 'user_report_chats.to_be_reported_user_id');
            }else if($request->order[0]['column'] == '3'){
                $column="report_type";
            }
        }
    
        $direction = 'desc';  // Default direction
    
        if (isset($request->order[0]['dir']) && $request->order[0]['dir'] == 'asc') {
            $direction = 'asc';
        }
    
        return UserReportChat::with(['reporter_user', 'to_reporter_user'])->orderBy($column, $direction);
    }
    

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('userchatreport-table')
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
            Column::make('no')->title('No')->render('meta.row + meta.settings._iDisplayStart + 1;')->orderable(false),
            Column::make('reporter_username')->title('Reporter Username (Reported By)')->orderable(true),
            Column::make('reported_username')->title("Reported Username (Reported To)")->orderable(true),
            Column::make('report_type')->title("Report Type")->orderable(true),
            Column::make('report_description')->title("Report Description")->width('250px')->className('report-description-td')->orderable(false),
            Column::make('report_time')->title("Report Time")->orderable(false),
            // Column::make('action')->title("Action"),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'UserChatReport_' . date('YmdHis');
    }
}
