<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Packages\Utils\Utils;
use App\Events\CreateProductEvent;
use Illuminate\Support\Facades\DB;
use App\Packages\Queue\QueueService;
use App\QueueEvents\CreateProductQueueEvent;
use App\Packages\RequestInput\SaveProductInput;

class ProductController extends Controller
{
    private QueueService $queueService;

    public function __construct(QueueService $queueService)
    {
        $this->queueService = $queueService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id = null)
    {
        $user = DB::table('users')->where('email', 'nambcvt275@gmail.com')->first();
        $input = $request->all();
        if ($id) {
            $input['id'] = $id;
        }
		$this->queueService->publishEvent(new CreateProductQueueEvent(Utils::getPublishMessageData($user, $input)));

		return response()->json([
            'admin' => true,
            'message' => 'successful',
            'input' => $input
            // 'product_id' => $envelop?->getData(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
