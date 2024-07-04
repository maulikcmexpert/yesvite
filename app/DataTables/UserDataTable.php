<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Storage;

class UserDataTable extends DataTable
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
            ->addColumn('app_user', function ($row) {
                if ($row->app_user == '1') {
                    return '<svg width="16" height="26" viewBox="0 0 16 26" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M12.9231 0H3.07692C2.26087 0 1.47824 0.326105 0.90121 0.906574C0.324175 1.48704 0 2.27433 0 3.09524V22.9048C0 23.7257 0.324175 24.513 0.90121 25.0934C1.47824 25.6739 2.26087 26 3.07692 26H12.9231C13.7391 26 14.5218 25.6739 15.0988 25.0934C15.6758 24.513 16 23.7257 16 22.9048V3.09524C16 2.27433 15.6758 1.48704 15.0988 0.906574C14.5218 0.326105 13.7391 0 12.9231 0ZM14.7692 22.9048C14.7692 23.3973 14.5747 23.8697 14.2285 24.218C13.8823 24.5662 13.4127 24.7619 12.9231 24.7619H3.07692C2.58729 24.7619 2.11772 24.5662 1.7715 24.218C1.42527 23.8697 1.23077 23.3973 1.23077 22.9048V3.09524C1.23077 2.60269 1.42527 2.13032 1.7715 1.78204C2.11772 1.43376 2.58729 1.2381 3.07692 1.2381H12.9231C13.4127 1.2381 13.8823 1.43376 14.2285 1.78204C14.5747 2.13032 14.7692 2.60269 14.7692 3.09524V22.9048ZM8 13.619C8.60856 13.619 9.20345 13.4375 9.70945 13.0974C10.2154 12.7573 10.6098 12.2739 10.8427 11.7083C11.0756 11.1427 11.1365 10.5204 11.0178 9.91996C10.8991 9.31954 10.606 8.76802 10.1757 8.33515C9.7454 7.90227 9.19714 7.60748 8.60028 7.48805C8.00341 7.36862 7.38475 7.42991 6.82251 7.66418C6.26028 7.89845 5.77973 8.29518 5.44163 8.80419C5.10354 9.3132 4.92308 9.91163 4.92308 10.5238C4.92308 11.3447 5.24725 12.132 5.82429 12.7125C6.40132 13.2929 7.18395 13.619 8 13.619ZM8 8.66667C8.36514 8.66667 8.72207 8.77559 9.02567 8.97965C9.32927 9.18372 9.56589 9.47376 9.70562 9.81311C9.84536 10.1525 9.88192 10.5259 9.81068 10.8861C9.73945 11.2464 9.56362 11.5773 9.30543 11.837C9.04724 12.0967 8.71829 12.2736 8.36017 12.3453C8.00205 12.4169 7.63085 12.3801 7.29351 12.2396C6.95617 12.099 6.66784 11.861 6.46498 11.5556C6.26212 11.2502 6.15385 10.8911 6.15385 10.5238C6.15385 10.0313 6.34835 9.55889 6.69457 9.21061C7.04079 8.86233 7.51037 8.66667 8 8.66667ZM12.3077 17.9524C12.3077 18.1166 12.2429 18.274 12.1275 18.3901C12.012 18.5062 11.8555 18.5714 11.6923 18.5714C11.5291 18.5714 11.3726 18.5062 11.2572 18.3901C11.1418 18.274 11.0769 18.1166 11.0769 17.9524C11.0769 17.4598 10.8824 16.9875 10.5362 16.6392C10.19 16.2909 9.7204 16.0952 9.23077 16.0952H6.76923C6.2796 16.0952 5.81002 16.2909 5.4638 16.6392C5.11758 16.9875 4.92308 17.4598 4.92308 17.9524C4.92308 18.1166 4.85824 18.274 4.74284 18.3901C4.62743 18.5062 4.4709 18.5714 4.30769 18.5714C4.14448 18.5714 3.98796 18.5062 3.87255 18.3901C3.75714 18.274 3.69231 18.1166 3.69231 17.9524C3.69231 17.1315 4.01648 16.3442 4.59352 15.7637C5.17055 15.1832 5.95318 14.8571 6.76923 14.8571H9.23077C10.0468 14.8571 10.8294 15.1832 11.4065 15.7637C11.9835 16.3442 12.3077 17.1315 12.3077 17.9524Z" fill="#EA555C"/>
</svg>';
                } else {
                    return '
<svg width="29" height="26" viewBox="0 0 29 26" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M17.2114 18.0299C16.2698 17.8087 15.7016 17.6202 15.7016 16.7394C15.7016 16.0252 16.5517 16.1793 17.0737 14.3034C17.3447 14.2019 17.5957 13.9046 17.7035 13.505C17.8413 12.9938 17.6927 12.5181 17.3818 12.3577C17.5622 8.99909 14.4999 9.26009 14.4999 9.26009C14.4999 9.26009 11.4377 8.99909 11.6181 12.3577C11.3063 12.5181 11.1577 12.9929 11.2963 13.505C11.4042 13.9037 11.6552 14.2019 11.9262 14.3034C12.4482 16.1793 13.2982 16.0252 13.2982 16.7394C13.2982 17.6202 12.73 17.8087 11.7884 18.0299C10.845 18.2492 8.89478 18.7911 8.89478 20.0282C8.89478 21.2697 8.89478 22.3056 8.89478 22.3056H20.106C20.106 22.3056 20.106 21.2697 20.106 20.0282C20.106 18.7902 18.1548 18.2492 17.2114 18.0299Z" fill="#EA555C"/>
<path d="M18.8936 4.23203C19.319 4.23203 19.6639 3.88715 19.6639 3.46172C19.6639 3.03629 19.319 2.69141 18.8936 2.69141C18.4682 2.69141 18.1233 3.03629 18.1233 3.46172C18.1233 3.88715 18.4682 4.23203 18.8936 4.23203Z" fill="#EA555C"/>
<path d="M21.96 4.23203C22.3854 4.23203 22.7303 3.88715 22.7303 3.46172C22.7303 3.03629 22.3854 2.69141 21.96 2.69141C21.5346 2.69141 21.1897 3.03629 21.1897 3.46172C21.1897 3.88715 21.5346 4.23203 21.96 4.23203Z" fill="#EA555C"/>
<path d="M25.0298 4.23203C25.4553 4.23203 25.8001 3.88715 25.8001 3.46172C25.8001 3.03629 25.4553 2.69141 25.0298 2.69141C24.6044 2.69141 24.2595 3.03629 24.2595 3.46172C24.2595 3.88715 24.6044 4.23203 25.0298 4.23203Z" fill="#EA555C"/>
<path d="M27.6089 0H1.39109C0.6235 0 0 0.625312 0 1.392V24.4416C0 25.2092 0.6235 25.8336 1.39109 25.8336H27.6089C28.3756 25.8336 29 25.2092 29 24.4416V1.392C29 0.625312 28.3756 0 27.6089 0ZM1.39109 0.879063H27.6089C27.8908 0.879063 28.12 1.10925 28.12 1.392V5.7275H0.878156V1.392C0.878156 1.10925 1.10834 0.879063 1.39109 0.879063ZM27.6089 24.9545H1.39109C1.10744 24.9545 0.878156 24.7243 0.878156 24.4416V6.60656H28.1209V24.4416C28.12 24.7243 27.8898 24.9545 27.6089 24.9545Z" fill="#EA555C"/>
</svg>';
                }
            })

            ->rawColumns(['profile', 'app_user']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        return  User::where(['account_type' => '0'])->orderBy('id', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('user-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            // ->orderBy(1)
            ->setTableAttributes(['class' => 'table table-bordered data-table users-data-table dataTable no-footer'])
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
            Column::make('no')->title('#')->render('meta.row + meta.settings._iDisplayStart + 1;'),
            Column::make('profile'),
            Column::make('username'),
            Column::make('app_user')->title('User Type'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'User_' . date('YmdHis');
    }
}
