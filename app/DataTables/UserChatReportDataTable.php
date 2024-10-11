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

use App\Models\{
    UserReportChat
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
            // ->filter(function ($query) {
            //     if ($this->request->has('search')) {
            //         $keyword = $this->request->get('search');
            //         $keyword = $keyword['value'];
            //         $query->where(function ($q) use ($keyword) {
            //             $q->whereHas('users', function ($q) use ($keyword) {
            //                 $q->where('firstname', 'LIKE', "%{$keyword}%")
            //                     ->orWhere('lastname', 'LIKE', "%{$keyword}%");
            //             })->orWhereHas('events', function ($q) use ($keyword) {
            //                 $q->where('event_name', 'LIKE', "%{$keyword}%");
            //             });
            //         });
            //     }
            // })

            ->addColumn('number', function ($row) {
                static $count = 1;
                return $count++;
            })

            ->addColumn('reporter_username', function ($row) {
                // dd($row->reporter_user->firstname);
                // return $row->reporter_user->firstname;
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

            ->addColumn('action', function ($row) {
                $cryptId = encrypt($row->id);
                $view_url = route('user_post_report.show', $cryptId);
                $actionBtn = '<div class="action-icon">
                    <a class="" href="' . $view_url . '" title="View"><i class="fa fa-eye"></i></a>';
                return $actionBtn;
            })

            ->rawColumns(['number', 'reporter_username', 'reported_username', 'report_type', 'report_description', 'action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(UserReportChat $model): QueryBuilder
    {
        // return UserReportChat::orderBy('id', 'desc');
        return UserReportChat::with(['reporter_user', 'to_reporter_user'])->orderBy('id', 'desc');
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
            Column::make('reporter_username')->title('Reporter Username (Reported By)'),
            Column::make('reported_username')->title("Reported Username (Reported To)"),
            Column::make('report_type')->title("Report Type"),
            Column::make('report_description')->title("Report Description"),
            Column::make('action')->title("Action"),
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
