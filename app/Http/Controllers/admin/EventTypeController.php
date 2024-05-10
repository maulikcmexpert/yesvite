<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Http\Requests\CreateEventTypePost;

use App\Http\Requests\UpdateEventTypePost;

use App\Models\EventType;

use Yajra\DataTables\DataTables;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class EventTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $data = EventType::orderBy('id', 'desc');

            return Datatables::of($data)



                ->addIndexColumn()

                ->addColumn('number', function ($row) {

                    static $count = 1;

                    return $count++;
                })



                ->addColumn('action', function ($row) {

                    $cryptId = encrypt($row->id);

                    $edit_url = route('event_type.edit', $cryptId);

                    $delete_url = route('event_type.destroy', $cryptId);

                    $actionBtn = '<div class="action-icon">
                        <a class="" href="' . $edit_url . '" title="Edit"><i class="fa fa-edit"></i></a>
                        <form action="' . $delete_url . '" method="POST">' .
                        csrf_field() . // Changed from @csrf to csrf_field()
                        method_field("DELETE") . // Changed from @method to method_field()
                        '<button type="submit" class="btn bg-transparent"><i class="fas fa-trash"></i></button></form>
                        </div>';

                    return $actionBtn;
                })

                ->rawColumns(['number', 'action'])



                ->make(true);
        }



        $title = 'Event Type';

        $page = 'admin.event_type.list';

        $js = 'admin.event_type.event_typejs';

        return view('admin.includes.layout', compact('title', 'page', 'js'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Add event type';

        $page = 'admin.event_type.add';

        $js = 'admin.event_type.event_typejs';

        return view('admin.includes.layout', compact('title', 'page', 'js'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateEventTypePost $request)
    {
        try {
            DB::beginTransaction();

            foreach ($request->event_type as $val) {

                EventType::create([

                    'event_type' => $val,

                ]);
            }

            DB::commit();

            return redirect()->route('event_type.index')->with('success', 'Event type Add successfully !');
        } catch (QueryException $e) {

            DB::rollBack();

            Log::error('Database query error');

            return redirect()->route('category.create')->with('danger', 'Event type not added');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $eventType_id =  decrypt($id);

        $title = 'Edit Event Type';

        $page = 'admin.event_type.edit';

        $js = 'admin.event_type.event_typejs';

        $eventTypeId = $id;

        $getEventTypeDetail = EventType::where('id', $eventType_id)->first();

        return view('admin.includes.layout', compact('title', 'page', 'js', 'getEventTypeDetail', 'eventTypeId'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventTypePost $request,  $id)
    {
        try {



            DB::beginTransaction();

            $eventTypeId = decrypt($id);

            $updateEventType = EventType::findOrFail($eventTypeId);



            $updateEventType->event_type = $request->event_type;

            $updateEventType->save();

            DB::commit();

            return redirect()->route('event_type.index')->with('success', 'Event type updated successfully!');
        } catch (QueryException $e) {

            DB::rollBack();

            Log::error('Database query error');

            return redirect()->route('event_type.edit', $id)->with('danger', 'Event type not updated!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {

            DB::beginTransaction();



            $id = decrypt($id);

            $user = EventType::find($id)->delete();

            DB::commit();

            return redirect()->route('event_type.index')
                ->with('success', 'Event type deleted successfully');
        } catch (QueryException $e) {



            DB::rollBack();

            return redirect()->route('event_type.index')
                ->with('danger', 'Event type not deleted');
        }
    }

    public function checkEventTypeIsExist(Request $request)
    {
        try {

            $eventType = EventType::where(['event_type' => $request->event_type])->get();

            if (count($eventType) > 0) {

                if (isset($request->id) && !empty($request->id)) {



                    if ($eventType[0]->id == decrypt($request->id)) {



                        $return =  true;

                        echo json_encode($return);

                        exit;
                    }
                }

                $return =  false;
            } else {

                $return = true;
            }

            echo json_encode($return);

            exit;
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(false);
        }
    }
}
