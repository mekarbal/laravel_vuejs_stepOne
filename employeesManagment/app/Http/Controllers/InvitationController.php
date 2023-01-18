<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invitation;

class InvitationController extends Controller
{
    public function addInvitation(Request $request)
    {
        $data = $request->validate([
            'sender_name' => 'string',
            'company_name' => 'string',
            'receiver' => 'string',
            'email' => 'string',
        ]);
        $invitation = Invitation::create($data);
        return response()->json(
            [
                'status' => true,
                'message' => 'Invitation created Successfully',
                'data' => $invitation,
            ],
            201
        );
    }
    public function getAllInvitations()
    {
        $invitations = Invitation::all();

        if ($invitations->isEmpty()) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'No invitation found',
                ],
                404
            );
        }
        return response()->json(
            [
                'status' => false,
                'message' => 'invitations list',
                'data' => $invitations,
            ],
            200
        );
    }

    public function updateInvitation(Request $request, $id)
    {
        $data = $request->validate([
            'sender_name' => 'string',
            'company_name' => 'string',
            'receiver' => 'string',
            'sent' => 'boolean',
            'is_Admin' => 'boolean',
            'validated' => 'boolean',
            'email' => 'string',
        ]);
        $invitation = Invitation::find($id);

        if ($invitation == null) {
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Invitation Not Found',
                ],
                404
            );
        }

        $invitation->update($data);
        return response()->json(
            [
                'status' => true,
                'message' => 'Invitation updated Successfully',
                'data' => $invitation,
            ],
            200
        );
    }
    public function deleteCompany(Request $request, $id)
    {
        $invitation = Invitation::find($id);

        if ($invitation == null) {
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Invitation Not Found',
                ],
                404
            );
        }
        $invitation->delete();
        return response()->json(
            [
                'status' => true,
                'message' => 'Invitation deleted Successfully',
            ],
            200
        );
    }
}
