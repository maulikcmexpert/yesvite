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
use Illuminate\Http\Request;

use App\Models\{
    UserReportToPost,
    User,
    Event
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
                        $q->orWhereHas('events', function ($q) use ($keyword) {
                            $q->where('event_name', 'LIKE', "%{$keyword}%");
                        });
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
            ->addColumn('report_type', function ($row) {

                return $row->report_type;
            }) ->addColumn('report_description', function ($row) {

                return $row->report_description;
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

            ->rawColumns(['number', 'username','report_type','report_description','event_name', 'post_type', 'action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(UserReportToPost $model,Request $request): QueryBuilder
    {
        $column = 'id';
        
        if (isset($request->order[0]['column'])) {
            if ($request->order[0]['column'] == '0') {
                $column = User::select('firstname')
                ->whereColumn('users.id', 'user_report_to_posts.user_id');
                            }
                if ($request->order[0]['column'] == '1') {
                    $column = User::select('firstname')
                    ->whereColumn('users.id', 'user_report_to_posts.user_id');
                }

                if ($request->order[0]['column'] == '4') {
                    $column = Event::select('event_name')
                    ->whereColumn('events.id', 'user_report_to_posts.event_id');
    
                }
            }
            
            $direction = 'desc';
        if (isset($request->order[0]['dir']) && $request->order[0]['dir'] == 'asc') {
            $direction = 'asc';
        }
        return UserReportToPost::with(['events', 'users', 'event_posts'])->orderBy($column, $direction);
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
            // ->orderBy(0)
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
            Column::make('username')->title('Username(Reported By)')->orderable(true),
            Column::make('report_type')->title('Report Type')->orderable(false  ),
            Column::make('report_description')->title("Report Description")->width('250px')->className('report-description-td')->orderable(false),
            Column::make('event_name')->title("Event Name")->orderable(true),
            Column::make('post_type')->title("Post Type")->orderable(false),
            Column::make('action')->title("Action")->orderable(false),
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
