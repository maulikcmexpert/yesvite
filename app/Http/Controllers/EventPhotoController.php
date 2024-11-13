<?php
namespace App\Http\Controllers;
use App\Models\{
    Event,
    EventPost,
    EventPostComment,
    EventInvitedUser,
    Notification,
    EventImage,
    EventGiftRegistry,
    EventPostImage,
    EventPotluckCategory,
    EventPotluckCategoryItem,
    UserPotluckItem,
    PostControl,
    EventPostReaction
    
};

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as Exception;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class EventPhotoController extends Controller
{
    public function index(String $id){
        
        $user  = Auth::guard('api')->user();
        // $rawData = $request->getContent();
        // $input = json_decode($rawData, true);
        $event_id =$id;
        if ($event_id == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        try {
            // $selectedFilters = $request->input('filters');
            $getPhotoList = EventPost::query();
            $getPhotoList->with(['user', 'event_post_reaction', 'post_image'])->withCount(['event_post_reaction', 'post_image', 'event_post_comment' => function ($query) {
                $query->where('parent_comment_id', NULL);
            }])->where(['event_id' => $event_id, 'post_type' => '1']);
            $eventCreator = Event::where('id', $event_id)->first();
            // if (!empty($selectedFilters) && !in_array('all', $selectedFilters)) {
            //     $getPhotoList->where(function ($query) use ($selectedFilters, $eventCreator) {
            //         foreach ($selectedFilters as $filter) {
            //             switch ($filter) {
            //                 case 'time_posted':
            //                     $query->orderBy('id', 'desc');
            //                     break;
            //                 case 'guest':
            //                     $query->orWhere('user_id', '!=', $eventCreator->user_id);

            //                     break;
            //                 case 'photos':
            //                     $query->orWhereHas('post_image', function ($subQuery) {
            //                         $subQuery->where('type', 'image');
            //                     });
            //                     break;
            //                 case 'videos':
            //                     $query->orWhereHas('post_image', function ($subQuery) {
            //                         $subQuery->where('type', 'video');
            //                     });
            //                     break;
            //                     // Add more cases for other filters if needed
            //             }
            //         }
            //     });
            // }
            $getPhotoList->orderBy('id', 'desc');
            $results = $getPhotoList->get();
            $postPhotoList = [];
            foreach ($results as $value) {
                $ischeckEventOwner = Event::where(['id' => $event_id, 'user_id' => $user->id])->first();
                $postControl = PostControl::where(['user_id' => $user->id, 'event_id' => $event_id, 'event_post_id' => $value->id])->first();
                if ($postControl != null) {

                    if ($postControl->post_control == 'hide_post') {
                        continue;
                    }
                }
                $postPhotoDetail['user_id'] = $value->user->id;
                $postPhotoDetail['is_own_post'] = ($value->user->id == $user->id) ? "1" : "0";
                $postPhotoDetail['is_host'] =  ($ischeckEventOwner != null) ? 1 : 0;
                $postPhotoDetail['firstname'] = $value->user->firstname;
                $postPhotoDetail['lastname'] = $value->user->lastname;
                $postPhotoDetail['profile'] = (!empty($value->user->profile) || $value->user->profile != NULL) ? asset('storage/profile/' . $value->user->profile) : "";
                $selfReaction = EventPostReaction::where(['user_id' => $user->id, 'event_post_id' => $value->id])->first();
                $postPhotoDetail['is_reaction'] = ($selfReaction != NULL) ? '1' : '0';
                $postPhotoDetail['self_reaction'] = ($selfReaction != NULL) ? $selfReaction->reaction : "";
                $postPhotoDetail['event_id'] = $value->event_id;
                $postPhotoDetail['id'] = $value->id;
                $postPhotoDetail['post_message'] = (!empty($value->post_message) || $value->post_message != NULL) ? $value->post_message : "";
                $postPhotoDetail['post_time'] = $this->setpostTime($value->updated_at);
                $postPhotoDetail['is_in_photo_moudle'] = $value->is_in_photo_moudle;
                $photoVideoData = "";
                if (!empty($value->post_image)) {
                    $photData = $value->post_image;
                    foreach ($photData as $val) {
                        $photoVideoDetail['id'] = $val->id;
                        $photoVideoDetail['event_post_id'] = $val->event_post_id;
                        $photoVideoDetail['post_media'] = (!empty($val->post_image) || $val->post_media != NULL) ? asset('storage/post_image/' . $val->post_image) : "";
                        $photoVideoDetail['thumbnail'] = (!empty($val->thumbnail) || $val->thumbnail != NULL) ? asset('storage/thumbnails/' . $val->thumbnail) : "";
                        $photoVideoDetail['type'] = $val->type;
                        $photoVideoData = $photoVideoDetail;
                    }
                }

                $postPhotoDetail['mediaData'] = $photoVideoData;
                $postPhotoDetail['total_media'] = ($value->post_image_count - 1 != 0 && $value->post_image_count - 1 != -1)  ? "+" . $value->post_image_count - 1 : "";
                $getPhotoReaction = getReaction($value->id);
                $reactionList = [];
                foreach ($getPhotoReaction as $values) {
                    $reactionList[] = $values->reaction;
                }
                $postPhotoDetail['reactionList'] = $reactionList;
                $postPhotoDetail['total_likes'] = $value->event_post_reaction_count;
                $postPhotoDetail['total_comments'] = $value->event_post_comment_count;
                $postPhotoList[] = $postPhotoDetail;
            }
            if (!empty($postPhotoList)) {
                return compact('postPhotoList');
                // return response()->json(['status' => 1, 'data' => $postPhotoList, 'message' => "Photo List"]);
            } else {
                $postPhotoList="";
                return compact('postPhotoList');
                // return response()->json(['status' => 0, 'data' => $postPhotoList, 'message' => "Photo not found"]);
            }
        } 
        catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['status' => 0, 'message' => 'db error']);
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }
}
