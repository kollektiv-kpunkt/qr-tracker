<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCodeRequest;
use App\Http\Requests\UpdateCodeRequest;
use Illuminate\Http\Request;
use App\Models\Code;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
use ZipArchive;


class CodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('codes.index', [
            "codes" => DB::table("codes")->where("user_id", auth()->user()->id)->paginate(15)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('codes.create', [
            "code" => new Code()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCodeRequest  $request
     * @return \Illuminate\Http\Response
     *
     * @todo Replace QR Code Library with a better one
     * @body The current QR Code library is not very good. It apparently throws errors randomly (https://github.com/endroid/qr-code/issues/365) maybe we should switch to a better one.
     */
    public function store(StoreCodeRequest $request)
    {
        $validated = $request->validated();
        $validated["uuid"] = urlencode(urldecode($validated["uuid"]));
        if (!str_starts_with( $validated["link"], "http" )) {
            $validated["link"] = "http://" . $validated["link"];
        }
        $code = Code::create($validated);
        return redirect()->route('codes.show', ['code' => $code->uuid]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Code  $code
     * @return \Illuminate\Http\Response
     */
    public function show(Code $code)
    {
        $code->fg_colorRGB = sscanf($code->fg_color, "#%02x%02x%02x");
        $code->bg_colorRGB = sscanf($code->bg_color, "#%02x%02x%02x");
        $qr = QRCode::size(500)->format("svg")->color($code->fg_colorRGB[0], $code->fg_colorRGB[1], $code->fg_colorRGB[2])->backgroundColor($code->bg_colorRGB[0], $code->bg_colorRGB[1], $code->bg_colorRGB[2])->generate(request()->getSchemeAndHttpHost() . env("APP_REDIRECT_BASE", "/r/") . $code->uuid . "?qr=1");
        return view('codes.show', [
            "code" => $code,
            "qr" => $qr
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Code  $code
     * @return \Illuminate\Http\Response
     */
    public function edit(Code $code)
    {
        return view('codes.edit', [
            "code" => $code
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCodeRequest  $request
     * @param  \App\Models\Code  $code
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCodeRequest $request, Code $code)
    {
        $validated = $request->validated();
        $validated["uuid"] = urlencode(urldecode($validated["uuid"]));
        if (!str_starts_with( $validated["link"], "http" )) {
            $validated["link"] = "http://" . $validated["link"];
        }
        Code::where("uuid", $code->uuid)->update($validated);
        return redirect()->route('codes.show', ['code' => $validated["uuid"]]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Code  $code
     * @return \Illuminate\Http\Response
     */
    public function destroy(Code $code)
    {
        $code->delete();
        return redirect()->route('codes.index');
    }

    public function redirect(Code $code)
    {
        $code->scans++;
        if (!isset($_COOKIE["scanned_" . $code->uuid])) {
            $code->u_scans++;
            setcookie("scanned_" . $code->uuid, "true", time() + 60 * 60 * 24 * 365, "/");
        }
        if (isset($_GET["qr"])) {
            $code->qr_scans++;
        }
        $code->save();
        return redirect($code->link);
    }

    public function export() {
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="' . date("Y-m-d") . '_codes.csv";');
        $f = fopen('php://output', 'w');

        $codes = DB::table("codes")->where("user_id", auth()->user()->id)->get();
        fputcsv($f, ["UUID", "Link", "Scans", "Unique Scans", "Created At", "Updated At"]);
        foreach ($codes as $code) {
            fputcsv($f, [$code->uuid, $code->link, $code->scans, $code->u_scans, $code->created_at, $code->updated_at]);
        }
        fclose($f);
        redirect()->route('codes.index');
    }

    public function exportSvg() {
        $codes = DB::table("codes")->where("user_id", auth()->user()->id)->get();
        $zipFileName = "codes-" . date("Y-m-d-G:i:s") . ".zip";
        $zip = new ZipArchive;
        $zip->open($zipFileName, ZipArchive::CREATE);
        $codes->each(function($code) use ($zip) {
            $code->fg_colorRGB = sscanf($code->fg_color, "#%02x%02x%02x");
            $code->bg_colorRGB = sscanf($code->bg_color, "#%02x%02x%02x");
            $qr = QRCode::size(500)->format("svg")->color($code->fg_colorRGB[0], $code->fg_colorRGB[1], $code->fg_colorRGB[2])->backgroundColor($code->bg_colorRGB[0], $code->bg_colorRGB[1], $code->bg_colorRGB[2])->generate(request()->getSchemeAndHttpHost() . env("APP_REDIRECT_BASE", "/r/") . $code->uuid . "?qr=1");
            $qr_name = str_replace(" ", "-", strtolower($code->name)) . '-' . $code->uuid . ".svg";
            $zip->addFromString($qr_name, $qr);
        });
        $zip->close();
        header("Content-type: application/zip");
        header("Content-Disposition: attachment; filename=$zipFileName");
        header("Content-length: " . filesize($zipFileName));
        readfile("$zipFileName");
        unlink($zipFileName);
    }

    public function import(Request $request) {
        $file = request()->file("import_csv");
        $file->move(storage_path("app/public"), $file->getClientOriginalName());
        $f = fopen(storage_path("app/public/{$file->getClientOriginalName()}"), "r");
        $header = fgetcsv($f);
        while (($line = fgetcsv($f)) !== FALSE) {
            $data = array_combine($header, $line);
            $data["user_id"] = auth()->user()->id;
            if ($data["fg_color"] == "") {
                unset($data["fg_color"]);
            }
            if ($data["bg_color"] == "") {
                unset($data["bg_color"]);
            }
            Code::create($data);
        }
        fclose($f);
        return redirect()->route('tools')->with("status", "codes-imported");
    }

    public function deleteAll() {
        Code::where("user_id", auth()->user()->id)->delete();
        return redirect()->route('tools')->with("status", "codes-deleted");
    }
}
