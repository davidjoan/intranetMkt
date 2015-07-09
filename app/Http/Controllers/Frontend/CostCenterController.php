<?php namespace IntranetMkt\Http\Controllers\Frontend;

use Illuminate\Routing\ResponseFactory;
use IntranetMkt\Http\Requests;
use IntranetMkt\Http\Controllers\Controller;

use Illuminate\Http\Request;
use IntranetMkt\Models\CostCenter;

class CostCenterController extends Controller {


    protected $costCenter;
    private $responseFactory;

    public function __construct(CostCenter $costCenter, ResponseFactory $responseFactory)
    {
        $this->costCenter = $costCenter;
        $this->responseFactory = $responseFactory;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $costCenters = $this->costCenter->all();
        return $this->responseFactory->json($costCenters);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
