<?php
namespace App\Http\Middleware;

use Illuminate\Database\Eloquent\Model;
use Closure;
use Illuminate\Http\JsonResponse;

class NullToBlank
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $output = $next($request);

        if ($output instanceof JsonResponse) {
            $responseArray = $output->getData();

            if (!empty($responseArray)) {
                $responseArray = json_decode(json_encode($responseArray), true);

                array_walk_recursive($responseArray, function (&$item, $key) {
                    $item = $item === null ? '' : $item;
                });
            }

            return response()->json($responseArray);
        }

        return $output;
    }
}
