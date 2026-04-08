<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Traits\ActivityLoggableTrait;
use App\Models\User;
use App\Models\Setting;
use App\Models\Utility;
use App\Models\TransferAmount;
use App\Models\SmtpSetting;
use App\Models\PaymentStatus;
use App\Models\BeamSetting;
use App\Models\PusherSetting;
use App\Models\EmailTemplate;
use App\Models\NotificationUser;
use App\Models\Country;
use App\Models\Unit;
use App\Models\IllegalDumping;
use App\Models\WasteType;
use App\Models\WasteSource;
use App\Models\Region;
use App\Models\District;
use App\Models\Ward;
use App\Models\NotificationTemplate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Auth;


class ConfigurationController extends Controller
{
    use ActivityLoggableTrait;

    public function unit()
    {
        if (!\Auth::user()->can('manage unit')) {
            return abort(401, __('Permission denied.'));
        }
        $units = Unit::where('archive', 0)->orderBy('id', 'desc')->get();
        return view('setting.unit',compact('units'));
    }

    public function unit_view()
    {
        if (!\Auth::user()->can('manage unit')) {
            return abort(401, __('Permission denied.'));
        }

        $data = Unit::where('archive', 0)->orderBy('id', 'desc')->get();
        return view('setting.unit_view', compact('data'));
    }

    public function getUnit(Request $request)
    {
        $search_text = $request->search_text;

        $user = \Auth::user();
        $data = Unit::where('archive', 0);

        // Search filter on waste picker name, weather, or payment_status
        if (!empty($search_text)) {
            $data->where('name', 'like', "%$search_text%");
        }

        $result = $data->orderBy('id', 'desc')->get();

        return response()->json(['status' => 200, 'message' => "Data Exist", 'data' => $result]);
    }

