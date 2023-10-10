<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\Admin\InstructionResource;
use App\Models\Instruction;
use Illuminate\Http\Request;

class InstructionController extends Controller
{
    public function getData()
    {
        $instructions = Instruction::allowed()->get();
        if ($instructions && count($instructions) > 0) {
            return response([
                'status' => 'success',
                'message' => '',
                'status_code' => 200,
                'instructions' => InstructionResource::collection($instructions),
            ]);
        }

        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'No instructions found.',
            'instructionss' => null,
        ]);
    }


    public function submitData(Request $request)
    {
        if ($request->edited == -1) {
            $instruction = Instruction::create([
                'description' => $request->description,
                'status' => 'activated',
            ]);
    
            if ($instruction) {
                return response([
                    'status' => 'success',
                    'message' => 'Instruction created successfully',
                    'instruction' => new InstructionResource($instruction)
                ]);
            }
        } else {
            $instruction = Instruction::find($request->edited);
            $update = $instruction->update([
                'description' => $request->description,
                'status' => 'activated',
            ]);
    
            if ($update) {
                $instruction = Instruction::find($request->edited);
                return response([
                    'status' => 'success',
                    'message' => 'Instruction updated successfully',
                    'instruction' => new InstructionResource($instruction)
                ]);
            }
        }

        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'something went wrong...',
            'instruction' => null,
        ], 500);
    }

    public function changeStatusData(Request $request, Instruction $instruction)
    {
        $message = "";
        if ($request->status == 'deleted') {
            $message = "Deleted";
        } else if ($request->status == 'activated') {
            $message = "Activated";
        } else {
            $message = "Deactivated";
        }

        $updated =  $instruction->update(['status' => $request->status]);

        if ($updated) {
            $instruction =  Instruction::find($instruction->id);
            return response(['status' => 'success', 'message' => $message, 'instruction' => new InstructionResource($instruction)], 200);
        }

        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'something want wrong. unable to ',
        ]);
    }
}
