<?php

function returnResponse($status, $data, $msg, array $headers = null)
{
    $content = ['status' => $status, 'message' => $msg, 'result' => $data];
    $response = response()->json(clearNull($content), 200);
    if ($headers && request()->has('Timezone')) {
        return $response->withHeaders($headers);
    }
    return $response;
}

function clearNull($data)
{
    if (request()->header('Device-Type', 'web') != "web") {
        return json_decode(json_encode($data));
    }
    return json_decode(preg_replace('/(\s*"[^"]+")\s*:\s*null/', '$1:""', json_encode($data)));
}
