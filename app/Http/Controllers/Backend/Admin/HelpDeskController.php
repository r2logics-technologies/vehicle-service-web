<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\Admin\HelpDeskResource;
use App\Models\HelpDesk;
use Illuminate\Http\Request;

class HelpDeskController extends Controller
{
    public function getData()
    {
        $helpDesks = HelpDesk::allowed()->get();
        if ($helpDesks && count($helpDesks) > 0) {
            return response([
                'status' => 'success',
                'message' => '',
                'status_code' => 200,
                'help_desks' => HelpDeskResource::collection($helpDesks),
            ]);
        }

        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'No help desk found.',
            'help_desks' => null,
        ]);
    }


    public function submitData(Request $request)
    {
        if ($request->edited == -1) {
            $helpDesk = HelpDesk::create([
                'contect_type' => $request->type == 'email' ? 'email' : 'mobile',
                'email' => $request->type == 'email' ? $request->contect : null,
                'mobile' => $request->type == 'mobile' ? $request->contect : null,
                'status' => 'activated',
            ]);
    
            if ($helpDesk) {
                return response([
                    'status' => 'success',
                    'message' => 'Contect created successfully',
                    'help_desk' => new HelpDeskResource($helpDesk)
                ]);
            }
        } else {
            $helpDesk = HelpDesk::find($request->edited);
            $update = $helpDesk->update([
                'contect_type' => $request->type == 'email' ? 'email' : 'mobile',
                'email' => $request->type == 'email' ? $request->contect : null,
                'mobile' => $request->type == 'mobile' ? $request->contect : null,
            ]);
    
            if ($update) {
                $helpDesk = HelpDesk::find($request->edited);
                return response([
                    'status' => 'success',
                    'message' => 'Contect updated successfully',
                    'help_desk' => new HelpDeskResource($helpDesk)
                ]);
            }
        }

        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'something went wrong...',
            'help_desk' => null,
        ], 500);
    }

    public function changeStatusData(Request $request, HelpDesk $helpDesk)
    {

        $message = "";
        if ($request->status == 'deleted') {
            $message = "Deleted";
        } else if ($request->status == 'activated') {
            $message = "Activated";
        } else {
            $message = "Deactivated";
        }

        $updated =  $helpDesk->update(['status' => $request->status]);

        if ($updated) {
            $helpDesk =  HelpDesk::find($helpDesk->id);
            return response(['status' => 'success', 'message' => $message, 'help_desk' => new HelpDeskResource($helpDesk)], 200);
        }

        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'something want wrong. unable to ',
        ]);
    }
}
