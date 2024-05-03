<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ResumeGroupMapper;
use App\Models\ResumeGroupItemMapper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResumeGroupMapperController extends Controller
{
    public function update(Request $request, $id) {
        $groupMapper = ResumeGroupMapper::find($id);
        if(!$groupMapper){
            return response()->json([
                "status" => false,
                "message" => "Group not found."
            ], 400);
        }
        
        $messages = array(
            'message.required' => 'Message is required',
        );

        $groupMapperData = $request->all();
        $validator = Validator::make($groupMapperData, [
            'title' => 'required|string',
            'order' => 'required|integer',
            'type' => 'required|string',
            'group_item_mapper' => 'required|array',
        ], $messages);

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->errors()->all()
            ]);
        }

        $groupMapper->title = $groupMapperData['title'];
        $groupMapper->order = $groupMapperData['order'];
        $groupMapper->type = $groupMapperData['type'];
        $groupMapper->save();

        // Update Group Item Mapper data
        $groupItemMapperIdsArray = [];
        foreach($groupMapperData['group_item_mapper'] AS $groupItemData){
            if(isset($groupItemData['id'])){
                $groupItemMapperIdsArray[] = $groupItemData['id'];
            }
        }

        ResumeGroupItemMapper::where('resume_group_mapper_id', $id)->whereNotIn('id',$groupItemMapperIdsArray)->delete();

        $groupItems = collect($groupMapperData['group_item_mapper']);
        $groupItems->each(function ($item) use ($groupMapper) {
            $groupMapper->groupItemMapper()->updateOrCreate([
            'id' => $item['id'] ?? null,
            ], $item);
        });

        return response()->json([
            'result' => $groupMapper,
            'status' => 200,
            'message' => 'Group data updated successfully.'
        ]);
    }
}