    public function saveUnit(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'name' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 500, 'message' => $validator->errors()->first()]);
            }

            DB::beginTransaction();
            $hidden_id = $request->input('hidden_id');

            $data = [
                'name' => $request->name,
                'base_unit_id' => $request->base_unit_id,
                'operation_type' => $request->operation_type,
                'operation_value' => $request->operation_value,
                'archive' => 0,
            ];

            if (!empty($hidden_id)) {
                if (!\Auth::user()->can('edit unit')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to edit unit"]);
                }

                $data['updated_at'] = now();
                $id = Crypt::decrypt($hidden_id);
                Unit::where('id', $id)->update($data);
                $message = 'Unit updated successfully';
                $this->saveActivityLog("Unit", "Update Unit Id " . $id);
            } else {
                if (!\Auth::user()->can('create unit')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to create unit"]);
                }

                $validator = \Validator::make($request->all(), [
                    'name' => 'required|unique:units,name',
                ]);

                if ($validator->fails()) {
                    return response()->json(['status' => 500, 'message' => $validator->errors()->first()]);
                }

                $data['created_by'] = Auth::user()->id;
                $data['created_at'] = now();
                $data['updated_at'] = now();
                $unit = Unit::create($data);
                $message = 'Unit saved successfully';
                $this->saveActivityLog("Unit", "Save Unit Id " . $unit->id);
            }

            DB::commit();
            return response()->json(['status' => 200, 'message' => $message]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function editUnit($id)
    {
        $data = Unit::find($id);
        echo json_encode(['data' => $data, 'id' => Crypt::encrypt($id)]);
    }

    public function deleteUnit($id)
    {
        if (!\Auth::user()->can('delete unit')) {
            return response()->json(['status' => 500, 'message' => "No permission to delete unit"]);
        }

        try {
            Unit::where('id', $id)->update(['archive' => 1, 'updated_at' => now()]);
            $this->saveActivityLog("Unit", "Archive Unit Id " . $id);
            return response()->json(['status' => 200, 'message' => "Unit archived successfully"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function waste_source()
    {
        if (!\Auth::user()->can('manage waste source')) {
            return abort(401, __('Permission denied.'));
        }

        return view('setting.waste_source');
    }

    public function waste_source_view()
    {
        if (!\Auth::user()->can('manage waste source')) {
            return abort(401, __('Permission denied.'));
        }

        $data = WasteSource::where('archive', 0)->orderBy('id', 'desc')->get();
        return view('setting.waste_source_view', compact('data'));
    }

    public function saveWasteSource(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'name' => 'required',
                'description' => 'nullable',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 500, 'message' => $validator->errors()->first()]);
            }

            DB::beginTransaction();
            $hidden_id = $request->input('hidden_id');

            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'archive' => 0,
            ];

            if (!empty($hidden_id)) {
                if (!\Auth::user()->can('edit waste source')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to edit waste source"]);
                }

                $data['updated_at'] = now();
                $id = Crypt::decrypt($hidden_id);
                WasteSource::where('id', $id)->update($data);
                $message = 'Waste Source updated successfully';
                $this->saveActivityLog("Waste Source", "Update Waste Source Id " . $id);
            } else {
                if (!\Auth::user()->can('create waste source')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to create waste source"]);
                }

                $validator = \Validator::make($request->all(), [
                    'name' => 'required|unique:waste_sources,name',
                ]);

                if ($validator->fails()) {
                    return response()->json(['status' => 500, 'message' => $validator->errors()->first()]);
                }

                $data['created_by'] = Auth::user()->id;
                $data['created_at'] = now();
                $data['updated_at'] = now();
                $wasteSource = WasteSource::create($data);
                $message = 'Waste Source saved successfully';
                $this->saveActivityLog("Waste Source", "Save Waste Source Id " . $wasteSource->id);
            }

            DB::commit();
            return response()->json(['status' => 200, 'message' => $message]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function editWasteSource($id)
    {
        $data = WasteSource::find($id);
        echo json_encode(['data' => $data, 'id' => Crypt::encrypt($id)]);
    }

    public function deleteWasteSource($id)
    {
        if (!\Auth::user()->can('delete waste source')) {
            return response()->json(['status' => 500, 'message' => "No permission to delete waste source"]);
        }

        try {
            WasteSource::where('id', $id)->update(['archive' => 1, 'updated_at' => now()]);
            $this->saveActivityLog("Waste Source", "Archive Waste Source Id " . $id);
            return response()->json(['status' => 200, 'message' => "Waste Source archived successfully"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function waste_type()
    {
        if (!\Auth::user()->can('manage waste type')) {
            return abort(401, __('Permission denied.'));
        }

        $wasteSources = WasteSource::where('archive', 0)->orderBy('id', 'desc')->get();
        $wasteTypes = WasteType::where('archive', 0)->where('parent_id',null)->orderBy('id', 'desc')->get();
        return view('setting.waste_type',compact('wasteSources','wasteTypes'));
    }

    public function waste_type_view()
    {
        if (!\Auth::user()->can('manage waste type')) {
            return abort(401, __('Permission denied.'));
        }

        $allSources = WasteSource::all()->keyBy('id');
        $data = WasteType::where('archive', 0)->orderBy('id', 'desc')->get();
        return view('setting.waste_type_view', compact('data','allSources'));
    }

    public function getWasteType(Request $request)
    {
        $parent_id = $request->parent_id;
        $search_text = $request->search_text;

        $user = \Auth::user();
        $data = WasteType::where('archive', 0)->where('parent_id',null);

        // Search filter on waste picker name, weather, or payment_status
        if (!empty($search_text)) {
            $data->where('name', 'like', "%$search_text%");
        }

        $result = $data->orderBy('id', 'desc')->get();

        return response()->json(['status' => 200, 'message' => "Data Exist", 'data' => $result]);
    }

    public function getSubWasteType(Request $request)
    {
        $parent_id = $request->parent_id;
        $search_text = $request->search_text;

        $user = \Auth::user();
        $data = WasteType::where('archive', 0)->where('parent_id',$parent_id);

        // Search filter on waste picker name, weather, or payment_status
        if (!empty($search_text)) {
            $data->where('name', 'like', "%$search_text%");
        }

        $result = $data->orderBy('id', 'desc')->get();

        return response()->json(['status' => 200, 'message' => "Data Exist", 'data' => $result]);
    }

    public function saveWasteType(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'name' => 'required',
                'description' => 'nullable',
                'waste_sources' => 'nullable|array|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 500, 'message' => $validator->errors()->first()]);
            }

            DB::beginTransaction();
            $hidden_id = $request->input('hidden_id');

            $data = [
                'parent_id' => $request->parent_id ?? null,
                'name' => $request->name,
                'description' => $request->description,
                'waste_sources' => json_encode($request->waste_sources ?? []),
                'archive' => 0,
            ];

            if (!empty($hidden_id)) {
                if (!\Auth::user()->can('edit waste type')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to edit waste type"]);
                }

                $data['updated_at'] = now();
                $id = Crypt::decrypt($hidden_id);
                WasteType::where('id', $id)->update($data);
                $message = 'Waste Type updated successfully';
                $this->saveActivityLog("Waste Type", "Update Waste Type Id " . $id);
            } else {
                if (!\Auth::user()->can('create waste type')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to create waste type"]);
                }

                $validator = \Validator::make($request->all(), [
                    'name' => 'required|unique:waste_types,name',
                ]);

                if ($validator->fails()) {
                    return response()->json(['status' => 500, 'message' => $validator->errors()->first()]);
                }

                $data['created_by'] = Auth::user()->id;
                $data['created_at'] = now();
                $data['updated_at'] = now();
                $wasteType = WasteType::create($data);
                $message = 'Waste Type saved successfully';
                $this->saveActivityLog("Waste Type", "Save Waste Type Id " . $wasteType->id);
            }

            DB::commit();
            return response()->json(['status' => 200, 'message' => $message]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function editWasteType($id)
    {
        $data = WasteType::find($id);
        echo json_encode(['data' => $data, 'id' => Crypt::encrypt($id)]);
    }

    public function deleteWasteType($id)
    {
        if (!\Auth::user()->can('delete waste type')) {
            return response()->json(['status' => 500, 'message' => "No permission to delete waste type"]);
        }

        try {
            WasteType::where('id', $id)->update(['archive' => 1, 'updated_at' => now()]);
            $this->saveActivityLog("Waste Type", "Archive Waste Type Id " . $id);
            return response()->json(['status' => 200, 'message' => "Waste Type archived successfully"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function illegal_dumping()
    {
        if (!\Auth::user()->can('manage illegal dumping')) {
            return abort(401, __('Permission denied.'));
        }

        return view('illegal_dumping.index');
    }

    public function illegal_dumping_view()
    {
        if (!\Auth::user()->can('manage illegal dumping')) {
            return abort(401, __('Permission denied.'));
        }

        $data = IllegalDumping::where('archive', 0)->orderBy('id', 'desc')->get();
        return view('illegal_dumping.view', compact('data'));
    }

    public function getIllegalDumpingRow($id)
    {
        if (!\Auth::user()->can('show illegal dumping')) {
            return response()->json([
                'status' => 500,
                'message' => "No permission to show illegal dumping"
            ]);
        }

        $data = IllegalDumping::find($id);
        return response()->json($data);
    }

    public function getIllegalDumping(Request $request)
    {
        if (!\Auth::user()->can('show illegal dumping')) {
            return response()->json([
                'status' => 500,
                'message' => "No permission to show illegal dumping"
            ]);
        }

        $search_term = $request->input('search');

        $query = IllegalDumping::query();

        if ($search_term) {
            $query->where(function ($q) use ($search_term) {
                $q->where('location_name', 'like', '%' . $search_term . '%')
                  ->orWhere('description', 'like', '%' . $search_term . '%');
            });
        }

        $data = $query->latest()->get();

        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }




    public function saveIllegalDumping(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'location_name' => 'required',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'description' => 'required',
                'photo' => 'nullable|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 500, 'message' => $validator->errors()->first()]);
            }

            DB::beginTransaction();
            $hidden_id = $request->input('hidden_id');

            $data = [
                'user_id' => Auth::user()->id,
                'location_name' => $request->location_name,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'description' => $request->description,
                'archive' => 0,
            ];

            if ($request->hasFile('photo')) {
                $photos = [];
                foreach ($request->file('photo') as $file) {
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '_' . uniqid() . '.' . $extension;
                    $path = $file->storeAs('documents', $filename, 'public');
                    $photos[] = $path;
                }
                // Store as JSON string in DB (assuming `photo` column is text)
                $data['photo'] = json_encode($photos);
            }


            if (!empty($hidden_id)) {
                if (!\Auth::user()->can('edit illegal dumping')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to edit illegal dumping"]);
                }

                $data['updated_at'] = now();
                $id = Crypt::decrypt($hidden_id);
                IllegalDumping::where('id', $id)->update($data);
                $message = 'Illegal Dumping updated successfully';
                $this->saveActivityLog("Illegal Dumping", "Update Illegal Dumping Id " . $id);
            } else {
                if (!\Auth::user()->can('create illegal dumping')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to create illegal dumping"]);
                }

                $data['created_by'] = Auth::user()->id;
                $data['created_at'] = now();
                $data['updated_at'] = now();
                $record = IllegalDumping::create($data);
                $message = 'Illegal Dumping saved successfully';
                $this->saveActivityLog("Illegal Dumping", "Save Illegal Dumping Id " . $record->id);
            }

            DB::commit();
            return response()->json(['status' => 200, 'message' => $message]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function editIllegalDumping($id)
    {
        $data = IllegalDumping::find($id);
        echo json_encode(['data' => $data, 'id' => Crypt::encrypt($id)]);
    }

    public function deleteIllegalDumping($id)
    {
        if (!\Auth::user()->can('delete illegal dumping')) {
            return response()->json(['status' => 500, 'message' => "No permission to delete illegal dumping"]);
        }

        try {
            IllegalDumping::where('id', $id)->update(['archive' => 1, 'updated_at' => now()]);
            $this->saveActivityLog("Illegal Dumping", "Archive Illegal Dumping Id " . $id);
            return response()->json(['status' => 200, 'message' => "Illegal Dumping archived successfully"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }


}
