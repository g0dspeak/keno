<?php

use Symfony\Component\VarDumper\VarDumper;
use Illuminate\Support\Debug\Dumper;

/* dumper */
if (!function_exists('dd')) {
    function dd()
    {
        $callstack = debug_backtrace();
        dd_dumpHeader($callstack);

        $dd = new VarDumper();
//        $dd = new Dumper();

        foreach (func_get_args() as $var) {
            $dd->dump($var);
        }

        die;
    }
}
if (!function_exists('_d')) {
    function _d()
    {
        $callstack = debug_backtrace();
        dd_dumpHeader($callstack);

        $dd = new VarDumper();
//        $dd = new Dumper();

        foreach (func_get_args() as $var) {
            $dd->dump($var);
        }
    }
}

function dd_dumpHeader($callstack)
{
    echo "<pre><hr/><div style='clear: both; background-color: rgb(243, 246, 249)'>";
    $calledFromOutput = "\r\n<span style='color:rgb(116, 114, 19); text-decoration: underline dotted darkslategrey;'>called from: "
        . $callstack[0]['file'] . " : " . $callstack[0]['line'] . "</span>";

    echo "\r\n<b><strong>" . $calledFromOutput . "</strong></b>";
    echo "</div><hr/><br/>";
}
/* /dumper */

function getParamFromJson($json, $param)
{
    return @json_decode($json, 1)[$param];
}

/**
 * Append query row with custom params
 *
 * @param array $params
 * @return string
 */
function appendParamsToQuery($params = [])
{
    parse_str(\Illuminate\Support\Facades\Request::getQueryString(), $urlParsed);
    $newUrlParsed = array_merge($urlParsed, $params);
    $newUrl = '?' . http_build_query($newUrlParsed);

    return \Illuminate\Support\Facades\Request::url() . $newUrl;
}

/**
 * Detach params from the query row
 *
 * @param array $params
 * @return string
 */
function popParamsFromQuery($params = [])
{
    parse_str(\Illuminate\Support\Facades\Request::getQueryString(), $urlParsed);
    $urlParsed = array_diff_key($urlParsed, array_flip($params));

    $newUrl = http_build_query($urlParsed);

    return \Illuminate\Support\Facades\Request::url() . ($newUrl ? '?' . $newUrl : null);
}

/**
 * Check whether query row has custom param
 * 
 * @param $param
 * @return bool
 */
function queryHasParam($param)
{
    parse_str(\Illuminate\Support\Facades\Request::getQueryString(), $urlParsed);
    
    return array_key_exists($param, $urlParsed);
}

/*--------- debug page functionality -------------*/
function debugEmailOptions($user)
{
    return [
        'name' => $user->name,
        'intro' => 'We congratulate you on the successful registration on our website ! Please active your account : ',
        'buttonLink' => route('account.activate', str_random(30)),
        'buttonText' => 'Activate account',
    ];
}

function sendDebugEmail()
{
    $user = auth()->user();
    
    try {
        \Illuminate\Support\Facades\Mail::send(
            'email.registration_confirm',
            debugEmailOptions($user),
            function ($m) use ($user) {
                $m->from(env('MAIL_FROM'), env('MAIL_SENDER'));
                $m->to($user->email, $user->name)->subject('Playright registration');
            }
        );
    } catch (Exception $e) {
        flash()->error('Whoops!', sprintf('There was some error during sending email: $s', $e->getMessage()));
        redirect()->route('page.debug');
    }

    flash()->success('Great!', sprintf('Testing email has been successfully sent to %s. Please check your email or log file.', $user->email));
    redirect()->route('page.debug');
}

function getApiTokensRoles(\Illuminate\Http\Request $request)
{
    $apiTokensRoles = [];
    
    if ($request->get('apiTokens')) {
        $users = \App\User::all();
        $apiTokensRoles = [];
        foreach ($users as $user) {
            $apiTokensRoles[$user->role->name][$user->email] = $user->api_token;
        }
    }
    
    return $apiTokensRoles;
}

function getLogFile(\Illuminate\Http\Request $request)
{
    $logFileContent = null;
    
    if ($request->get('logFile')) {
        $fileName = storage_path('/logs/laravel.log');
        $logFileContent = file_get_contents($fileName);
        $logFileContent = substr($logFileContent, strpos($logFileContent, '<!DOCTYPE html>'));
    }
    
    return $logFileContent;
}
/*--------- /debug page functionality -------------*/
