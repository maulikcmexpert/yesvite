<?php

namespace App\DataTables;

use App\Models\UserPostReport;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use App\Models\{
    UserReportToPost
};

class UserPostReportDataTable extends DataTable
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
                        $q->orWhereHas('users', function ($q) use ($keyword) {
                            $q->where('firstname', 'LIKE', "%{$keyword}%")
                            ->orWhere('lastname', 'LIKE', "%{$keyword}%");

                        })
                        ->orWhere('event_name', 'LIKE', "%{$keyword}%");

                    });
                }
            })

            ->addColumn('number', function ($row) {

                static $count = 1;

                return $count++;
            })

            ->addColumn('username', function ($row) {

                return $row->users->firstname . ' ' . $row->users->lastname;
            })

            ->addColumn('event_name', function ($row) {

                return $row->events->event_name;
            })


            ->addColumn('post_type', function ($row) {

                if ($row->event_posts->post_type == '0') {

                    return "<span class='text-info'>Normal</span>";
                }
                if ($row->event_posts->post_type == '1') {

                    return "<span class='text-info'>Photos and videos</span>";
                }
                if ($row->event_posts->post_type == '2') {

                    return "<span class='text-info'>Polls</span>";
                }
                if ($row->event_posts->post_type == '3') {

                    return "<span class='text-info'>Recording</span>";
                }
            })

            ->addColumn('action', function ($row) {

                $cryptId = encrypt($row->id);
                $view_url = route('user_post_report.show', $cryptId);

                $actionBtn = '<div class="action-icon">
                    <a class="" href="' . $view_url . '" title="View"><i class="fa fa-eye"></i></a>';

                return $actionBtn;
            })

            ->rawColumns(['number', 'username', 'event_name', 'post_type', 'action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(UserReportToPost $model): QueryBuilder
    {
        return UserReportToPost::with(['events', 'users', 'event_posts'])->orderBy('id', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('userpostreport-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->setTableAttributes(['class' => 'table table-bordered data-table users-data-table dataTable no-footer'])
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
            Column::make('username')->title('Username(Reported By)'),
            Column::make('event_name')->title("Event Name"),
            Column::make('post_type')->title("Post Type"),
            Column::make('action')->title("Action"),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'UserPostReport_' . date('YmdHis');
    }
}
