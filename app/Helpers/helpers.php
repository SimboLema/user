<?php




//return unique request id to be used on tiramis request
use App\Http\Controllers\KMJ\EncryptionServiceController;
use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Spatie\ArrayToXml\ArrayToXml;

function generateRequestID()
{
    return 'RID-KMJ' . date('ymdHis') . time();
}


//return unique id for other request
function otherUniqueID()
{
    return 'KMJ' . date('ymdHis') . time();
}

function generateXML($tiraTag, $data)
{
    try {
        $xml = (new ArrayToXml($data, $tiraTag))->dropXmlDeclaration()->prettify()->toXml();
        Log::channel('tiramisxml')->info("Generated XML before Posted:\n".$xml);
        return $xml;
    } catch (\Exception $e) {
        report($e);
    }
}

/**
 * Recursively remove null, empty string, and empty array values from an array.
 * Helps prevent emitting empty XML tags that TIRA may reject as incomplete.
 */
function removeNullsRecursive($value)
{
    if (is_array($value)) {
        $filtered = [];
        foreach ($value as $key => $child) {
            $cleanChild = removeNullsRecursive($child);
            $isEmptyArray = is_array($cleanChild) && count($cleanChild) === 0;
            $isEmptyString = $cleanChild === '';
            if ($cleanChild === null || $isEmptyString || $isEmptyArray) {
                continue;
            }
            $filtered[$key] = $cleanChild;
        }
        return $filtered;
    }

    return $value;
}

function generateTiraXml($data, $signature): string
{
    try {
        return "<TiraMsg>$data<MsgSignature>$signature</MsgSignature></TiraMsg>";
    } catch (\Exception $e) {
        report($e);
    }
}

function TiraRequest($endPoint, $data): array
{
    // Generate signature
    $signature = EncryptionServiceController::createTiramisSignature($data);

    // Generate XML
    $xml = generateTiraXml($data, $signature);

    // Send request
    $res = Http::withOptions([
        'verify' => false,
        'cert' => public_path('tiramis_certs/certificate.pem'),
        'ssl_key' => public_path('tiramis_certs/private_key.pem'),
    ])
    ->timeout(120)
    ->retry(3, 500)
    ->withHeaders([
        'ClientCode' => 'IB10152',
        'ClientKey'  => '1Xr@Jnq74&cYaSl2',
        'SystemCode' => 'TP_KMJ_001',
        'SystemName' => 'KMJ System',
    ])
    ->withBody($xml, 'application/xml')
    ->post($endPoint)
    ->body();

    // Log response
    Log::channel('tiramisxml')->info("ACKNOWLEDGMENT", [$res]);

    return [
        "response" => $res
    ];
}
function TiraRequestCallBack($data): array
{
    $signature = EncryptionServiceController::createTiramisSignature($data);
    $xml = generateTiraXml($data, $signature);

    Log::channel('tiramisxml')->info('Generated TIRA XML with signature: ' . $xml);
    return [
        'signature' => $signature,
        'xml' => $xml,
    ];
}



function returnTiraDate($date): string
{
    return (new Carbon($date))->format('Y-m-d\\TH:i:s');
}

function getProductId($productCode)
{
    $product = Product::where('product_code', $productCode)->first();
    if (!$product) {
        // Optional: create the product if not found
        $product = Product::create([
            'code' => $productCode,
            'name' => 'Unknown Product', // or provide a name dynamically
        ]);
    }
    return $product->id;
}

function processImage($imagePath)
{
    if (!file_exists($imagePath)) {
        // 1x1 transparent PNG fallback
        return 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';
    }

    $handle = fopen($imagePath, 'rb');
    $contents = '';

    while (!feof($handle)) {
        $contents .= fread($handle, 8192); // read 8KB
    }

    fclose($handle);

    return base64_encode($contents);
}
