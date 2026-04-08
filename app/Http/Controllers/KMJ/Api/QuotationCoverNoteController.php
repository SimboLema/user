<?php

namespace App\Http\Controllers\KMJ\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\KMJ\SendTiraController;
use App\Models\Models\KMJ\CoverNoteDuration;
use App\Models\Models\KMJ\Quotation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuotationCoverNoteController extends Controller
{
    public function renewCoverNote(Request $request, $id)
    {

        try {
            // Start database transaction
            DB::beginTransaction();

            $coveNoteDuration = CoverNoteDuration::find($request->cover_note_duration_id);
            $cover_note_start_date = $request->cover_note_start_date;

            $coverNoteStartDate = Carbon::parse($cover_note_start_date);
            $coverNoteEndDate = $coverNoteStartDate->copy()->addMonths($coveNoteDuration->months ?? 12)->subDay();

            $quotation = Quotation::find($id);
            $quotation->cover_note_type_id = 2;
            $quotation->cover_note_duration_id = $coveNoteDuration->id;
            $quotation->cover_note_start_date = $coverNoteStartDate;
            $quotation->cover_note_end_date = $coverNoteEndDate;
            $quotation->updated_by = Auth::user()->id;
            $quotation->save();

            //Send Tira
            $sendTiraController = app(SendTiraController::class);
            $sendTiraController->sendToTira($quotation->id);

            // Commit transaction
            DB::commit();

            return redirect()
                ->route('kmj.quotation.covernote', $quotation->id)
                ->with('success', 'CoverNote Renewed successfully!');
        } catch (\Exception $e) {
            // Rollback on failure
            DB::rollBack();

            Log::error('CoverNote Renewed creation failed: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Something went wrong while creating the CoverNote Renewed. Please try again.');
        }
    }
}
