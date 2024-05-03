<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Resume;
use App\Models\ResumeGroupMapper;
use App\Models\ResumeSocialLinkMapper;
use App\Models\ResumeGroupItemMapper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResumeController extends Controller
{
    protected Resume $resume;

    public function __construct(Resume $resume)
    {
        $this->resume = $resume;
    }

    public function store(Request $request) {
        $resumeExist = Resume::where('user_id',auth()->user()->id)->first();

        if($resumeExist){
            return response()->json([
                "status" => false,
                "message" => "Resume is already created for given user."
            ], 400);
        }

        $messages = array(
            'message.required' => 'Message is required',
        );

        $validator = Validator::make($request->all(), [
            'profile_url' => 'required|string',
            'name' => 'required|string',
            'role' => 'required|string',
            'location' => 'required|string',
            'description_title' => 'required|string',
            'description_text' => 'required|string',
            'social_link_mapper' => 'required|array',
            'group_mapper' => 'required|array',
            'group_mapper.*.title' => 'required|string',
            'group_mapper.*.type' => 'required|string|in:left,right',
            'group_mapper.*.order' => 'required|integer',
            'social_link_mapper.*.url' => 'required|string',
            'social_link_mapper.*.fav_icon' => 'required|string',
            'social_link_mapper.*.order' => 'required|integer',
            'social_link_mapper.*.title' => 'required|string',
            'group_mapper.*.group_item_mapper.*.title' => 'required|string',
            'group_mapper.*.group_item_mapper.*.order' => 'required|integer',
        ], $messages);

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->errors()->all()
            ], 400);
        }

        $resumeResponse = auth()->user()->resume()->create($request->all());
        $resumeResponse->socialLinkMapper()->createMany($request->social_link_mapper);
        $groupMapper = $resumeResponse->groupMapper()->createMany($request->group_mapper);
        for($i= 0; $i < count($groupMapper); $i++) {
            $groupMapper[$i]->groupItemMapper()->createMany($request->group_mapper[$i]['group_item_mapper']);
        }        
        
        return response()->json($resumeResponse, 201);
    }

    public function get($username) {
        $user = User::where('username', $username)->first();

        if (!$user) {
            return response()->json([
                "status" => false,
                "message" => "User not found"
            ], 400);
        }

        $resume = Resume::with('socialLinkMapper','groupMapper', 'groupMapper.groupItemMapper')->where('user_id',$user->id)->first();
        return response()->json($resume, 201);
    }

    public function update(Request $request, string $id)
    {
        $resume = Resume::find($id);

        if(!$resume){
            return response()->json([
                "status" => false,
                "message" => "Resume not found."
            ], 400);
        }

        $messages = array(
            'message.required' => 'Message is required',
        );

        $validator = Validator::make($request->all(), [
            'profile_url' => 'required|string',
            'name' => 'required|string',
            'role' => 'required|string',
            'location' => 'required|string',
            'description_title' => 'required|string',
            'description_text' => 'required|string',
            'social_link_mapper' => 'required|array',
            'group_mapper' => 'required|array',
            'group_mapper.*.title' => 'required|string',
            'group_mapper.*.type' => 'required|string|in:left,right',
            'group_mapper.*.order' => 'required|integer',
            'social_link_mapper.*.url' => 'required|string',
            'social_link_mapper.*.fav_icon' => 'required|string',
            'social_link_mapper.*.order' => 'required|integer',
            'social_link_mapper.*.title' => 'required|string',
            'group_mapper.*.group_item_mapper.*.title' => 'required|string',
            'group_mapper.*.group_item_mapper.*.order' => 'required|integer',
        ], $messages);

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->errors()->all()
            ], 400);
        }
     
        $result = $resume->update($request->all());

        // Update Social Links data
        $socialLinkIdsArray = [];
        foreach($request->social_link_mapper AS $socialLinkData){
            if(isset($socialLinkData['id'])){
                $socialLinkIdsArray[] = $socialLinkData['id'];
            }
        }
        
        ResumeSocialLinkMapper::where('resume_id', $id)->whereNotIn('id',$socialLinkIdsArray)->delete();
        $socialLinks = collect($request->social_link_mapper);
        $socialLinks->each(function ($data) use ($resume) {
            $resume->socialLinkMapper()->updateOrCreate([
              'id' => $data['id'] ?? null,
            ], $data);
          });


        // Update Group and Group Item Data
        $groupMapperIdsArray = [];
        $groupItemMapperIdsArray = [];

        foreach($request->group_mapper AS $groupData){
            if(isset($groupData['id'])){
                $groupMapperIdsArray[] = $groupData['id'];
                foreach($groupData['group_item_mapper'] AS $groupItemData){
                    if(isset($groupItemData['id'])){
                        $groupItemMapperIdsArray[] = $groupItemData['id'];
                    }
                }
                
                ResumeGroupItemMapper::where('resume_group_mapper_id', $groupData['id'])->whereNotIn('id',$groupItemMapperIdsArray)->delete();
            }
           
        }

        ResumeGroupItemMapper::whereNotIn('resume_group_mapper_id',$groupMapperIdsArray)->delete();
        ResumeGroupMapper::where('resume_id', $id)->whereNotIn('id',$groupMapperIdsArray)->delete();

        $groups = collect($request->group_mapper);
        $groups->each(function ($data) use ($resume) {
            $group = $resume->groupMapper()->updateOrCreate([
            'id' => $data['id'] ?? null,
            ], $data);
            $groupItems = collect($data['group_item_mapper']);
            $groupItems->each(function ($item) use ($group) {
                $group->groupItemMapper()->updateOrCreate([
                'id' => $item['id'] ?? null,
                ], $item);
            });
        });
        
        return response()->json([
            'result' => $result,
            'status' => 200,
            'message' => 'Resume updated successfully.'
        ]);
    }
}
